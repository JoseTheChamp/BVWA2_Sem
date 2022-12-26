<?php
include_once 'header.php'
?>
<?php
if (!isset($_SESSION["userId"])){
    header("location: ../index.php");
    exit();
}
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
if(isset($_GET["modify"])){
    $_SESSION["modify"] = $_GET["modify"];
    $work = getWorkFromWorkId($conn,$_GET["modify"]);
    if($work["ownerId"] !== $_SESSION["userId"]){
        header("location: ../index.php?error=CannotModifyForeignWork");
        exit();
    }
}else{
    unset($_SESSION["modify"]);
}
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

    <section class="editor-form">
        <h2>Editor</h2>
        <form action="includes/addTag.inc.php" method="post">
            <h3>New Tag</h3>
            <input type="text" name="newTagName" placeholder="New Tag Name...">
            <button type="submit" name="submit">Add New Tag</button>
        </form>
        <form action="includes/editor.inc.php" method="post">
            <h3>New Work</h3>
            <input type="text" name="name" placeholder="Name..."
                <?php
                if (isset($work)){
                    $value = $work["workName"];
                    echo "value='$value'";
                }
                ?>>
            <input type="number" name="part" placeholder="Part..."
                <?php
                if (isset($work)){
                    $value = $work["workPart"];
                    echo "value='$value'";
                }
                ?>>
            <input type="text" name="series" placeholder="Series..."
                <?php
                if (isset($work)){
                    $value = $work["workSeries"];
                    echo "value='$value'";
                }
                ?>>
            <input type="text" name="author" placeholder="Author..."
                <?php
                if (isset($work)){
                    $value = $work["workAuthor"];
                    echo "value='$value'";
                }
                ?>>
            <input type="number" name="age" placeholder="Minimum age..."
                <?php
                if (isset($work)){
                    $value = $work["minAge"];
                    echo "value='$value'";
                }
                ?>>
            <?php
             echo "<select data-placeholder='Begin typing a name to filter...' multiple class='chosen-select' name='genres[]'>
                <option value=''></option>";
             if (isset($work)){
                 $genresSelected = getAllGenresFromWork($conn,$work["workId"]);
             }
                $genres = getAllGenres($conn);
                foreach ($genres as &$value) {
                    if (isset($work)){
                        $echoed = false;
                        foreach ($genresSelected as &$genSel){
                            if ($genSel["genreName"] == $value){
                                echo "<option selected>$value</option>";
                                $echoed = true;
                            }
                        }
                        if (!$echoed){
                            echo "<option>$value</option>";
                        }
                    }else{
                        echo "<option>$value</option>";
                    }
                }
            echo "</select>";
            echo "<select data-placeholder='Begin typing a name to filter...' multiple class='chosen-select' name='tags[]'>
                <option value=''></option>";
            if (isset($work)){
                $tagsSelected = getAlltagsFromWork($conn,$work["workId"]);
            }
            $tags = getAllTags($conn);
            foreach ($tags as &$value) {
                if (isset($work)){
                    $echoed = false;
                    foreach ($tagsSelected as &$tagSel){
                        if ($tagSel["tagName"] == $value){
                            echo "<option selected>$value</option>";
                            $echoed = true;
                        }
                    }
                    if (!$echoed){
                        echo "<option>$value</option>";
                    }
                }else{
                    echo "<option>$value</option>";
                }
            }
                ?>
            </select>
            <textarea name="text" placeholder="Text..."><?php
                if (isset($work)){
                    $value = $work["workText"];
                    echo "$value";
                }
                ?></textarea>
            <button type="submit" name="submit">Save work to gallery</button>
        </form>
    </section>
    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
    </script>
<?php
if (isset($_GET["error"])){
    if ($_GET["error"] == "emptyinput"){
        echo "<p>Fill in all fields!</p>";
    }else if($_GET["error"] == "usernametaken"){
        echo "<p>This username is already taken!</p>";
    }
    else if($_GET["error"] == "usernotloggedin"){
        echo "<p>You are not logged in!</p>";
    }
    else if($_GET["error"] == "stmtfailedcreatework"){
        echo "<p>Failed to create work!</p>";
    }
    else if($_GET["error"] == "stmtfailedgenres"){
        echo "<p>Failed to create genres!</p>";
    }
    else if($_GET["error"] == "stmtfailedtags"){
        echo "<p>Failed to create tags!</p>";
    }
}
?>
<?php
include_once 'footer.php'
?>