<?php


namespace OldElephant\Engines\DB;


use OldElephant\Engines\Init\Initializer;
use OldElephant\Engines\Speaker\Speak;
use mysqli;

class BasicDBase
{
    public $db_name='';
    public $db_username='';
    public $db_password='';
    public $db_host='';
    public $db_port='';
    public $db_socket='';
    public $db_engine;


    // this methods use for method chaining
    public $mysql;
    public $query;
    public $result;
    public $array_result;

    // child table_name
    public $instance_table;
    public $instance_table_info;
    public $check_changes_flag;
    /*
     *  info table is description about a table
     *  that retrieved from get_table_info() method
     *  and the below table is a description about that
     *  table!
     */
    public $info_table_info=[
        'FieldName'=>'VARCHAR(100)',
        'Type'=>'VARCHAR(100)',
        'Null'=>'VARCHAR(100)',
        'Key'=>'VARCHAR(100)',
        'Default'=>'VARCHAR(100)',
        'Extra'=>'VARCHAR(100)'
    ];
    /*
     * place of instances of this class
     */
    public static $place_of_db_classes='OLS\DBTemplates\\';




    /*
     * constructor
     * this initialize Basic database needing variables
     */
    public function __construct()
    {
        $init=new Initializer();
        $db_details=$init->read_need_file()['database'];
        if(empty($db_details) || count($db_details)<7){
            $exp=new Speak();
            $exp->throw_error("Error:connection to database ");
        }

        $this->db_host=$db_details['host'];
        $this->db_username=$db_details['username'];
        $this->db_name=$db_details['name'];
        $this->db_password=$db_details['password'];
        $this->db_port=$db_details['port'];
        $this->db_socket=$db_details['socket'];
        $this->db_engine=$db_details['engine'];

        // code for create table in child
        // this class childes are a table and when inherit from it,the table will be created with
        // first instance of it
        $this->check_changes_flag=$init->read_need_file()['flag']['table_checking'];
        if(isset($this->instance_table) && $this->check_changes_flag=="true"){
            $this->create($this->instance_table['name'],
                $this->instance_table['structure_array']);

            if(false===self::get_table_info($this->instance_table['name'].'_json')){
                $this->json_info_creator();
            }
            if($this->check_changes_flag){
                $this->check_changes_prepare();
            }

        }
    }
    /*
     * connect to database and return instance of mysql connection
     */
    final public function connect_to_db()
    {
        try{

            $mysql=new mysqli($this->db_host
                ,$this->db_username
                ,$this->db_password
                ,$this->db_name
                ,$this->db_port
                ,$this->db_socket);
        }
        catch(Exception $exp1){

            $exp=new Speak();
            $exp->throw_error("Error:connection has timout ");
        }
        if($mysql->connect_error){
            $exp=new Speak();
            $exp->throw_error("Error:cant reach database ");
        }
        $this->mysql=$mysql;
        return $this;
    }
    /*
     * create new table
     */
    final public function create($table_name,$structure_array)
    {
        $db_name=$this->db_name;
        $query="CREATE TABLE  IF NOT EXISTS `$db_name`.`$table_name` (";
        foreach ($structure_array as $table_property_name=>$table_property_value){

            $query.="`$table_property_name` $table_property_value";
            if($table_property_name!=end(array_keys($structure_array))){
                $query.=',';
            }
        }
        $query.=") ENGINE = $this->db_engine;";
        $this->connect_to_db()->query($query)->exec();
        return $this;
    }
    /*
     * execute given query on database and return results
     */
    final public function query($query)
    {
        if(isset($this->query)){
            $this->connect_to_db()->query.=$query.' ';
            return $this;
        }else{
            $this->connect_to_db()->query=$query.' ';
            return $this;
        }

    }
    /*
     * execute cashed query if query not-defined
     * execute given query if defined
     * without return
     */
    final public function exec($query=null)
    {
        $this->connect_to_db();
        if(!is_null($query)){

            if(isset($this->query)){
                $self_query=$this->query;
                unset($this->query);
                return $this->connect_to_db()->exec($self_query.' '.$query);
            }else{
                return $this->connect_to_db()->query($query)->exec();
            }

        }else{

            if(isset($this->query)){
                $self_query=$this->query;
                $mysql=$this->mysql;
                unset($this->query);
                unset($this->mysql);
                return $mysql->query($self_query);
            }else{
                return false;
            }
        }

    }
    /*
     * fetch all result of an query result
     */
    public function getAll()
    {
        $mysql=$this->mysql;
        $query=$this->query;
        if(isset($mysql)){
            if(isset($query)){
                unset($this->query);
                unset($this->mysql);
                return $mysql->query($query)->fetch_all();
            }
            return false;
        }
        unset($this->mysql);
        unset($this->query);
        return false;
    }
    /*
     * get specified table
     */
    public function get($table_name=null,$child_using_flag=true)
    {
        if(isset($this->instance_table) && $child_using_flag){
            $table_name=$this->instance_table['name'];
            return $this->connect_to_db()->query("select * from  `$this->db_name`.`$table_name`" )->getAll();
        }
        if(!is_null($table_name)){
            return $this->connect_to_db()->query("select * from  `$this->db_name`.`$table_name`" )->getAll();
        }
        return false;
    }
    /*
     * check if table exists or not
     */
    final public static function is_table_exists($table_name)
    {
        $inst=new self();
        return
            $inst->get_table_info($table_name)         //condition to
            ?
            $inst->get_table_info($table_name)->fetch_all() //if true
            :
            false;             //if false
    }
    /*
     * insert record into table
     */
    final public function insert($table_name,$structure,$data)
    {
        $db_name=$this->db_name;
        $query="INSERT INTO `$db_name`.`$table_name` (";
        foreach ($structure as $key=> $item) {
            $query .= "`$item`";
            if ($key != end(array_keys($structure))) {
                $query .= ',';
            }
        }
        $query.=') VALUES (';
        foreach ($data as $key=> $item){
            $query.="'$item'";
            if($key!=end(array_keys($data))){
                $query.=',';
            }
        }
        $query.=")";
        return $this->query($query)->exec();

    }
    /*
     *      * insert method only for derived classes
     */
    public function insert_to_me($input_array)
    {
        if($this->instance_table){
            $db_name=$this->db_name;
            $table_name=$this->instance_table['name'];
            $column_names=array_keys($this->instance_table['structure_array']);
            $query="INSERT INTO `$db_name`.`$table_name` (";
            foreach($column_names as $key => $field){
                    $query.="`$field`";
                if ($key != end(array_keys($column_names))) {
                    $query .= ',';
                }
            }
            $query.=") VALUES (";
            foreach ($column_names as $key => $field){
                if(key_exists($field,$input_array)){
                    $query.="'$input_array[$field]'";
                }else{
                    $query.="''";
                }
                if ($key != end(array_keys($column_names))) {
                    $query .= ',';
                }
            }
            $query.=")";
            $this->query($query)->exec();
        }else{
            return false;
        }
    }
    /*
     * drop a table
     */
    public function drop($table_name)
    {
        $this->query("DROP TABLE $table_name;")->exec();
    }
    /*
     * delete column
     */
    public function delete_from_me($input_array)
    {
        if($this->instance_table){
            $db_name=$this->db_name;
            $table_name=$this->instance_table['name'];
            if($input_array=='*'){
                $query="TRUNCATE TABLE `$db_name`.`$table_name`";
            }else{
                $query="DELETE FROM `$db_name`.`$table_name` WHERE";
                foreach ($input_array as $column => $value){
                    $query.="`$table_name`.`$column` = '$value' ";
                    if ($column != end(array_keys($input_array))) {
                        $query .= ',';
                    }
                }
            }
            $this->query($query)->exec();
        }else{
            return false;
        }
    }
    /*
     * update a column
     */
    public function update_in_me($input_array,$where_clause){
        if($this->instance_table){
            $db_name=$this->db_name;
            $table_name=$this->instance_table['name'];
            $query="UPDATE `$db_name`.`$table_name` SET ";
            foreach ($input_array as $column => $value){
                $query.="`$column` = '$value' ";
                if ($column != end(array_keys($input_array))) {
                    $query .= ',';
                }
            }
            $where_name=$where_clause[0];
            $where_value=$where_clause[1];
            $query.=" WHERE `$table_name`.`$where_name` = '$where_value' ";
            $this->query($query)->exec();
        }else{
            return false;
        }
    }
    /*
     * search in instance tables with given condition
     */
    public function search_in_me($field,$condition)
    {
        if($this->instance_table){
            $db_name=$this->db_name;
            $table_name=$this->instance_table['name'];
            if($field=='*'){
                return $this->query("SELECT * FROM `$db_name`.`$table_name` WHERE $condition")->getAll();
            }
            if(is_array($field)){
                $fields='';
                foreach ($field as $key => $item){
                    $fields.="`$item`";
                    if(end(array_keys($field))!=$key){
                        $fields.=',';
                    }
                }
                return $this->query("SELECT $fields FROM `$db_name`.`$table_name` WHERE $condition")->getAll();
            }
            return $this->query("SELECT `$field` FROM `$db_name`.`$table_name` WHERE $condition")->getAll();
        }else{
            return false;
        }
    }
    /* -----------------------------------------------------------------
     *
     *
     *
     *
     * below methods using for check the changes in structure of Tables that
     * inherit from this class and they are basic and important tables for
     * project . the plan it that there is a flag in child classes
     * when if on the constructor every time check table changes when an instance produce
     * from the child class , and if there is a change in structure of table
     * the constructor drop before table and make new one!
     * --the developer should come to the end before main site publishing
     *
     *
     * -----------------------------------------------------------------
     *
     */



