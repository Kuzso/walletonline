<?php
 
//load and connect to MySQL database stuff
require("config.inc.php");
 
/*
 * Following code will list all the products
 */
if (!empty($_POST)) {
// array for JSON response
$response = array();

$query = "
          SELECT DISTINCT 
            `yearmonth` 
          FROM  `inout` 
          WHERE `uid` = :uid
         ";
    
    $query_params = array(
        ':uid' => $_POST['uid']
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
    // products node
    $response["products"] = array();
    //fetching all the rows from the query
    $row = $stmt->fetch();
 
        // temp user array
        $product = array();
       // $product["pid"] = $row["id"];
        $product["name"] = $row["yearmonth"];
 
        // push single product into final response array
        array_push($response["products"], $product);
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "No values found";
 
    // echo no users JSON
    echo json_encode($response);
}
} else {
  
  ?>
		<h1>Test data</h1> 
		<form action="getdatum.php" method="post"> 
		    User id:<br /> 
		    <input type="text" name="uid" placeholder="User id" /> 
		    <br /><br /> 
		    <input type="submit" value="Get Data" /> 
		</form> 
    <a href="login.php">Login</a>
		<a href="register.php">Register</a>
	<?php
  
}
?>