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
            echo "<tr><td>$value[0]</td>";
            echo "<td>$value[1]</td>";
            echo "<td>$value[2]</td>";
            echo "<td>$value[3]</td>";

            $genres = getAllGenresFromWork($conn,$value[4]);
            echo "<td>";
            foreach ($genres as &$value2) {
                $value2 = $value2["genreName"];
                echo "$value2, ";
            }
            echo "</td>";

            $tags = getAllTagsFromWork($conn,$value[4]);
            echo "<td>";
            foreach ($tags as &$value1) {
                $value1 = $value1["tagName"];
                echo "$value1, ";
            }
            echo "</td>";
            echo "<td><a href='view.php?id=$value[4]'>View</a></td>";
            echo "<td><a href='editor.php?modify=$value[4]'>Modify</a></td>";
            echo "<td><a onclick='deleteFun()' href='includes/delete.inc.php?id=$value[4]'>Delete</a></td>";
            if ($value[5] == 0){
                echo "<td><a onclick='publishFun()' href='includes/publish.inc.php?id=$value[4]'>Publish</a></td></tr>";
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