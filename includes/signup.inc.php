<?php
if (isset($_POST["submit"])){
    $uid = $_POST["uid"];
    $email = $_POST["email"];
    $pwd = $_POST["pwd"];
    $pwdRepeat = $_POST["pwdrepeat"];

    require_once 'dbh.inc.php';
    require_once  'functions.inc.php';


    if(emptyInputSignup($uid,$email,$pwd,$pwdRepeat) !== false){
        header("location: ../signup.php?error=emptyinput");
        exit();
    }
    if(invalidUid($uid) !== false){
        header("location: ../signup.php?error=invaliduid");
        exit();
    }
    if(invalidEmail($email) !== false){
        header("location: ../signup.php?error=invalidemail");
        exit();
    }
    if(!pwdMatch($pwd,$pwdRepeat) !== false){
        header("location: ../signup.php?error=passwordsdontmatch");
        exit();
    }

    if(uidExists($conn,$uid,$email)){
        header("location: ../signup.php?error=usernametaken");
        exit();
    }

    createUser($conn,$email,$uid,$pwd);

}else{
    header("location: ../signup.php");
}