<?php include_once('VariableReference.php');?>
<div id="content">
    <h1>login</h1>
    <form action="<?php echo $site_url;?>login" method="post">
        <input type="text" name="username" placeholder="User Name" ><br>
        <input type="password" name="password" value="Password"><br>
        <input type="submit" value="Login">
    </form>
    Do not have an account?<a href="<?php echo $site_url;?>signup"> go to home</a>
</div>