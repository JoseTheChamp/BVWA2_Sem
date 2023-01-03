<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if ($_SESSION["userId"] === null) {
    header("location: ../gallery.php");
}

if (isset($_GET["workId"])) {
    $id = $_GET["workId"];
    if (isset($_GET["action"])) {
        if ($_GET["action"] === "add") {
            addFavorite($conn, $_SESSION["userId"], $_GET["workId"]);
            header("location: ../view.php?id=$id&res=added");
            exit();
        } else if ($_GET["action"] === "remove") {
            removeFavorite($conn, $_SESSION["userId"], $_GET["workId"]);
            header("location: ../view.php?id=$id&res=removed");
            exit();
        } else {
            header("location: ../view.php?id=$id&error=WrongAction");
            exit();
        }
    } else {
        header("location: ../view.php?id=$id&error=NoActionStated");
        exit();
    }
}
header("location: ../index.php");
exit();