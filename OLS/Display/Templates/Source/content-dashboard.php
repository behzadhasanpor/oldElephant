<?php include_once('VariableReference.php');?>
<div id="content">
    <h1>YOUR DASHBOARD</h1>
    hi <?php echo $current_logged_in_user['first_name'];?><br>
    Are you want <a href="<?php echo $site_url;?>signout">Sign Out</a>
</div>