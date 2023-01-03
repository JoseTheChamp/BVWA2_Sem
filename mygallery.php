<?php
include_once 'header.php';

    if ($_SESSION["userId"] === null){
        header("location: ../index.php");
    }
    require_once "includes/functions.inc.php";
    require_once "includes/dbh.inc.php";
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
<h2>Your works:</h2>
    <form action="mygallery.php" method="post">


        <!--FILTERING-->
        <label>Filter by: </label>
        <input type="text" name="filterName" placeholder="Work name..."
            <?php
            if (isset($_POST["filterName"])){
                $value = $_POST["filterName"];
                $_SESSION["filterName"] = $value;
                echo "value='$value'";
            }else{
                unset($_SESSION["filterName"]);
            }
            ?>
        >
        <input type="text" name="filterPart" placeholder="Part..."
            <?php
            if (isset($_POST["filterPart"])){
                $value = $_POST["filterPart"];
                $_SESSION["filterPart"] = $value;
                echo "value='$value'";
            }else{
                unset($_SESSION["filterPart"]);
            }
            ?>
        >
        <input type="text" name="filterSeries" placeholder="Series..."
            <?php
            if (isset($_POST["filterSeries"])){
                $value = $_POST["filterSeries"];
                $_SESSION["filterSeries"] = $value;
                echo "value='$value'";
            }else{
                unset($_SESSION["filterSeries"]);
            }
            ?>
        >
        <input type="text" name="filterAuthor" placeholder="Author..."
            <?php
            if (isset($_POST["filterAuthor"])){
                $value = $_POST["filterAuthor"];
                $_SESSION["filterAuthor"] = $value;
                echo "value='$value'";
            }else{
                unset($_SESSION["filterAuthor"]);
            }
            ?>
        >
        <select data-placeholder='Genres...' multiple class='chosen-select' name='genres[]'>
            <option value=''></option>
            <?php
            if (isset($_POST["genres"])){
                $genresSelected = $_POST["genres"];
                $_SESSION["selGenres"] = $genresSelected;
            }else{
                unset($_SESSION["selGenres"]);
            }
            $genres = getAllGenres($conn);
            foreach ($genres as &$value) {
                if (isset($_POST["genres"])){
                    $echoed = false;
                    foreach ($genresSelected as &$genSel){
                        if ($genSel === $value){
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
            ?></select>
        <select data-placeholder='Tags...' multiple class='chosen-select' name='tags[]'>
            <option value=''></option>
            <?php
            if (isset($_POST["tags"])){
                $tagsSelected = $_POST["tags"];
                $_SESSION["selTags"] = $tagsSelected;
            }else{
                unset($_SESSION["selTags"]);
            }
            $tags = getAllTags($conn);
            foreach ($tags as &$value) {
                if ($_POST["tags"]){
                    $echoed = false;
                    foreach ($tagsSelected as &$tagSel){
                        if ($tagSel === $value){
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
            ?></select>
        <input type="submit" value="Submit">
    </form>

    <!--BUILDING THE TABLE-->
<table>
    <tr>
        <th>Name</th>
        <th>Part</th>
        <th>Series</th>
        <th>Author</th>
        <th>Genres</th>
        <th>Tags</th>
        <th>Read</th>
        <th>Modify</th>
        <th>Delete</th>
        <th>Publish</th>
    </tr>


        <?php // CREATING THE SQL STATEMENT
        $key = "userId";
        $sql = "SELECT * FROM works WHERE ownerId = $_SESSION[$key]";

        if (isset($_SESSION["filterName"]) && $_SESSION["filterName"] !== ""){
            $key = "filterName";
            $sql = $sql . " AND workName = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["filterPart"]) && $_SESSION["filterPart"] !== ""){
            $key = "filterPart";
            $sql = $sql . " AND workPart = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["filterSeries"]) && $_SESSION["filterSeries"] !== ""){
            $key = "filterSeries";
            $sql = $sql . " AND workSeries = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["filterAuthor"]) && $_SESSION["filterAuthor"] !== ""){
            $key = "filterAuthor";
            $sql = $sql . " AND workAuthor = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["selGenres"]) && $_SESSION["selGenres"] !== ""){
            $selGenres = $_SESSION["selGenres"];
            foreach ($selGenres as &$genval){
                $sql = $sql . " AND works.workId IN (SELECT works.workId FROM works JOIN work_genre USING(workId) JOIN genres USING(genreId) WHERE genreName = '$genval')";
            }
        }
        if (isset($_SESSION["selTags"]) && $_SESSION["selTags"] !== ""){
            $selTags = $_SESSION["selTags"];
            foreach ($selTags as &$tagval){
                $sql = $sql . " AND works.workId IN (SELECT works.workId FROM works JOIN work_tag USING(workId) JOIN tags USING(tagId) WHERE tagName = '$tagval')";
            }
        }
        $sql = $sql . ";";
        //echo $sql;
        $data = getAllWorksFromUser($conn,$sql);

        //BUILDING DATA
        foreach ($data as &$value) {
            $key = "workName";
            echo "<tr><td>$value[$key]</td>";
            $key = "workPart";
            echo "<td>$value[$key]</td>";
            $key = "workSeries";
            echo "<td>$value[$key]</td>";
            $key = "workAuthor";
            echo "<td>$value[$key]</td>";
            $key = "workId";

            $genres = getAllGenresFromWork($conn,$value[$key]);
            echo "<td>";
            foreach ($genres as &$value2) {
                $value2 = $value2["genreName"];
                echo "$value2, ";
            }
            echo "</td>";

            $tags = getAllTagsFromWork($conn,$value[$key]);
            echo "<td>";
            foreach ($tags as &$value1) {
                $value1 = $value1["tagName"];
                echo "$value1, ";
            }
            echo "</td>";
            echo "<td><a href='view.php?id=$value[$key]'>View</a></td>";
            echo "<td><a href='editor.php?modify=$value[$key]'>Modify</a></td>";
            echo "<td><a onclick='deleteFun()' href='includes/delete.inc.php?id=$value[$key]'>Delete</a></td>";
            $innerKey = "workPub";
            if ($value[$innerKey] == 0){
                echo "<td><a onclick='publishFun()' href='includes/publish.inc.php?id=$value[$key]'>Publish</a></td></tr>";
            }else{
                echo "<td>Published</td></tr>";
            }
        }
        ?>
</table>
<script type="text/javascript">
    function deleteFun() {
        if(!confirm("Do you truly want to delete the item?")){

        }
    }
    function publishFun() {
        if(!confirm("Do you truly want to publish the item?")){

        }
    }
    $(".chosen-select").chosen({
        no_results_text: "Oops, nothing found!"
    })
    $(document).ready(function()
    {
        $("tr:even").css("background-color", "#e3e3e3");
    });
</script>
<?php
include_once 'footer.php'
?>