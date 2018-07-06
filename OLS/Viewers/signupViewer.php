<?php

namespace OLS\Viewers;


use OldElephant\Engines\Presentation\View;

class signupViewer extends View
{
    public $title='sign up';
    protected $content_files=[
        'header',
        'content-signup',
        'footer',
    ];
}