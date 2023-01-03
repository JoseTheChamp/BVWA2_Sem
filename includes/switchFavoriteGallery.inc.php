<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if ($_SESSION["userId"] === null){
    header("location: ../gallery.php");
}

if (isset($_GET["workId"])){
    if (isset($_GET["action"])){
        if ($_GET["action"] === "add"){
            addFavorite($conn,$_SESSION["userId"],$_GET["workId"]);
            header("location: ../gallery.php?res=added");
            exit();
        }else if ($_GET["action"] === "remove"){
            removeFavorite($conn,$_SESSION["userId"],$_GET["workId"]);
            header("location: ../gallery.php?res=removed");
            exit();
        }else{
            header("location: ../gallery.php?error=WrongAction");
            exit();
        }
    }else{
        header("location: ../gallery.php?error=NoActionStated");
        exit();
    }
}
header("location: ../gallery.php");
exit();