<img src="http://oi63.tinypic.com/aw709.jpg">
<h3>
Intro
</h3>
the oldElephant is a php framework i just designed it first for <br>
my personal development purposes! but i saw it has more<br>
ability to use as a 'frame for work' and develope php webpages,thus<br>
i decided to publish it there and i will try to extend this project.<br>
<br>
i will explain project aspects and usages in bellow headers:<br>
<ul>
  <li>Basic of .needed file and How To Configure FrameWork</li>
  <li>Hello World!</li>
  <li>Directory</li>
  <li>Structure</li>
  <li>Is it MVC-based?</li>
  <li>Update</li>
</ul>
<br>
<br>
Abbreviations:<br>
OL  : oldElephant framework<br>
OLS : OL Source -> a folder contains Developer Configurations
<h3>
Basic Of .needed file
</h3>
for OL, no need any complex configuration yet!.OL has a configuration file<br>
named '.needed' provided for manage some needing input and framework <br>
initialization.this file is in OLS folder.assume the below <br>
text is a part of .needed file : <br>
<pre>
        ** database<br>
        database.name=your_database_name<br>
        database.host=your_database_host<br>
        database.username=your_database_username<br>
        database.password=your_database_password<br>
        ** site_url<br>
        site_url=http://localhost/CMS/<br>
        ** replacements<br>
        replacement.site_url=http://localhost/CMS/<br>
        ** direction of replacement
        ** 'reverse' for reverse all replacements
        ** 'forward' for do all replacements
        replaceDirection=reverse
        ** flags
        flag.replacement=false
        flag.table_checking=false
</pre>
the initializer Engine(explaination later) will encode that like this:<br>
the output array is :<br>
<pre>
          array=[
           'database' => [
               'name'=>'your_database_name'
               'host'=>'your_database_host'
               'username'=>'your_database_username'
               'password'=>'your_database_password'
                       ]
           'site_url'=> 'http://localhost/CMS/'
           'replacement'=>[
               'site_url'=>'http://localhost/CMS/'
                       ]
            'replaceDirection'=>'reverse'
            'flag'=>[
                 'replacement'=>'false'
                 'table_checking'=>'false'
                    ]
               ]
</pre><br>
for this time,i wanna explain important things about .needed that <br>
we need to configure framework.<br>
<br>
<strong>database</strong> :this part needed for framework and we should <br>
adjust this information correctly.<br>
<br>
<strong>site_url</strong> :the based url of site in webserver folder like:<br>
webserver : /var/www/html/<br>
project folder : /var/www/html/CMS/<br>
then site_url  =  Scheme://your_host/CMS/ <br>
(Note:the end slash is needed for site_url)<br>
<br>
<strong>replacement</strong>s: it used for indicate what to be replaced with 'site_url' placeholders<br>
in Apearance folder with specified url<br>
<strong>replaceDirection</strong>: indicate direction of replace placeholders with orginal<br>
replace word of them<br>
<strong>flag</strong>: control flags<br>
<h3>How to configure?</h3>
Now for first configure the framework we need to adjust the .needed file with our desired<br>
settings ,then switch the replacement flag true(we do this only at begining of configuration or <br>
at transfer the project to a new directory and in the other status the flag should<br>
remain false),after that we should adjust the replacement direction to forward and access to <br>
project using 
<br>
.....to be written in future 

