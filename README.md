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
  <li>How To Configure FrameWork</li>
  <li>Hello World!</li>
  <li>Directory</li>
  <li>Structure</li>
  <li>Is it MVC-based?</li>
  <li>Update</li>
</ul>
<br>
<br>
Abbreviations:<br>
OL  : oldElephant framework
OLS : OL Source -> a folder contains Developer Configurations
<h3>
How To Configure FrameWork
</h3>
this no need any complex configuration yet!.OL has a configuration file<br>
named '.needed' provided for manage some needing input and framework <br>
initialization.explaint that assume the below text is a part of.needed file:<br>
<br>
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
</pre>
the initializer Engine(explane later) will encode that like this:<br>
the output array is :<br>
<pre>
          array=[
           'database' => [
               'name'=>your_database_name
               'host'=>your_database_host
               'username'=>'your_database_username'
               'password'=>'your_database_password'
                       ]
           'site_url'=> 'http://localhost/CMS/'
           'replacement'=>[
               'site_url'=>'http://localhost/CMS/'
                       ]
               ]
</pre><br>
for this time,i wanna explain important thing about it that <br>
we need to configure framework.<br>
<Strong>database</strong> :this part needed for framework and we should <br>
adjust this information correctly.<br>
<Strong>site_url</strong> :the based url of site in webserver folder like:<br>
webserver : /var/www/html/<br>
project folder : /var/www/html/CMS/<br>
then site_url  =  Scheme://your_host/CMS/ <br>
(Note:the end slash is needed for site_url)

