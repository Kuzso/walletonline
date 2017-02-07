<?php

  require("config.inc.php");
  
  $query = "
          SELECT DISTINCT 
            `yearmonth` 
          FROM  `inout` 
          LIMIT 1
         ";
  
      try {
        $stmt   = $db->prepare($query);
        $result = $stmt->execute();
    }
    catch (PDOException $ex) {
        // For testing, you could use a die and message. 
        //die("Failed to run query: " . $ex->getMessage());
        
        //or just use this use this one to product JSON data:
        $response["success"] = 0;
        $response["message"] = $ex->getMessage();
       echo die(json_encode($response));
        
    }
  
    $response["success"] = 1;
    $response["message"] = "Services available.";
    
    echo json_encode($response);
?>