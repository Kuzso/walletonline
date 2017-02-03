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
$query = "SELECT `osszeg`, `befizetes` 
           FROM `inout` 
           WHERE `uid`= :uid
             and `pnem`= :pnem";
 
$query_params = array(
                  ':uid' => $_POST['uid'],
                  ':pnem' => $_POST['pnem']
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
    $mny = 0;
 
    while ($row = $stmt->fetch()) {
        if ($row['befizetes']=="1"){
        $mny=$mny+intval($row['osszeg']);
        } else {
        $mny=$mny-intval($row['osszeg']);
        }
    }
    // success
    $response["success"] = 1;
    $response["osszeg"] = intval($mny);
 
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
		<form action="getmoney.php" method="post"> 
		    User id:<br /> 
		    <input type="text" name="uid" placeholder="User id" /> 
        <br />
        Currency:<br /> 
        <input type="text" name="pnem" placeholder="Currency" /> 
		    <br /><br /> 
		    <input type="submit" value="Get Data" /> 
		</form>
	<?php
}
?>