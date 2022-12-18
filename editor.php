<?php
include_once 'header.php'
?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

    <section class="editor-form">
        <h2>Editor</h2>
        <form action="includes/editor.inc.php" method="post">
            <input type="text" name="name" placeholder="Name...">
            <input type="number" name="part" placeholder="Part...">
            <input type="text" name="series" placeholder="Series...">
            <input type="text" name="author" placeholder="Series...">
            <input type="text" name="age" placeholder="Minimum age...">
            <?php
             echo "<select data-placeholder='Begin typing a name to filter...' multiple class='chosen-select' name='genres'>
                <option value=''></option>";
                require_once 'includes/dbh.inc.php';
                require_once 'includes/functions.inc.php';
                $genres = getAllGenres($conn);
                foreach ($genres as &$value) {
                    echo "<option>$value</option>";
                }
            echo "</select>";
            echo "<select data-placeholder='Begin typing a name to filter...' multiple class='chosen-select' name='tags'>
                <option value=''></option>";
                $tags = getAllTags($conn);
                foreach ($tags as &$value) {
                    echo "<option>$value</option>";
                }
                ?>
            </select>
            <textarea name="text" placeholder="Text..."></textarea>
            <button type="submit" name="submit">Save work to gallery</button>
        </form>
    </section>
    <script>
        $(".chosen-select").chosen({
            no_results_text: "Oops, nothing found!"
        })
    </script>
<?php
include_once 'footer.php'
?>