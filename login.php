<?php
include_once 'header.php'
?>
    <section class="login-form">
        <h2>Log in</h2>
        <form action="includes/login.inc.php" method="post">
            <input type="text" name="name" placeholder="UserName/Email...">
            <input type="password" name="pwd" placeholder="Password...">
            <button type="submit" name="submit">Log In</button>
        </form>
    </section>
<?php
if (isset($_GET["error"])) {
    if ($_GET["error"] == "emptyinput") {
        echo "<p>Fill in all fields!</p>";
    } else if ($_GET["error"] == "wronglogin") {
        echo "<p>Username or password is incorrect!</p>";
    }
}

include_once 'footer.php'
?>