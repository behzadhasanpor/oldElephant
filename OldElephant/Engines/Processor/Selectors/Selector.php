<?php


namespace OldElephant\Engines\Processor\Selectors;

use OldElephant\Engines\IO\IO;
use OLS\Processors\Selectors\SelectorResources\SelectionResource;

/**
 * ------------------------------------------------------------------------------------------------
 * Authentication class
 *
 *
 *
 *
 * -------------------------------------------------------------------------------------------------
 */

class Selector
{
    use SelectionResource;

    public static $place_of_auth_classes='OLS\Processors\Selectors\\';

    public function auth_process($route_details){
        $class_name=$this->auth_id_class_assignment[$route_details['auth_id']];
        if(!isset($class_name)){
            $class_name='NotFoundAuthClass';
        }
        $auth=$this->get_instance($class_name);
        return $auth->setting($route_details);
    }

    public function get_instance($class_name)
    {
        $place_of_auth_classes=self::$place_of_auth_classes;
       return eval("return \$inst = new $place_of_auth_classes$class_name();");
    }
    /*
     * an method for log a person in
     */
    public function person_logging_in($person_table,$login_array,$dashboard_page,$login_page,$out_details)
    {
        if($person_table->current_logged_in_person()){
            return [
                'class_name'=>$dashboard_page,
                'details'=>$out_details
            ];
        }
        $result=[];
        $flag=true;
        foreach($login_array as $input){
            if(isset($_POST[$input])){
                if(!empty(IO::filter_input($_POST,$input))){
                    if($input=='password'){
                        $result[$input]=sha1(IO::filter_input($_POST,$input));
                    }
                    $result[$input]=IO::filter_input($_POST,$input);
                }else{
                    $flag=false;
                    break;
                }
            }else{
                $flag=false;
                break;
            }
        }
        $res=false;
        if($flag==true){
            $res=$person_table->login_person($result);
        }
        return [
            'class_name'=>$res['flag']===true?$dashboard_page:$login_page,
            'details'=>$out_details
        ];
        
    }
    /*
     * a method for sign a person up
     */
    public function person_sign_up($person_table,$arr_of_field,$dashboard_page,$sign_page,$out_details)
    {
        if($person_table->current_logged_in_person()){
            return [
                'class_name'=>$dashboard_page,
                'details'=>$out_details
            ];
        }
        $result=[];
        $flag=true;
        foreach($arr_of_field as $input){
            if(isset($_POST[$input])){
                if(!empty(IO::filter_input($_POST,$input))){
                    if($input=='password'){
                        $result[$input]=sha1(IO::filter_input($_POST,$input));
                        continue;
                    }
                    $result[$input]=IO::filter_input($_POST,$input);
                }else{
                    $flag=false;
                    break;
                }
            }else{
                $flag=false;
                break;
            }
        }
        if($flag==true){
            $person_table->sign_up_person($result);
        }
        return [
            'class_name'=>$flag?$dashboard_page:$sign_page,
            'details'=>$out_details
        ];
    }
}











