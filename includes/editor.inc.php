<?php
if (isset($_POST["submit"])){
    $name = $_POST["name"];
    $part = $_POST["part"];
    $series = $_POST["series"];
    $author = $_POST["author"];
    $age = $_POST["age"];
    $genres = $_POST["genres"];
    $tags = $_POST["tags"];
    $text = $_POST["text"];

    require_once 'dbh.inc.php';
    require_once  'functions.inc.php';

    //TODO - kontrola vstupu

    if(uidExists($conn,$uid,$email)){
        header("location: ../signup.php?error=usernametaken");
        exit();
    }

    createUser($conn,$email,$uid,$pwd);

}else{
    header("location: ../signup.php");
}