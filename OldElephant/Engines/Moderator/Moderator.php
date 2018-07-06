<?php


namespace OldElephant\Engines\Moderator;

use OldElephant\Engines\Init\Initializer;
use OLS\Processors\IOPersons\Users;
use OldElephant\Engines\Route\Router;
use OLS\Configure;





class Moderator
{
    /*
     * getting site url
     */
    public static function site_url()
    {
        $init=new Initializer();
        return $init->read_need_file()['site_url'];
    }
    /*
     * this static method return an instance of current running viewer
     * and the variable value of route if it has if not it return null
     * and you can use it every where you want
     *
     */
    public static function current_Viewer()
    {
        return [
            'name'=>Router::$current_viewer_name,
            'instance'=>Router::$current_viewer_instance,
            'variable_route'=>Router::$current_RV,
            'details'=>Router::$returned_details_from_auth_engine
        ];
    }
    /*
     * get current viewer title
     *
     */
    public static function title()
    {
        return self::current_Viewer()['instance']->title;
    }
    /*
     * get current viewer variable_route if exists
     */
    public static function RV(){
        return self::current_Viewer()['variable_route'];
    }
    /*
     * initialize and echoing javascript scripts(.js files)
     * this static method don't need none initializing
     * just create a .js file in every where inner Appearance directory
     * (also every sub_directory of that is supported)
     * and run this method once in a page
     */
    public static function scripts_initializing()
    {
        $config=new Configure();
        $inst=new Initializer();
        $paths=[];
        $inst->get_all_files(site_dir.DIRECTORY_SEPARATOR.'Appearance','js',$paths);

        $return_string="";
        foreach ($paths as $path){
            $path=str_replace(site_dir.DIRECTORY_SEPARATOR,$inst->read_need_file()['site_url'],$path);
            $return_string.="<script class='page-scripts' type='text/javascript' src='$path' ></script>";
        }
        return $return_string;
    }
    /*
     * this is like above method but used for stylesheets(.css files)
     */
    public static function stylesheet_initializing()
    {
        $config=new Configure();
        $inst=new Initializer();
        $paths=[];
        $inst->get_all_files(site_dir.DIRECTORY_SEPARATOR.'Appearance','css',$paths);

        $return_string="";
        foreach ($paths as $path){
            $path=str_replace(site_dir.DIRECTORY_SEPARATOR,$inst->read_need_file()['site_url'],$path);
            $return_string.="<link type='text/css' rel='stylesheet' href='$path' >";
        }
        return $return_string;
    }
    /*
     * method for give current user that is logedin
     */
    public static function current_logged_user()
    {
        $user=new Users();
        return $user->get_current_person_full_data();
    }
    public static function is_any_user_login(){
        $user=new Users();
        return $user->current_logged_in_person();
    }

    }