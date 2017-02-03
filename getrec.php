<?php
 
/*
 * Following code will list all the products
 */

//load and connect to MySQL database stuff
require("config.inc.php");

if (!empty($_POST)) {

  // array for JSON response
  $response = array();
  
  // get all products from products table
  $query = "SELECT `cimke`,`befizetes`,`osszeg`,`pnem` 
            FROM `inout` 
            WHERE `uid` = :uid
              and `yearmonth` = :yearmonth
              and `datum` = :day";
  
  $query_params = array(
                    ':uid' => $_POST['uid'],
                    ':yearmonth' => $_POST['yearmonth'],
                    ':day' => $_POST['day']
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
  
      while ($row = $stmt->fetch()) {
          $bef="";
          $one="1";
          
          if ($row["befizetes"]==$one) {
            $bef = "Income";
          } else {
            $bef = "Expense";
          }
          
          $product = array();
        // $product["pid"] = $row["id"];
          $product["name"] = $row["cimke"];
          $product["props"] = "".$bef.": ".$row["osszeg"]." ".$row["pnem"]."";
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
      $response["message"] = "No products found";
  
      // echo no users JSON
      echo json_encode($response);
  }

} else {
  
  ?>
		<h1>Test data</h1> 
		<form action="getrec.php" method="post"> 
		    User id:<br /> 
		    <input type="text" name="uid" placeholder="User id" /> <br />
        Year and month:<br /> 
		    <input type="text" name="yearmonth" placeholder="2016/12" /> <br />
        day:<br /> 
		    <input type="text" name="datum" placeholder="Int value" /> 
		    <br /><br /> 
		    <input type="submit" value="Get Data" /> 
		</form> 
	<?php
  
}
?>