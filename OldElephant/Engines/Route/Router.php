<?php


namespace OldElephant\Engines\Route;


use OldElephant\Engines\Processor\Selectors\Selector;
use OldElephant\Engines\IO\IO;
use OldElephant\Engines\Init\Initializer;
use OLS\Routing\RouteResource;


class Router
{
    use RouteResource;
    /*
     * the address of viewer place in directory
     * if you change directory of viewers then should change
     * this property
     *
     */
    public static $place_of_viewers='OLS\Viewers\\';

    /*
     * this is the rv or route variable.
     * for now it is useless!!
     */
    public $route_variable_value;

    /*
     * an variable to hold current viewer
     */
    public static $current_viewer_instance;
    public static $current_viewer_name;
    public static $returned_details_from_auth_engine;
    public static $current_RV;
    /*
     * it is just an abstract for execution of initialize_router() method
     * statically
     */
    public static function routing($initial_ajax_array=null)
    {
        if(!is_null($initial_ajax_array)){
            if($initial_ajax_array['flag']=='true'){
                $using_ajax_flag=true;
            }else{
                $using_ajax_flag=null;
            }
        }else{
            $using_ajax_flag=null;
        }
        self::$current_viewer_name=self::current_viewer($using_ajax_flag,$initial_ajax_array);
        self::current_viewer_instance();
        self::get_current_variable_value();
        self::execute_class(self::$current_viewer_name);
    }
    /*
     * get current viewer name
     */
    public static function current_viewer($using_ajax_flag=null,$initial_ajax_array=null){
        $router = new self();
        /*
         * if execute when no Selector id exists in route assignment array
         * and else the Selector unit will answer this Router unit to execute what viewer and how!
         */
        $route_array = $router->search_for_similar_route($using_ajax_flag,$initial_ajax_array);
        if(isset($route_array['ajax_flag'])){
            if($route_array['ajax_flag']===true){
                $auth = new Selector();
                $response = $auth->auth_process($route_array);
                if($response['class_name']!==false){
                    self::$returned_details_from_auth_engine=$response['details'];
                    return $response['class_name'];
                }
                return $router->special_route_assignment['^']['viewer'];
            }
        }
        if ($route_array !== false) {
            if ($route_array['auth_id'] == '') {
                return $route_array['viewer'];
            } else {
                /*-----------------------------------------------------------------------
                     * this execute when auth_id!=''
                     * and the Authentication Unit will return the result that should be executed
                     *
                     */

                $auth = new Selector();
                $response = $auth->auth_process($route_array);
                self::$returned_details_from_auth_engine=$response['details'];
                return $response['class_name'];
                /*
                 * ---------------------------------------------------------------------
                 */

            }
        }else{

            return $router->special_route_assignment['^']['viewer'];
        }

    }
    /*
     * get instance
     */
    public static function current_viewer_instance()
    {
        self::$current_viewer_instance=self::get_instance(self::$current_viewer_name);
        return self::$current_viewer_instance;

    }
    public static function get_current_viewer(){
        return self::$current_viewer_name;
    }
    /*
     * get instance of class
     */
    public static function get_instance($class)
    {
        $place_of_viewers=self::$place_of_viewers;
        return eval("return \$instance=new $place_of_viewers$class();");
    }
    /*
     * method for get current route variable_route value
     */
    public static function get_current_variable_value()
    {
        if(self::get_ajax_array()['flag']=='true'){
            return [
                'value'=>false,
                'viewer'=>''
            ];
        }else{
            $router=new self();
            $current_route=$router->current_route()['route'];
            foreach (array_keys($router->special_route_assignment) as $route){
                if(strpos($route,'@_')!==false){
                    $pos=strpos($route,'@_');
                    $temp=str_split($route,$pos)[0];
                    if(strpos($current_route,$temp)===0){
                        $sub=substr($current_route,strlen($temp),strlen($current_route)-strlen($temp));
                        self::$current_RV=$sub;
                        return [
                            'value'=>$sub,
                            'viewer'=>$router->special_route_assignment[$route]['viewer']
                        ];
                    }
                }
            }
            self::$current_RV=false;
            return [
                'value'=>false,
                'viewer'=>''
            ];
        }

    }
    /*
     * this method execute run() method of given class name with
     * predefined $place_of_viewer ,
     * So in otherwise this method execute run() method of an given viewer
     */
    public static function execute_class($class)
    {
        $place_of_viewers=self::$place_of_viewers;
        $inst=eval("return new $place_of_viewers$class();");
        $inst->run();
    }
    /*
     * return current route
     */
    public static function current_route()
    {
        $request_scheme=IO::filter_input($_SERVER,'SERVER_PROTOCOL');
	if($request_scheme=="HTTP/1.1"){$request_scheme='http';}
        $request_uri=IO::filter_input($_SERVER,'REQUEST_URI');
        $server_name=IO::filter_input($_SERVER,'HTTP_HOST');
        $current_url=$request_scheme.'://'.$server_name.$request_uri;
        $init=new Initializer();
        $str=$init->read_need_file()['site_url'];
        $route=str_replace($str,'',$current_url);
        return [
            'current_route'=>$current_url,
            'route'=>$route
        ];
    }
    /*
     * search for any matched at route name with given string and return
     * route that find
     */
    public function search_for_similar_route($using_ajax_flag=null,$initial_ajax_array=null){

        if(!is_null($using_ajax_flag)){
            $current_route = $this->current_route()['route'];
            foreach ($this->ajax_common_route_assignment as $route => $assign_array) {
                if ($current_route == $route) {
                    return [
                        'ajax_flag'=>true,
                        'auth_id'=>$initial_ajax_array['auth_id']
                    ]
                        ;
                }
            }
            return false;
        }else{
            $current_route = $this->current_route()['route'];
            foreach ($this->route_assignment as $route => $assign_array) {
                if ($current_route == $route) {
                    return $assign_array;
                }else{
                    continue;
                }
            }
            foreach (array_keys($this->special_route_assignment) as $route){
                if(strpos($route,'@_')!==false){
                    $pos=strpos($route,'@_');
                    $temp=str_split($route,$pos)[0];
                    if(strpos($current_route,$temp)===0){
                        return $this->special_route_assignment[$route];
                    }
                }
            }
            return false;
        }

    }
    public static function get_site_url()
    {
        $init=new Initializer();
        return $init->read_need_file()['site_url'];
    }

    public static function get_ajax_array()
    {
        if(ajax_auth_id!=NULL){
            return [
                'flag'=>'true',
                'auth_id'=>ajax_auth_id
            ];
        }else{
            return [
                'flag'=>'false',
                'auth_id'=>ajax_auth_id
            ];
        }

    }
}
