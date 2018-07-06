<?php
namespace OLS\Viewers;
use \OldElephant\Engines\Presentation\View;
class dashboardViewer extends View {
    public $title='user dashboard';
    public $redirect_using_flag=true;
    protected $content_files=[
        'header',
        'content-dashboard',
        'footer',
    ];
}