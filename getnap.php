<?php
 
/*
 * Following code will list all the products
 */
 
//load and connect to MySQL database stuff
require("config.inc.php");

if (!empty($_POST)) {

// array for JSON response
$response = array(); 

$query = "SELECT DISTINCT `datum` 
          FROM `inout` 
          WHERE `uid`= :uid 
          and `yearmonth`= :yearmonth";
          
$query_params = array(
  ':uid' => $_POST['uid'],
  ':yearmonth' => $_POST['yearmonth']
);

try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one to product JSON data:
        $response["success"] = 0;
        $response["message"] = $ex->getMessage();
        die(json_encode($response));
        
    }
 
// check for empty result
if ($result) {
    // looping through all results
    $response["products"] = array();
    
    while ($row = $stmt->fetch()) {
        $query2 = "
                  Select `cimke` 
                  from `inout` 
                  WHERE `uid` = :uid 
                    and `yearmonth` = :yearmonth
                    and `datum` = :datum
                    ";
        
        $query_params2 = array(
          ':uid' => $_POST['uid'],
          ':yearmonth' => $_POST['yearmonth'],
          ':datum' => $row['datum']
         );
        
        try {
        $stmt2   = $db->prepare($query2);
        $result2 = $stmt2->execute($query_params2);
        }
        catch (PDOException $ex) {
            // For testing, you could use a die and message. 
            //die("Failed to run query: " . $ex->getMessage());
            
            //or just use this use this one to product JSON data:
            $response["success"] = 0;
            $response["message"] = $ex->getMessage();
            die(json_encode($response));
            
        }
        $numrec = $stmt2->rowCount();
       // $product["pid"] = $row["id"];
        $product["name"] = $row["datum"];
        $product["props"] = "".$numrec." records";
        // push single product into final response array
        array_push($response["products"], $product);
    }
  
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "Nothing found";
 
    // echo no users JSON
    echo json_encode($response);
  }
} else {
  
  ?>
		<h1>Test data</h1> 
		<form action="getnap.php" method="post"> 
		    User id:<br /> 
		    <input type="text" name="uid" placeholder="User id" /> <br />
        Year/month:<br /> 
		    <input type="text" name="yearmonth" placeholder="Year/month" /> 
		    <br /><br /> 
		    <input type="submit" value="Get Data" /> 
		</form> 
    <a href="login.php">Login</a>
		<a href="register.php">Register</a>
	<?php
    
}
?>