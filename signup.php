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
include_once 'footer.php'
?>