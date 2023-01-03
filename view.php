<?php
include_once 'header.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
if(isset($_GET["id"])){
    $id = $_GET["id"];
    $work = getWorkFromWorkId($conn,$id);
    if($work !== false){
        if ($work["workPub"] !== 0){
            goto page;
        }else if (isset($_SESSION["userId"])){
            if($work["ownerId"] === $_SESSION["userId"]){
                goto page;
            }else{
                header("location: ../index.php?error=CannotViewForeignUnpublishedWork");
                exit();
            }
        }else{
            header("location: ../index.php?error=WorkWithThisIdIsNotPublished");
            exit();
        }
    }else{
        header("location: ../index.php?error=WorkDoesNotExist");
        exit();
    }
}else{
    header("location: ../index.php?error=NoIdProvided");
    exit();
}
header("location: ../index.php?error=UnexpectedError");
exit();
page:
$workIndexed = array_values($work);
echo "<h1>Name: $workIndexed[2]</h1>";
echo "<p><b>Part:</b> $workIndexed[3]</p>";
echo "<p><b>Series:</b> $workIndexed[4]</p>";
echo "<p><b>Author:</b> $workIndexed[5]</p>";
echo "<p><b>Minimum age requirement:</b> $workIndexed[6]</p>";
$genres = getAllGenresFromWork($conn,$work["workId"]);
echo "<p><b>Genres:</b>  ";
foreach ($genres as &$value){
    $key = "genreName";
    echo "$value[$key], ";
}
echo "</p>";
$tags = getAllTagsFromWork($conn,$work["workId"]);
echo "<p><b>Tags:</b>  ";
foreach ($tags as &$value){
    $key = "tagName";
    echo "$value[$key], ";
}
echo "</p>";
echo "<p><b>Text:</b> </p>";
echo "<p style='white-space: pre-wrap;font-size: smaller'>$workIndexed[7]</p><br>";

if ($work["workPub"] !== 0){
    $numLikes = getNumberOfLikesFromWorkId($conn,$work["workId"]);
    echo "<p><b>Likes:</b>  $numLikes   ";
    if(isset($_SESSION["userId"])){
        if (!didUserLikedWork($conn,$_SESSION["userId"],$work["workId"])){
            $key = "workId";
            echo "<a href='includes/like.inc.php?id=$work[$key]'>Like</a>";
        }else{
            $key = "workId";
            echo "<a href='includes/like.inc.php?id=$work[$key]&remove=true'>Remove like</a>";
        }
    }
    echo "</p>";

    //Favorite
    if(isset($_SESSION["userId"])){
        if (getFavorite($conn,$_SESSION["userId"],$work["workId"])){
            $key = "workId";
            echo "<a href='includes/switchFavoriteView.inc.php?action=remove&workId=$work[$key]'>Remove favorite</a>";
        }else{
            $key = "workId";
            echo "<a href='includes/switchFavoriteView.inc.php?action=add&workId=$work[$key]'>Add favorite</a>";
        }
    }


    echo "<h3>Comments:</h3>";
    $comments = getAllCommentsOfWork($conn,$work["workId"]);
    echo "<p>";
    foreach ($comments as &$value){
        $key1 = "userUid";
        $key2 = "commentText";
        $key3 = "commentId";
        echo "$value[$key1]: $value[$key2]<br>";
        if (isset($_SESSION["userId"]) && $value["userId"] === $_SESSION["userId"]){
            $key = "workId";
            echo "
            <form action='includes/comment.inc.php?id=$work[$key]&remove=$value[$key3]' method='post'>
                <button type='submit' actio name='submit'>Remove Comment</button>
            </form>
            ";
        }
    }
    echo "</p>";

    if(isset($_SESSION["userId"])){
        $key = "workId";
        echo "
    <section class='comment-form'>
        <h3>Make a comment!</h3>
        <form action='includes/comment.inc.php?id=$work[$key]' method='post'>
            <input type='text' name='text' placeholder='Your comment...'>
            <button type='submit' name='submit'>Comment</button>
        </form>
    </section>
    ";
    }else{
        echo "<p>If you want to comment and like, you need to be logged in.</p>";
    }
}


include_once 'footer.php';