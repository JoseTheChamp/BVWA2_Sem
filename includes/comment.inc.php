<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if ($_SESSION["userId"] === null){
    header("location: ../index.php?error=notLoggedIn");
    exit();
}
if (!isset($_GET["id"])){
    header("location: ../index.php?error=noId");
    exit();
}
$key = "id";
if (isset($_POST["text"])){
        commentOnWork($conn,$_SESSION["userId"],$_GET["id"],$_POST["text"]);
        header("location: ../view.php?id=$_GET[$key]");
        exit();
}else if(isset($_GET["remove"])){
    removeComment($conn,$_GET["remove"]);
    header("location: ../view.php?id=$_GET[$key]");
    exit();
}else{
    header("location: ../view.php?id=$_GET[$key]&error=notAllFieldsWereEntered");
    exit();
}
exit();