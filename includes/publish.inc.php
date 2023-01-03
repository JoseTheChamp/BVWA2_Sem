<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if ($_SESSION["userId"] === null) {
    header("location: ../index.php");
}

$work = getWorkFromWorkId($conn,$_GET["id"]);

if ($work["ownerId"] === $_SESSION["userId"]){
    publishWork($conn,$work["workId"]);
}else{
    header("location: ../index.php?error=CannotPublishForeignWork");
    exit();
}
header("location: ../mygallery.php");