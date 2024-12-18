<?php

    $host = "localhost";
    $username = "root";
    $password = "root123";
    $database = "blogs";

    $conn = new mysqli($host, $username, $password, $database);

    if (!$conn) {
        die("Could not connect to the database");
    }