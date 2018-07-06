<?php

namespace OLS\Processors\IOPersons;


use OldElephant\Engines\Processor\Processes\Persons\IOPerson;

class Users extends IOPerson
{
    /*
     * $cookie time
     */
    public $cookie_time=72*3600;
    public $cookie_name='__oldElephant';
    /*
     * name for bunch of persons who
     */
    public $persons_table_class_name='UserTable';
    /*
     * login field that use for logging in
     */
    public $log_using=['username','password'];
    /*
     * log field that use for hashing
     */
    public $log_using_hash='username';
    public $table_instance;

    /*
     * two field that need for person security
     */
    public $security_fields=[
        'status'=>'status',
        'token'=>'number'
    ];
}