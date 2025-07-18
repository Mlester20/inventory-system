<?php

    //define variables
    $host = 'localhost';
    $user_name = 'root';
    $password = '';
    $db = 'inventorydb';

    //try and catch block
    try{
        $con = new mysqli($host, $user_name, $password, $db);

        if($con->connect_error){
            throw new Exception("Connection failed" . $con->connect_error);
        }

    }catch(Exception $e) {
        die("Mysqli Error" . $e->getMessage());
    }

?>