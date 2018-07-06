<?php
namespace OLS\Viewers;
use OldElephant\Engines\Presentation\View;

class NotView extends View
{
    public $title='page not found';
    protected $content_files=[
        'header',
        'content-not',
        'footer',
    ];
}