<?php
include_once 'header.php'
?>
<section class="signup-form">
<h2>Sign Up</h2>
    <form action="includes/signup.inc.php" method="post">
        <input type="text" name="uid" placeholder="UserName...">
        <input type="text" name="email" placeholder="Email...">
        <input type="password" name="pwd" placeholder="Password...">
        <input type="password" name="pwdrepeat" placeholder="Password...">
        <button type="submit" name="submit">Sign Up</button>
    </form>
</section>
<?php
if (isset($_GET["error"])){
    if ($_GET["error"] == "emptyinput"){
        echo "<p>Fill in all fields!</p>";
    }else if($_GET["error"] == "usernametaken"){
        echo "<p>This username is already taken!</p>";
    }
    else if($_GET["error"] == "invaliduid"){
        echo "<p>This username is not allowed!</p>";
    }
    else if($_GET["error"] == "invalidemail"){
        echo "<p>This is not valid email!</p>";
    }
    else if($_GET["error"] == "passwordsdontmatch"){
        echo "<p>Passwords do not match!</p>";
    }
}
?>
<?php
include_once 'footer.php'
?>