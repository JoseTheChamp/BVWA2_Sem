<?php

function uidExists($conn,$uid,$email){
    $sql = "SELECT * FROM users WHERE userUid = ? OR userEmail = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ss",$uid,$email);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)){
        mysqli_stmt_close($stmt);
        return $row;
    }else{
        mysqli_stmt_close($stmt);
        $result = false;
        return $result;
    }
}

function createUser($conn, $email,$uid,$pwd){
    $sql = "INSERT INTO users (userEmail,userUid,userPwd) VALUES (?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }

    $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);

    mysqli_stmt_bind_param($stmt,"sss",$email,$uid,$hashedPwd);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    header("location: ../index.php");
    exit();
}

function loginUser($conn,$uid,$pwd){
    $uidExists = uidExists($conn,$uid,$uid);

    if($uidExists === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }

    $pwdHashed = $uidExists["userPwd"];
    $checkPwd = password_verify($pwd,$pwdHashed);
    if ($checkPwd === false){
        header("location: ../login.php?error=wronglogin");
        exit();
    }else if($checkPwd === true){
        session_start();
        $_SESSION["userId"] = $uidExists["userId"];
        $_SESSION["userUid"] = $uidExists["userUid"];
        $_SESSION["userEmail"] = $uidExists["userEmail"];
        header("location: ../index.php");
        exit();
    }

}