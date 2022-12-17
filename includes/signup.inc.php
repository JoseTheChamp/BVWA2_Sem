<?php
if (isset($_POST["submit"])){
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];

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