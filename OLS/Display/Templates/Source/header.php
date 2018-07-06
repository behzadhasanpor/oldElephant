<?php
include('VariableReference.php');
?>
<html>
    <head>
        <meta charset="utf-8">
        <title>
        <?php
            echo $title;
        ?>
        </title>
        <?php
            echo $links;
        ?>
        <link rel="icon" type="image/x-icon" class="js-site-favicon" href="<?php echo $site_url;?>/favicon.ico">
    </head>
    <body>
        <nav>
            <ul>
                <li>
                    <a href="<?php echo $site_url;?>home">Home</a>
                </li>
                <li>
                    <?php if(!$is_user_login) :?>
                        <a href="<?php echo $site_url;?>login">Login</a>
                    <?php else : ?>
                        <a href="<?php echo $site_url;?>dashboard">dashboard</a>
                    <?php endif; ?>
                </li>
                <li>
                    <a href="<?php echo $site_url;?>category/type-12">try Api</a>
                </li>
            </ul>
            <img src="<?php echo $site_url;?>Media/Images/logo.png">
            <h3>OLD ELEPHANT</h3>
        </nav>




