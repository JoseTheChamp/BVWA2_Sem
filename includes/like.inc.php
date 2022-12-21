<?php
session_start();
require_once 'dbh.inc.php';
require_once 'functions.inc.php';

if ($_SESSION["userId"] === null){
    header("location: ../index.php?error=notLoggedIn");
    exit();
}
if (isset($_GET["id"])){ // wrong id might (aka non existent) cause error
    if (isset($_GET["remove"])){
        if (didUserLikedWork($conn,$_SESSION["userId"],$_GET["id"])){
            removeLikeWork($conn,$_SESSION["userId"],$_GET["id"]);
            $key = "id";
            header("location: ../view.php?id=$_GET[$key]");
            exit();
        }else{
            header("location: ../index.php?error=idDidNotHaveLike");
            exit();
        }
    }else{
        if (!didUserLikedWork($conn,$_SESSION["userId"],$_GET["id"])){
            likeWork($conn,$_SESSION["userId"],$_GET["id"]);
            $key = "id";
            header("location: ../view.php?id=$_GET[$key]");
            exit();
        }else{
            header("location: ../index.php?error=idWasAlreadyLiked");
            exit();
        }
    }
}else{
    header("location: ../index.php?error=idToLikeWasNotSpecified");
    exit();
}

