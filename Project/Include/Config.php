<?php

// Connect to the Database
$link = new PDO('mysql:host=localhost;dbname=website;charset=utf8',
    'root',
    '');

// Set PDO so that it displays Errors
$link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Set PDO so that the fetched values are in form of Objects
$link->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
