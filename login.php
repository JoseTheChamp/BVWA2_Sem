<?php
include_once 'header.php'
?>
    <section class="login-form">
        <h2>Sign Up</h2>
        <form action="includes/login.inc.php" method="post">
            <input type="text" name="name" placeholder="UserName/Email...">
            <input type="password" name="pwd" placeholder="Password...">
            <button type="submit" name="submit">Log In</button>
        </form>
    </section>
<?php
include_once 'footer.php'
?>