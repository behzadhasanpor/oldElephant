<img src="http://oi63.tinypic.com/aw709.jpg">

att : <span style="color:red">the codes of this project didnt refactored yet</span> and has a big TODO for work on that!!
<br>
<h3>
Intro
</h3>
the oldElephant is a php framework i just designed it first for <br>
my personal development purposes! but i saw it has more<br>
ability to use as a 'frame for work' and develope php Applications,thus<br>
i decided to publish it there and i will try to extend this project.<br>
<ul>
  <li>Basic of .needed file and How To Configure FrameWork</li>
  <li>Directory</li>
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
text is a sample of .needed file : <br>
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
<strong>site_url</strong> :the based url of site.<br>
assume that we have below details about a web site:<br>
webserver : /var/www/html/<br>
project folder : /var/www/html/CMS/<br>
then we have -> site_url  =  Scheme://your_host/CMS/ <br>
(Note:the end slash is needed for site_url)<br>
<br>
<strong>replacement</strong>s: it used for indicate what to be replaced with 'site_url' placeholders<br>
in Apearance folder with specified url<br>
<strong>replaceDirection</strong>: indicate direction of replace placeholders with orginal<br>
replace word of them<br>
<strong>flag</strong>: control flags<br>
<h3>How to configure?</h3>
Now for first configure the framework (after download or clone the OL)<br>
1-) adjust the <strong>database</strong> part with desired settings<br>
2-) adjust the site_url<br>
3-) switch the 'replacement' and 'table_checking' <strong>flag</strong>s 'true'(we do this only at first configuration or <br>
at transfer the project to a new directory and in the other status the flag should<br>
remain false).<br>
4-) adjust the <strong>replaceDirection</strong> to 'forward' and access to <br>
project using 
<br>
5-) once type site_url in a browser and Enter.then the initializer Engine will<br>
initialize the configuration of OL and the intro page will be shown<br>
6-) switch all flags false<br>
<br>
<h2>Directory</h2>
<strong>Appearance</strong> directory : <br>
contained styles,scripts,.. ,all files with .css and .js scripts can automaticaly add to project<br>
by Moderator Engine(Explanation later)<br>
<strong>Media</strong> directory : <br>
contained all Media files<br>
<strong>OldElephant</strong> directory : <br>
main OL framework directory contained all OL 'Engines'<br>
<strong>OLS</strong> directory : <br>
a folder contained OL source developer configuration(we should add our configuration
of web-site in OLS)<br>
<strong>.htaccess</strong> file : <br>
web server configuration file<br>
<strong>index.php</strong> file : <br>
main page for OL-based site(adjusted in .htaccess -> all site_url requests will guide to this file)<br>
<br>
<h2>Update</h2>
the OL is toddler and will be update and grow up,and as soon as possible a <br>
documentaion about it will be published.

  

