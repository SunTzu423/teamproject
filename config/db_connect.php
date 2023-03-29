<?php
$connection = mysqli_connect("localhost", "root", "password", "libraryProject");

if(!$connection) 
    echo "Connection error: " . mysqli_connect_error();
?>