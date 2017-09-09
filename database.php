<?php

/*
 * To change this template, choose Tools | Templates
 * and open th$servername="localhost";
        $username="root";
        $password="root";
        $db="ebabu";
        $conn=new mysqli($servername,$username,$password,$db);
        if($conn->connect_error)
        {
            echo "connection failed: ".$conn->connect_error;
        }
?>e template in the editor.
 */
$servername="localhost";
        $username="root";
        $password="";
        $db="listing_new_version";
        $conn=new mysqli($servername,$username,$password,$db);
        if($conn->connect_error)
        {
            echo "connection failed: ".$conn->connect_error;
        }
?>
