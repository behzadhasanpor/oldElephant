<?php


namespace OldElephant\Engines\Processor\Processes\Persons;
/*---------------------------------------------------------------------------
 *
 * this class is a module for moderate login ,logout and sign up different bunch persons
 * such us 'normal site users','admins','supervisors',...
 * and there isn't any restriction for that number and kind.
 * you should have a table instance class and insert its name in this class instance
 * class and word with its methods!!
 * we ..
 * ----------------------------------------------------------------------------
 */
use OldElephant\Engines\DB\BasicDBase;
use OldElephant\Engines\IO\IO;



class IOPerson
{
    /*
     * $cookie time
     */
    public $cookie_time;
    public $cookie_name;
    /*
     * name for bunch of persons who
     */
    public $persons_table_class_name;
    /*
     * login field that use for logging in
     */
    public $log_using;
    /*
     * log field that use for hashing
     */
    public $log_using_hash;
    /*
     * login person
     * there is a field 'status' that need to be in every IOPerson tables
     * the given password should be hashed
     */
    public $security_fields;

    /*
     * an instance of current instance's table
     */
    public $table_instance;


    public function __construct()
    {
        $this->table_instance= BasicDBase::get_table_class_instance($this->persons_table_class_name);
    }

    public function login_person($person_details){
        if($this->is_person_exists($person_details)['flag']){
            $table=$this->table_instance;
            $rnd=mb_strtoupper(strval(bin2hex(openssl_random_pseudo_bytes(32))));
            setcookie($this->cookie_name,$rnd,time()+$this->cookie_time);
            $name=$person_details[$this->log_using_hash];
            $table->update_in_me([$this->security_fields['status']
            =>sha1($rnd+$person_details[$this->log_using_hash])
                ],[$this->log_using_hash ,$name]);
            return [
                'flag'=>true,
                'details'=>''
            ];

        }else{
            return [
                'flag'=>'user_not_exists',
                'details'=>$this->is_person_exists($person_details)['details']
            ];
        }
    }
    /*
     * logging out a person
     */
    public function logout_current_person()
    {
        $current_username=$this->current_logged_in_person()[$this->log_using_hash];
        if($current_username!==false){
            $rnd1=mb_strtoupper(strval(bin2hex(openssl_random_pseudo_bytes(32))));
            $this->table_instance->update_in_me([$this->security_fields['status']=>$rnd1],
                [$this->log_using_hash ,$current_username]);
            unset($_COOKIE[$this->cookie_name]);
            return true;
        }else{
            return false;
        }
    }
    /*
     * sign up person
     */
    public function sign_up_person($person_details)
    {
        $this->table_instance->insert_to_me($person_details);
    }
    /*
     * check if user exists or not
     */
    public function is_person_exists($person_details)
    {
        $login_flag=true;
        $details=[];
        $where='';
        foreach($person_details as $key => $value){
            if($key=='password'){
                $value=sha1($value);
                $where.="$key = '$value'";
            }else{
                $where.="$key = '$value'";
            }
            if(end(array_keys($person_details))!=$key){
                $where.=' AND ';
            }
        }
        $table=$this->table_instance->search_in_me('*',$where);
        if($table==false){
            $login_flag=false;
            $details='';
        }else{
            $login_flag=true;
            $details='';
        }
        return [
            'flag'=>$login_flag,
            'details'=>$details
        ];
    }
    /*
     * current logged in person
     */
    public function current_logged_in_person()
    {
        if(isset($_COOKIE[$this->cookie_name])){
            $cookie_val=IO::filter_input($_COOKIE,$this->cookie_name);

            foreach ($this->table_instance->search_in_me([$this->log_using_hash,'status'],'1=1') as $id_status)
            {
                if(sha1($cookie_val+$id_status[0])==$id_status[1]){
                    return [
                        $this->log_using_hash=>$id_status[0]
                    ];
                }else{
                    continue;
                }
            }
        }
        return false;
    }
    /*
     * 
     */
    public function get_current_person_full_data()
    {
        $table_describe=array_keys($this->table_instance->instance_table['structure_array']);
        if(isset($_COOKIE[$this->cookie_name])){
            $cookie_val=IO::filter_input($_COOKIE,$this->cookie_name);

            foreach ($this->table_instance->search_in_me([$this->log_using_hash,'status'],'1=1') as $id_status)
            {
                if(sha1($cookie_val+$id_status[0])==$id_status[1]){
                    $record=[];
                    $values=$this->table_instance->search_in_me('*',"$this->log_using_hash = '$id_status[0]'")[0];
                    foreach ($table_describe as $key => $value){
                            $record[$value]=$values[$key];
                    }
                    return $record;
                }else{
                    continue;
                }
            }
        }
        return false;
    }

}

















