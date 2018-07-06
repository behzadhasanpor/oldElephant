<?php

namespace OLS\DBTemplates;

use OldElephant\Engines\DB\BasicDBase;



class UserTable extends BasicDBase
{
    /**
     * if this flag be false the parent class do nothing checking
     * and manipulating about derived classes
     * NOTICE: make sure this flag to be true in first time table creation
     * or in other changes that need to act on table
     */
    public $constructor_derived_class_cheeking_flag=true;
    /***
     * @var bool
     * a flag that when is true the class check for structure changes in
     * this class table if flag remain false no change will affected
     * NOTICE : this flag don't have any affect if  $constructor_derived_class_cheeking_flag is false!
     */
    public $check_changes_flag=true;

    /**
     * @var array
     * this is structure and name of this class table
     * every instance of inherit class of BasicDBase is a Table
     * and this property should declare in child class
     */
    public $instance_table=[
        'name'=>'users',
        'structure_array'=>[
            'id'=>'INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY',
            'username'=>'VARCHAR(50) NOT NULL UNIQUE',
            'email'=>'VARCHAR(50) NOT NULL UNIQUE',
            'password'=>'VARCHAR(200) NOT NULL',
            'first_name'=>'VARCHAR(50) NOT NULL',
            'last_name'=>'VARCHAR(50) NOT NULL',
            'status'=>'VARCHAR(50) NOT NULL',
            'create_time'=>'DATE NOT NULL',
            'update_time'=>'DATE NOT NULL',
        ]
    ];



}