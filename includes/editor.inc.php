<?php
session_start();
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

    if ($_SESSION["userId"] === null){
        header("location: ../editor.php?error=usernotloggedin");
        exit();
    }
    if(emptyInputEditor($name,$part,$series,$author,$age,$text,$genres) !== false){
        header("location: ../editor.php?error=emptyinput");
        exit();
    }
    if (isset($_SESSION["modify"])){
        updateWork($conn,$_SESSION["modify"],$_SESSION["userId"],$name,$part,$series,$author,$age,$genres,$tags,$text);
    }else{
        createWork($conn,$_SESSION["userId"],$name,$part,$series,$author,$age,$genres,$tags,$text);
    }
}else{
    header("location: ../login.php");
}