<?php

namespace OldElephant\Engines\Init;

class Initializer
{
    /*
     * needing file name : default is ''
     */
    protected $needing_file_name='';
    /*
     * needing file extension : default is 'needed'
     * this file can be every where in directory of site
     */
    protected $needing_file_extension='needed';
    /*
     * needing file include
     * array of folders that should be include to projects
     * 'program_base_library' => show library who contain php,.. files
     * 'program_base_library' => show library who contain css,js,.. files
     *                  that should be included
     */
    protected $folders_to_be_included=[
        'program_base_library'=>[

        ],
        'program_Appearance'=>'Appearance'
    ];
    /*
     * showing the place of index.php or main directory
     */
    protected $main_site_directory=site_dir;
    /*
     * action all needing functions to be executed to initial data
     */
    public static function action_include()
    {

            $new=new self();
            // '{{@!_CONTENT_!@}}' replace ment
            if($new->read_need_file()['flag']['replacement']=='true'){
                $new->initialize_replacements();
            }
        return ['status'=>'success'];
    }
    /*
     * function for going deep inside folders and include all files
     * with specified extension and set it in an reference array given to it
     */

    public function get_all_files($dir,$ext,& $paths){
            $handle=opendir($dir);
            while($item=readdir($handle)){
                if($item=='.' || $item=='..') {
                    continue;
                }
                $details=pathinfo($item);
                if($ext=='*'){
                    if(is_file($dir.DIRECTORY_SEPARATOR.$item)){
                        array_push($paths,$dir.DIRECTORY_SEPARATOR.$item);
                        continue;
                    }else{
                        $this->get_all_files($dir.DIRECTORY_SEPARATOR.$item,$ext,$paths);
                    continue;}
                }

                // if $item is file and is in that extension
                if(is_file($dir.DIRECTORY_SEPARATOR.$item)){
                    if(key_exists('extension',$details)  ) {
                        if($details['extension']==$ext){
                                array_push($paths,$dir.DIRECTORY_SEPARATOR.$item);
                            continue;
                        }
                        continue;
                    }
                    continue;
                }
                $this->get_all_files($dir.DIRECTORY_SEPARATOR.$item,$ext,$paths);

            }
            closedir($handle);
    }
    /*
     * read the needing file and fetch its data into and array that is like:
     * ///////assume this is .needed file///////////
     *
     *
        ** database

        database.name=your_database_name
        database.host=your_database_host
        database.username=your_database_username
        database.password=your_database_password



        ** site_url
        site_url=http://localhost/CMS/

        ** replacements
        replacement.site_url=http://localhost/CMS/


     * /////////end of .needed file////////////////
     * the output array is :
*     array=[
     *      'database' => [
     *          'name'=>your_database_name
     *          'host'=>your_database_host
     *          'username'=>'your_database_username'
     *          'password'=>'your_database_password'
     *                  ]
     *      'site_url'=> 'http://localhost/CMS/'
     *      'replacement'=>[
     *          'site_url'=>'http://localhost/CMS/'
     *                  ]
*          ]
     */
    public function read_need_file()
    {
        $result=[];
        $init=new self();
        $path=site_dir.'OLS'.DIRECTORY_SEPARATOR.$init->needing_file_name.'.'.$this->needing_file_extension;
                $handler=fopen($path,'r');
                    while($line=fgets($handler)){
                        if(strpos($line,'*')===0 || strlen($line)==2){
                            continue;
                        }
                        list($name,$value)=explode('=',$line);

                        if(strpos($name,'.') && $parts=explode('.',$name)){
                            if(key_exists($parts[0],$result)){
                                $result[$parts[0]][$parts[1]]=trim($value);
                                continue;
                            }
                            $result[$parts[0]]=[$parts[1]=>trim($value)];
                            continue;
                        }
                        $result[$name]=trim($value)   ;


                    }
                fclose($handler);

        return $result;
    }
    /*
     * initialize replacement that need to be done in js,css,.. files
     */
    public function initialize_replacements()
    {
        $exts=[
            'css',
            'js'
        ];
        if($this->read_need_file()['replaceDirection']=='forward'){
            foreach($exts as $ext){
                foreach($this->read_need_file()['replacement'] as $to_be_replace => $replaced){
                    $this->replace_sign($ext,$to_be_replace,trim($replaced));
                }
            }
        }elseif ($this->read_need_file()['replaceDirection']=='reverse'){
            foreach($exts as $ext){
                foreach($this->read_need_file()['replacement'] as $to_be_replace => $replaced){
                    $this->replace_sign_reverse($ext,trim($replaced),$to_be_replace);
                }
            }
        }

    }
    /*
     * function for initialize files who contain '{{@!_CONTENT_!@}}'
     * in there code,it used for replacing code instead that sign in CONTENT
     */
    public function replace_sign($ext,$sign,$value)
    {
        $paths=[];
        $init=new self();
        $init->get_all_files($init->main_site_directory,$ext,$paths);
        foreach ($paths as $path){
            $content=file_get_contents($path);
            if(strpos($content,'{{@!_'.$sign.'_!@}}')!==false){
                $content=str_replace('{{@!_'.$sign.'_!@}}',$value,$content);
                $handler=fopen($path,'r+');
                fputs($handler,$content);
                fclose($handler);
            }
        }
    }
    /*
     * reverse of replace sign
     */
    public function replace_sign_reverse($ext,$sign,$value)
    {
        $paths=[];
        $init=new self();
        $init->get_all_files($init->main_site_directory,$ext,$paths);
        foreach ($paths as $path){
            $content=file_get_contents($path);
            if(strpos($content,$sign)!==false){
                $content=str_replace($sign,"{{@!_".$value."_!@}}",$content);
                $handler=fopen($path,'r+');
                fputs($handler,$content);
                fclose($handler);
            }
        }
    }
}