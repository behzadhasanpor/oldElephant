<?php

use OldElephant\Engines\Moderator\Moderator;

// site url
$site_url=Moderator::site_url();

// get title of viewer
$title=Moderator::title();

// get route variable if exists
$route_variable=Moderator::RV();

// get stylesheet initialization text
$links=Moderator::stylesheet_initializing();

// get scripts initialization text
$scripts=Moderator::scripts_initializing();

// current viewer
$current_viewer_name=Moderator::current_Viewer()['name'];

// current loged in use
$current_logged_in_user=Moderator::current_logged_user();

// current viewer details
$current_viewer_details=Moderator::current_Viewer()['details'];

// is any user login
$is_user_login=Moderator::is_any_user_login();

// returned details
$returned_details=Moderator::current_Viewer()['details'];
