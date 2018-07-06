<?php
namespace OLS;

use OldElephant\Engines\IO\IO;
use OldElephant\Engines\Init\Initializer;
use OldElephant\Engines\Route\Router;


Configure::launch();








class Configure{


    public $index_dir='';
    public function __construct()
    {
        $this->index_dir=__DIR__.'../';
    }
    public static function launch()
    {
        error_reporting("~ E_ALL");
        self::include_library();
        self::define_constants();
        $act=Initializer::action_include();
        if($act['status']=='failure'){
            die($act['message']);
        };
        if(isset($_POST['using_ajax_single_page']) && isset($_POST['auth_id'])){
            $ajax_flag=IO::filter_input($_POST,'using_ajax_single_page');
            $auth_id=IO::filter_input($_POST,'auth_id');
            $initial_ajax_array=[
                'flag'=>$ajax_flag,
                'auth_id'=>$auth_id
            ];
            define('ajax_auth_id',$initial_ajax_array['auth_id']);
            Configure::init_router($initial_ajax_array);
        }else{
            define('ajax_auth_id',NULL);
            Configure::init_router();
        }
    }

    public static function init_router($initial_ajax_array=null)
    {
        Router::routing($initial_ajax_array);
    }

    public static function include_library()
    {
        spl_autoload_register(function ($class_name){
            include_once(implode(DIRECTORY_SEPARATOR,explode('\\', $class_name)).'.php');
        });
    }

    public static function define_constants()
    {
        define('site_dir',__DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR);
    }


}




