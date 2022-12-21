<?php

function emptyInputSignup($uid,$email,$pwd,$pwdRepeat){
    if(empty($uid) || empty($email) ||empty($pwd) ||empty($pwdRepeat)){
       return true;
    }else{
        return false;
    }
}
function emptyInputEditor($name,$part,$series,$author,$age,$text,$genres){
    if(empty($name) || empty($part) ||empty($series) ||empty($author)||empty($age)||empty($text)||empty($genres)){
        return true;
    }else{
        return false;
    }
}

function emptyInputlogin($uid,$pwd){
    if(empty($uid)||empty($pwd)){
        return true;
    }else{
        return false;
    }
}

function invalidUid($uid){
    if(preg_match("/^[a-zA-Z0-9]*$/",$uid)){
        return false;
    }else{
        return true;
    }
}

function invalidEmail($email){
    if(filter_var($email,FILTER_VALIDATE_EMAIL)){
        return false;
    }else{
        return true;
    }
}

function pwdMatch($pwd,$pwdRepeat) {
    if($pwd === $pwdRepeat){
        return true;
    }else{
        return false;
    }
}


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
        header("location: ../index.php?login=true");
        exit();
    }

}

function getAllGenres($conn){
    $sql = "SELECT genreName FROM genres;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,$row["genreName"]);
    }
    return $list;
}

function getAllTags($conn){
    $sql = "SELECT tagName FROM tags;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,$row["tagName"]);
    }
    return $list;
}

function createWork($conn,$userId,$name,$part,$series,$author,$age,$genres,$tags,$text){
    $sql = "INSERT INTO works (ownerId,workName,workPart,workSeries,workAuthor,minAge,workText) VALUES (?,?,?,?,?,?,?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfailedcreatework");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"sssssss",$userId,$name,$part,$series,$author,$age,$text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    foreach ($genres as &$value) {
        $sql = "INSERT INTO work_genre (workId,genreId) VALUES ( (SELECT workId FROM works WHERE workName = ?), (SELECT genreId FROM genres WHERE genreName = ?));";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt,$sql)){
            header("location: ../editor.php?error=stmtfailedgenres");
            exit();
        }
        mysqli_stmt_bind_param($stmt,"ss",$name,$value);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }
    foreach ($tags as &$value) {
        $sql = "INSERT INTO work_tag (workId,tagId) VALUES ( (SELECT workId FROM works WHERE workName = ?), (SELECT tagId FROM tags WHERE tagName = ?));";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt,$sql)){
            header("location: ../editor.php?error=stmtfailedtags");
            exit();
        }
        mysqli_stmt_bind_param($stmt,"ss",$name,$value);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("location: ../mygallery.php");
    exit();
}

function getAllWorksFromUser($conn,$userId){
    $sql = "SELECT * FROM works WHERE ownerId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../signup.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$userId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,array($row["workName"],$row["workPart"],$row["workSeries"],$row["workAuthor"],$row["workId"],$row["workPub"]));
    }
    mysqli_stmt_close($stmt);
    return $list;
}

function getWorkFromWorkId($conn,$workId){
    $sql = "SELECT * FROM works WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
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

function getAllGenresFromWork($conn,$workId){
    $sql = "SELECT genreName FROM genres JOIN work_genre ON genres.genreId=work_genre.genreId WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,$row);
    }
    mysqli_stmt_close($stmt);
    return $list;
}
function getAllTagsFromWork($conn,$workId){
    $sql = "SELECT tagName FROM tags JOIN work_tag ON tags.tagId=work_tag.tagId WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,$row);
    }
    mysqli_stmt_close($stmt);
    return $list;
}

function getAllPublishedWorks($conn){
    $sql = "SELECT * FROM works WHERE workPub = 1;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,$row);
    }
    mysqli_stmt_close($stmt);
    return $list;
}

