<?php
 
/*
 * Following code will list all the products
 */
 
// array for JSON response
$response = array();
 
// include db connect class
require_once __DIR__ . '/db_connect.php';
 
// connecting to db
$db = new DB_CONNECT();
 
// get all products from products table
$result = mysql_query("SELECT DISTINCT `datum` FROM `inout` WHERE `uid`='".$_POST['uid']."' and `yearmonth`='".$_POST['yearmonth']."'") or die(mysql_error());
 
// check for empty result
if (mysql_num_rows($result) > 0) {
    // looping through all results
    // products node
    $response["products"] = array();
 
    while ($row = mysql_fetch_array($result)) {
        $recs = mysql_query("Select `cimke` from `inout` WHERE `uid`='".$_POST['uid']."' and `yearmonth`='".$_POST['yearmonth']."'
        and `datum`='".$row["datum"]."'");
        $numrec = mysql_num_rows($recs);
        $product = array();
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
    $response["message"] = "No products found";
 
    // echo no users JSON
    echo json_encode($response);
}
?>