<?php
include_once 'header.php'
?>
<?php
    if ($_SESSION["userId"] === null){
        header("location: ../index.php");
    }
    require_once "includes/functions.inc.php";
    require_once "includes/dbh.inc.php";
?>
<h2>Your works:</h2>
<p>Filtering to be here.</p>
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
        <?php
        $data = getAllWorksFromUser($conn,$_SESSION["userId"]);
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
</script>
<?php
include_once 'footer.php'
?>