    /*
     * the table info is a table that have array of records details
     * that have structure like '$info_table_info' property of class that
     * defined in above.
     */
    public function get_table_info($table_name=null,$inner_table_info_flag=false)
    {

        if(isset($this->instance_table) && $inner_table_info_flag){
            $table_name=$this->instance_table['name'];
            $description=$this->connect_to_db($table_name)->mysql->query("describe $table_name");
            return
                    $description            //condition to
                    ?
                    $description->fetch_all() //if true
                    :
                    false;
        }else{
            if(!is_null($table_name)){
                $description=$this->connect_to_db($table_name)->mysql->query("describe $table_name");
                return
                    $description            //condition to
                    ?
                    $description //if true
                    :
                    false;
            }else{
                return false;
            }
        }
    }

    /*
     * json_info_table_creator
     * this table is not json yet!!
     */
    protected function json_info_creator()
    {
        $table_name=$this->instance_table['name'].'_json';
        $this->create($table_name,$this->info_table_info);
        $instance_table_structure=$this->get_table_info($this->instance_table['name']);
        foreach ($instance_table_structure as $record){
            $this->insert($table_name,array_keys($this->info_table_info),$record);
        }

    }
    /*
     *
     * check changes and prepare them if change exists
     * by dropping old tables and make new tables
     *
     */
    public function check_changes_prepare()
    {
        $this->create($this->instance_table['name'].'_test',$this->instance_table['structure_array']);
        if($this->get_table_info($this->instance_table['name'].'_test')->fetch_all()!=
        $this->get($this->instance_table['name'].'_json',false)){
            $this->drop($this->instance_table['name']);
            $this->drop($this->instance_table['name'].'_json');
            $this->create($this->instance_table['name'],$this->instance_table['structure_array']);
            $this->json_info_creator();
        }
        $this->drop($this->instance_table['name'].'_test');
    }
    /*
     * get instance from given table_class_name
     */
    public static function get_table_class_instance($class_name)
    {
        $place_of_db_classes=self::$place_of_db_classes;
        return eval("return \$inst = new $place_of_db_classes$class_name();");
    }

}









