<?php

namespace OLS\Viewers;


use OldElephant\Engines\Presentation\View;

class loginViewer extends View
{
    public $redirect_using_flag=true;
    public $title='login';
    protected $content_files=[
        'header',
        'content-login',
        'footer',
    ];
}