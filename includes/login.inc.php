<?php

if (isset($_POST["submit"])){
    $uid = $_POST["name"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    if(emptyInputlogin($uid,$pwd) !== false){
        header("location: ../login.php?error=emptyinput");
        exit();
    }

    loginUser($conn,$uid,$pwd);

}else{
    header("location: ../login.php");
    exit();
}