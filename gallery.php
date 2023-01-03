<?php
include_once 'header.php';
require_once 'includes/dbh.inc.php';
require_once 'includes/functions.inc.php';
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>
    <h2>Published works:</h2>
    <form action="gallery.php?filtersChanged=true" method="post">

        <!--FILTERING-->
        <label>Filter by: </label>
        <input type="text" name="filterName" placeholder="Work name..."
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["filterName"])) {
                    $value = $_SESSION["filterName"];
                    echo "value='$value'";
                }
            } else if (isset($_POST["filterName"])) {
                $value = $_POST["filterName"];
                $_SESSION["filterName"] = $value;
                echo "value='$value'";
            } else {
                unset($_SESSION["filterName"]);
            }
            ?>
        >
        <input type="text" name="filterPart" placeholder="Part..."
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["filterPart"])) {
                    $value = $_SESSION["filterPart"];
                    echo "value='$value'";
                }
            } else if (isset($_POST["filterPart"])) {
                $value = $_POST["filterPart"];
                $_SESSION["filterPart"] = $value;
                echo "value='$value'";
            } else {
                unset($_SESSION["filterPart"]);
            }
            ?>
        >
        <input type="text" name="filterSeries" placeholder="Series..."
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["filterSeries"])) {
                    $value = $_SESSION["filterSeries"];
                    echo "value='$value'";
                }
            } else if (isset($_POST["filterSeries"])) {
                $value = $_POST["filterSeries"];
                $_SESSION["filterSeries"] = $value;
                echo "value='$value'";
            } else {
                unset($_SESSION["filterSeries"]);
            }
            ?>
        >
        <input type="text" name="filterAuthor" placeholder="Author..."
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["filterAuthor"])) {
                    $value = $_SESSION["filterAuthor"];
                    echo "value='$value'";
                }
            } else if (isset($_POST["filterAuthor"])) {
                $value = $_POST["filterAuthor"];
                $_SESSION["filterAuthor"] = $value;
                echo "value='$value'";
            } else {
                unset($_SESSION["filterAuthor"]);
            }
            ?>
        >
        <select data-placeholder='Genres...' multiple class='chosen-select' name='genres[]'>
            <option value=''></option>
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["selGenres"])) {
                    $genresSelected = $_SESSION["selGenres"];
                }
            } else if (isset($_POST["genres"])) {
                $genresSelected = $_POST["genres"];
                $_SESSION["selGenres"] = $genresSelected;
            } else {
                unset($_SESSION["selGenres"]);
            }
            $genres = getAllGenres($conn);
            foreach ($genres as &$value) {
                if (isset($_POST["genres"])) {
                    $echoed = false;
                    foreach ($genresSelected as &$genSel) {
                        if ($genSel === $value) {
                            echo "<option selected>$value</option>";
                            $echoed = true;
                        }
                    }
                    if (!$echoed) {
                        echo "<option>$value</option>";
                    }
                } else {
                    echo "<option>$value</option>";
                }
            }
            ?></select>
        <select data-placeholder='Tags...' multiple class='chosen-select' name='tags[]'>
            <option value=''></option>
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["selTags"])) {
                    $tagsSelected = $_SESSION["selTags"];
                }
            } else if (isset($_POST["tags"])) {
                $tagsSelected = $_POST["tags"];
                $_SESSION["selTags"] = $tagsSelected;
            } else {
                unset($_SESSION["selTags"]);
            }
            $tags = getAllTags($conn);
            foreach ($tags as &$value) {
                if ($_POST["tags"]) {
                    $echoed = false;
                    foreach ($tagsSelected as &$tagSel) {
                        if ($tagSel === $value) {
                            echo "<option selected>$value</option>";
                            $echoed = true;
                        }
                    }
                    if (!$echoed) {
                        echo "<option>$value</option>";
                    }
                } else {
                    echo "<option>$value</option>";
                }
            }
            ?></select><br>
        <label for="favorites">Show only favorites: </label>
        <input type="checkbox" id="favorites" <?php
        if (!isset($_GET["filtersChanged"])) {
            if (isset($_SESSION["favorites"])) {
                $value = $_SESSION["favorites"];
                if ($value) {
                    echo "checked='checked'";
                }
            }
        } else if (isset($_POST["favorites"])) {
            $value = $_POST["favorites"];
            $_SESSION["favorites"] = $value;
            if ($value) {
                echo "checked='checked'";
            }
        } else {
            unset($_SESSION["favorites"]);
        }
        ?> name="favorites">
        <label style="margin-left: 10px" for="dates">Order by: </label>
        <input type="radio" id="dates" checked="checked" name="orderBy" value="dates"
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (!isset($_SESSION["orderBy"]) || $_SESSION["orderBy"] === "dates") {
                    echo "checked='checked'";
                }
            } else if (!isset($_POST["orderBy"]) || $_POST["orderBy"] === "dates") {
                echo "checked='checked'";
                $_SESSION["orderBy"] = "dates";
            }
            ?>>
        <label for="dates">Dates</label>
        <input type="radio" id="likes" name="orderBy" value="likes"
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["orderBy"]) && $_SESSION["orderBy"] === "likes") {
                    echo "checked='checked'";
                }
            } else if (isset($_POST["orderBy"]) && $_POST["orderBy"] === "likes") {
                echo "checked='checked'";
                $_SESSION["orderBy"] = "likes";
            }
            ?>>
        <label for="likes">Likes</label>
        <input type="radio" id="comments" name="orderBy" value="comments"
            <?php
            if (!isset($_GET["filtersChanged"])) {
                if (isset($_SESSION["orderBy"]) && $_SESSION["orderBy"] === "comments") {
                    echo "checked='checked'";
                }
            } else if (isset($_POST["orderBy"]) && $_POST["orderBy"] === "comments") {
                echo "checked='checked'";
                $_SESSION["orderBy"] = "comments";
            }
            ?>>
        <label for="comments">Comments</label>
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
            <?php //only while logged in
            if (!empty($_SESSION["userId"])) {
                echo "<th>Favorite</th>";
            }
            ?>
            <th>Likes</th>
            <th>Comments</th>
            <th>DateTime</th>
            <th>Read</th>
        </tr>
        <?php
        require_once "includes/functions.inc.php";
        require_once "includes/dbh.inc.php";

        // BUILDING THE SQL STATEMENT
        $data = "";
        $key = "";
        if (!isset($_SESSION["orderBy"]) || $_SESSION["orderBy"] === "dates") {
            $sql = "SELECT * FROM works WHERE workPub = 1";
        } else if ($_SESSION["orderBy"] === "likes") {
            $sql = "SELECT *,count(likes.userId) as likes FROM works left join likes on works.workId = likes.workId WHERE workPub = 1 ";
        } else {
            $sql = "SELECT *,count(comments.userId) as comments FROM works left join comments on works.workId = comments.workId WHERE workPub = 1 ";
        }
        if (isset($_SESSION["filterName"]) && $_SESSION["filterName"] !== "") {
            $key = "filterName";
            $sql = $sql . " AND workName = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["filterPart"]) && $_SESSION["filterPart"] !== "") {
            $key = "filterPart";
            $sql = $sql . " AND workPart = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["filterSeries"]) && $_SESSION["filterSeries"] !== "") {
            $key = "filterSeries";
            $sql = $sql . " AND workSeries = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["filterAuthor"]) && $_SESSION["filterAuthor"] !== "") {
            $key = "filterAuthor";
            $sql = $sql . " AND workAuthor = '$_SESSION[$key]'";
        }
        if (isset($_SESSION["selGenres"]) && $_SESSION["selGenres"] !== "") {
            $selGenres = $_SESSION["selGenres"];
            foreach ($selGenres as &$genval) {
                $sql = $sql . " AND works.workId IN (SELECT works.workId FROM works JOIN work_genre USING(workId) JOIN genres USING(genreId) WHERE genreName = '$genval')";
            }
        }
        if (isset($_SESSION["selTags"]) && $_SESSION["selTags"] !== "") {
            $selTags = $_SESSION["selTags"];
            foreach ($selTags as &$tagval) {
                $sql = $sql . " AND works.workId IN (SELECT works.workId FROM works JOIN work_tag USING(workId) JOIN tags USING(tagId) WHERE tagName = '$tagval')";
            }
        }
        if (isset($_SESSION["userId"]) && isset($_SESSION["favorites"]) && $_SESSION["favorites"] !== "") {
            if ($_SESSION["favorites"]) {
                $keyuser = "userId";
                $sql = $sql . " AND works.workId IN (SELECT works.workId FROM works JOIN favorites USING(workId) WHERE favorites.userId = $_SESSION[$keyuser])";
            }
        }
        if (!isset($_SESSION["orderBy"]) || $_SESSION["orderBy"] === "dates") {
            $sql .= " order by works.datePub DESC;";
        } else if ($_SESSION["orderBy"] === "likes") {
            $sql .= " group by works.workId order by likes DESC;";
        } else {
            $sql .= " group by works.workId order by comments DESC;";
        }
        //echo $sql;
        $data = getAllPublishedWorks($conn, $sql);

        //BUILDING THE DATA
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
            $genres = getAllGenresFromWork($conn, $value[$key]);
            echo "<td>";
            foreach ($genres as &$value2) {
                $value2 = $value2["genreName"];
                echo "$value2, ";
            }
            echo "</td>";

            $tags = getAllTagsFromWork($conn, $value[$key]);
            echo "<td>";
            foreach ($tags as &$value1) {
                $value1 = $value1["tagName"];
                echo "$value1, ";
            }
            echo "</td>";

            if (!empty($_SESSION["userId"])) {
                $res = getFavorite($conn, $_SESSION["userId"], $value[$key]);
                if (!$res) {
                    echo "<td><form action='includes/switchFavoriteGallery.inc.php?action=add&workId=$value[$key]' method='post'><button style='height: 100%;width: 100%;border: transparent;background: transparent' type='submit'>&nbsp;</button></form></td>";
                } else {
                    echo "<td><form action='includes/switchFavoriteGallery.inc.php?action=remove&workId=$value[$key]' method='post'><button style='height: 100%;width: 100%;border: transparent;background: transparent' class='invisBtn' type='submit'><b>&#9733;</b></button></form></td>";
                }
            }

            $numOfLikes = getNumberOfLikesFromWorkId($conn, $value[$key]);
            echo "<td>$numOfLikes</td>";

            $numOfComments = getNumberOfCommentsFromWorkId($conn, $value[$key]);
            echo "<td>$numOfComments</td>";

            $dateOfPublication = $value["datePub"];
            echo "<td>$dateOfPublication</td>";

            echo "<td><a href='view.php?id=$value[$key]'>View</a></td></tr>";
        }
        ?>
    </table>
    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
        $(document).ready(function () {
            $("tr:even").css("background-color", "#e3e3e3");
        });
    </script>
<?php
include_once 'footer.php'
?>