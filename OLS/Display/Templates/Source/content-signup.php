<?php include_once('VariableReference.php');?>
<div id="content">
    <h1>SIGN UP</h1>
    <form action="<?php echo $site_url;?>signup/add" method="post">
        <input type="text" name="username" placeholder="User Name" ><br>
        <input type="password" name="password" value="Password"><br>
        <input type="email" name="email" placeholder="Email" ><br>
        <input type="text" name="first_name" value="Name"><br>
        <input type="text" name="last_name" placeholder="Last Name" ><br>
        <input type="submit" value="sign up">
    </form>
</div>