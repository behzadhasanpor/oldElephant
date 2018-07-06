<?php
namespace OLS\Viewers;
use OldElephant\Engines\Presentation\View;

class categoryViewer extends View
{
    public $title='api';
    protected $content_files=[
        'header',
        'content-category',
        'footer',
    ];

}