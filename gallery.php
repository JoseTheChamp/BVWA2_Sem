<?php
include_once 'header.php'
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
            <th>Likes</th>
            <th>Comments</th>
            <th>Read</th>
        </tr>
        <?php
        require_once "includes/functions.inc.php";
        require_once "includes/dbh.inc.php";
        $data = getAllPublishedWorks($conn);
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
            $numOfLikes = getNumberOfLikesFromWorkId($conn,$value[$key]);
            echo "<td><p>$numOfLikes</p></td>";
            $numOfComments = getNumberOfCommentsFromWorkId($conn,$value[$key]);
            echo "<td><p>$numOfComments</p></td>";
            echo "<td><a href='view.php?id=$value[$key]'>View</a></td>";
        }
        ?>
    </table>
<?php
include_once 'footer.php'
?>