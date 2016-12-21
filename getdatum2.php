<?php
 
/*
 * Following code will list all the products
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require("config.inc.php");


// get all products from products table
$result = mysql_query("SELECT DISTINCT `yearmonth` FROM `inout` where `uid`= '".$_POST['uid']."'") or die(mysql_error());
 
// check for empty result
if (mysql_num_rows($result) > 0) {
    // looping through all results
    // products node
    $response["months"] = array();
 
    while ($row = mysql_fetch_array($result)) {
        // temp user array
        $product = array();
        $product["month"] = $row["yearmonth"];
        
 
        // push single product into final response array
        array_push($response["months"], $product);
    }
    // success
    $response["success"] = 1;
 
    // echoing JSON response
    echo json_encode($response);
} else {
    // no products found
    $response["success"] = 0;
    $response["message"] = "No Data found";
 
    // echo no users JSON
    echo json_encode($response);
}
?>