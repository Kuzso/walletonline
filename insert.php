<?php

/*
Our "config.inc.php" file connects to database every time we include or require
it within a php script.  Since we want this script to add a new user to our db,
we will be talking with our database, and therefore,
let's require the connection to happen:
*/
require("config.inc.php");

//if posted data is not empty
if (!empty($_POST)) {
    //If the username or password is empty when the user submits
    //the form, the page will die.
    //Using die isn't a very good practice, you may want to look into
    //displaying an error message within the form instead.  
    //We could also do front-end form validation from within our Android App,
    //but it is good to have a have the back-end code do a double check.
    if (empty($_POST['osszeg'])) {
        
        
        // Create some data that will be the JSON response 
        $response["success"] = 0;
        $response["message"] = "Error00: It is an empty message";
        
        //die will kill the page and not execute any code below, it will also
        //display the parameter... in this case the JSON data our Android
        //app will parse
        die(json_encode($response));
    }
    
    
    //If we have made it here without dying, then we are in the clear to 
    //create a new user.  Let's setup our new query to create a user.  
    //Again, to protect against sql injects, user tokens such as :user and :pass
    $query = "INSERT INTO `inout` ( `cimke`, `befizetes`, `osszeg`, `pnem`, `yearmonth`, `datum`, `ido`, `etc`, `uid` ) VALUES ( :cimke, :befizetes, :osszeg, :pnem, :yearmonth, :datum, :ido, :etc, :uid )";
    
    //Again, we need to update our tokens with the actual data:
    $query_params = array(
        ':cimke' => $_POST['cimke'],
        ':befizetes' => $_POST['befizetes'],
        ':osszeg' => $_POST['osszeg'],
        ':pnem' => $_POST['pnem'],
        ':yearmonth' => $_POST['yearmonth'],
        ':datum' => $_POST['date'],
        ':ido' => $_POST['time'],
        ':uid' => $_POST['uid'],
        ':etc' => $_POST['etc']
    );
    
    //time to run our query, and create the user
    try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute($query_params);
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one:
        $response["success"] = 0;
        $response["message"] = "Database Error01. Please Try Again!".$ex->getMessage();
        die(json_encode($response));
    }
    
    //If we have made it this far without dying, we have successfully added
    //a new user to our database.  We could do a few things here, such as 
    //redirect to the login page.  Instead we are going to echo out some
    //json data that will be read by the Android application, which will login
    //the user (or redirect to a different activity, I'm not sure yet..)
    $response["success"] = 1;
    $response["message"] = "Upload completed";
    echo json_encode($response);
    
    //for a php webservice you could do a simple redirect and die.
    //header("Location: login.php"); 
    //die("Redirecting to login.php");
    
    
} 



?>