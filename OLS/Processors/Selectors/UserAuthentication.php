<?php

namespace OLS\Processors\Selectors;

use OLS\Processors\IOPersons\Users;
use OldElephant\Engines\Processor\Selectors\Selector;

class UserAuthentication extends Selector
{

    public $online=false;
    /*
     * it is a function that execute when an instance correspond to
     * this class's auth_id , created! and this function take one
     * variable $route_detail and should return an array of
     *      [
     *  'viewer' => 'related_viewer'  // to view at Router
     *  'auth_details'=>'//'    // some auth details
     *      ]
     */
    public function setting($route_details){
        $user=new Users();
        switch ($route_details['auth_id']){
            case 0:
                // try to access dashboard view
                return [
                    'class_name'=>$user->current_logged_in_person()?$route_details['viewer-dashboard']:$route_details['viewer-loginPage'],
                    'details'=>'ok'
                ];
                break;
            case 1:
                // try to login :
                $login_array=[
                    'username',
                    'password',
                ];
                return $this->person_logging_in($user,$login_array,$route_details['viewer-dashboard'],$route_details['viewer-loginPage'],'refresh-need');
                break;
            case 2:
                // try to sign up
                $arr_of_field=[
                    'username',
                    'password',
                    'email',
                    'first_name',
                    'last_name',
                ];
                return $this->person_sign_up($user,$arr_of_field,$route_details['viewer-signup'],$route_details['viewer-loginPage'],'ok');
                break;
            case 3:
                // try to sign out
                $user->logout_current_person();
                return [
                    'class_name'=>$route_details['viewer-home'],
                    'details'=>'ok'
                ];
        }

    }


}
















