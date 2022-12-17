<?php

if (isset($_POST["submit"])){
    $uid = $_POST["name"];
    $pwd = $_POST["pwd"];

    require_once 'dbh.inc.php';
    require_once 'functions.inc.php';

    //TODO - kontrola vstutpu + reporting

    loginUser($conn,$uid,$pwd);

}else{
    header("location: ../login.php");
    exit();
}