function updateWork($conn,$workId,$userId,$name,$part,$series,$author,$age,$genres,$tags,$text){
    $sql = "UPDATE works SET workName = ?, workPart = ?, workSeries = ?,workAuthor = ?,minAge = ?,workText = ? WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfailedupdatework");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"sssssss",$name,$part,$series,$author,$age,$text,$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);


    $sql = "DELETE FROM work_genre WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfaileddeleteworkgenre");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    foreach ($genres as &$value) {
        $sql = "INSERT INTO work_genre (workId,genreId) VALUES ( (SELECT workId FROM works WHERE workName = ?), (SELECT genreId FROM genres WHERE genreName = ?));";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt,$sql)){
            header("location: ../editor.php?error=stmtfailedgenres");
            exit();
        }
        mysqli_stmt_bind_param($stmt,"ss",$name,$value);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }


    $sql = "DELETE FROM work_tag WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfaileddeleteworktag");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    foreach ($tags as &$value) {
        $sql = "INSERT INTO work_tag (workId,tagId) VALUES ( (SELECT workId FROM works WHERE workName = ?), (SELECT tagId FROM tags WHERE tagName = ?));";
        $stmt = mysqli_stmt_init($conn);
        if (!mysqli_stmt_prepare($stmt,$sql)){
            header("location: ../editor.php?error=stmtfailedtags");
            exit();
        }
        mysqli_stmt_bind_param($stmt,"ss",$name,$value);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    }

    header("location: ../mygallery.php");
    exit();
}

function deleteWork($conn,$workId){
    $sql = "DELETE FROM works WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfaileddeletework");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function getPublishedFromWorkId($conn,$workId){
    $sql = "SELECT workPub FROM works WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
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

function getNumberOfLikesFromWorkId($conn,$workId){
    $sql = "SELECT COUNT(workId) as num FROM likes WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtgetNumberOfLikesFromWorkIdfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)){
        mysqli_stmt_close($stmt);
        return $row["num"];
    }else{
        mysqli_stmt_close($stmt);
        $result = false;
        return $result;
    }
}

function getNumberOfCommentsFromWorkId($conn,$workId){
    $sql = "SELECT COUNT(workId) as num FROM comments WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtgetNumberOfLikesFromWorkIdfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($resultData)){
        mysqli_stmt_close($stmt);
        return $row["num"];
    }else{
        mysqli_stmt_close($stmt);
        $result = false;
        return $result;
    }
}

function publishWork($conn,$workId){
    $sql = "UPDATE works SET workPub = 1 WHERE workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfailedpublishwork");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function didUserLikedWork($conn,$userId,$workId){
    $sql = "SELECT * FROM likes WHERE userId = ? AND workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtgetNumberOfLikesFromWorkIdfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ss", $userId,$workId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);
    if (mysqli_fetch_assoc($resultData)){
        mysqli_stmt_close($stmt);
        return true;
    }else{
        mysqli_stmt_close($stmt);
        return false;
    }
}

function likeWork($conn,$userId,$workId){
    $sql = "INSERT INTO likes (userId, workId) VALUES (?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfailedToLike");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ss", $userId,$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function removeLikeWork($conn,$userId,$workId){
    $sql = "DELETE FROM likes WHERE likes.userId = ? AND likes.workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfailedDeleteLike");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"ss", $userId,$workId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function commentOnWork($conn,$userId,$workId,$text){
    $sql = "INSERT INTO comments (userId, workId, commentText) VALUES (?, ?, ?);";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../editor.php?error=stmtfailedToComment");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"sss", $userId,$workId,$text);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

function getAllCommentsOfWork($conn,$workId){
    $sql = "SELECT users.userId,users.userUid,comments.commentText,comments.commentId FROM comments 
JOIN users USING(userId)
WHERE comments.workId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailed");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s", $workId);
    mysqli_stmt_execute($stmt);

    $resultData = mysqli_stmt_get_result($stmt);

    $list = array();
    while($row=mysqli_fetch_assoc($resultData)){
        array_push($list,$row);
    }
    mysqli_stmt_close($stmt);
    return $list;
}

function removeComment($conn,$commentId){
    $sql = "DELETE FROM comments WHERE comments.commentId = ?;";
    $stmt = mysqli_stmt_init($conn);
    if (!mysqli_stmt_prepare($stmt,$sql)){
        header("location: ../index.php?error=stmtfailedDeleteComment");
        exit();
    }
    mysqli_stmt_bind_param($stmt,"s",$commentId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

































