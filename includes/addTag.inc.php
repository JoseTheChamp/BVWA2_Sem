<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if (!isset($_POST["submit"])){
    header("location: ../index.php?error=noTagName");
    exit();
}
if (addTag($conn,$_POST["newTagName"]) === false){
    if (isset($_SESSION["modify"])){
        $id = $_SESSION["modify"];
        header("location: ../editor.php?modify=$id");
        exit();
    }else{
        header("location: ../editor.php?");
        exit();
    }
}
if (isset($_SESSION["modify"])){
    $id = $_SESSION["modify"];
    header("location: ../editor.php?modify=$id&error=thisTagAlreadyExists");
    exit();
}else{
    header("location: ../editor.php?error=thisTagAlreadyExists");
    exit();
}