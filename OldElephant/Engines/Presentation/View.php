<?php


namespace OldElephant\Engines\Presentation;
use OLS\Configure;
use OldElephant\Engines\Init\Initializer;




class View
{

    public $title='';
    public $pieces=[];
    protected $content_files=[
    ];
    public $route_variable_value;

    /*
     *  this is a flag used in run() method in view class
     *  what is that for?
     *  if we want to redirect in a template(i propose do redirect at end of footer
     *  and after </html> ) then we should turn this flag on and then we should
     *  add below if at end of footer after </html> (preferred) with our personal condition
     *  and adjust the redirect url (same url we define in Router----route_assignment)
     *  then the current page render will be ignored and the new address will executed
         if(condition){
            return [
                'redirect_flag'=>true,
                'redirect_url'=>'alert'
            ];
            }else{
                return [
                    'redirect_flag'=>false
                ];
            }
     */
    public $redirect_using_flag=false;



    public function __construct()
    {
        $this->pieces=$this->prepare_pieces($this->content_files);
    }
    /*
     * view initialization and run
     */
    public function run()
    {

        if($this->redirect_using_flag){
            ob_start();
            $returned=$this->execute_view();

            $page_content=ob_get_contents();
            ob_end_clean();
            if(!$returned['redirect_flag']){
                echo $page_content;
            }else{
                // notice that if we have redirect clause (with redirect_flag =true) in our templates then else executed
                $site_url=self::get_site_url();
                $location = $site_url.$returned['redirect_url'];
                header("Location:$location" );
            }
        }else{
            $this->execute_view();
        }

    }

    /*
     * execute predefined template parts
     */
    public function execute_view()
    {
        foreach ($this->pieces as $key => $path){
            if($key==end(array_keys($this->pieces))){
                return (include_once($path));
            }
            include_once($path);
        }
    }
    /*
     * add directory url to pieces and prepare them for beh include at execute view
     */
    public function prepare_pieces($input)
    {
        $result=[];
        foreach ($input as $key=> $item) {
            $result[$key]=site_dir.'OLS'.DIRECTORY_SEPARATOR.'Display'.DIRECTORY_SEPARATOR.'Templates'.DIRECTORY_SEPARATOR.'Source'.DIRECTORY_SEPARATOR.$input[$key].'.php';
        }
        return $result;

    }
    /*
     * get site url from .needed
     */
    public static function get_site_url()
    {
        $init=new Initializer();
        return $init->read_need_file()['site_url'];
    }

}