<?php

namespace OLS\Routing;

/**
 * this file included in Router class and
 * contain $route_assignment variable for
 *
 *
 *
 */

/*
 * an array that hold every route to its viewer
 * and different routes can have same viewers but-
 * they cant have multiple viewers
 *
 */

trait RouteResource{

    public $route_assignment=[
        'home'=>[
            'http_method'=>'get',
            'auth_id'=>'',
            'viewer'=>'HomeView'
        ],
        'dashboard'=>[
            'http_method'=>'get',
            'auth_id'=>'0',
            'viewer-dashboard'=>'dashboardViewer',
            'viewer-loginPage'=>'loginViewer'
        ],
        'signup'=>[
            'http_method'=>'get',
            'auth_id'=>'',
            'viewer'=>'signupViewer'
        ],
        'login'=>[
            'http_method'=>'post',
            'auth_id'=>'1',
            'viewer-dashboard'=>'dashboardViewer',
            'viewer-loginPage'=>'loginViewer'
        ],
        'signup/add'=>[
            'http_method'=>'post',
            'auth_id'=>'2',
            'viewer-signup'=>'signupViewer',
            'viewer-loginPage'=>'loginViewer'
        ],
        'signout'=>[
            'http_method'=>'get',
            'auth_id'=>'3',
            'viewer-home'=>'HomeView',
        ],
    ];
    public $special_route_assignment=[
        'category/type-@_'=>[
            'http_method'=>'get',
            'auth_id'=>'',
            'viewer'=>'categoryViewer'
        ],
        '^'=>[
            'http_method'=>'get',
            'auth_id'=>'',
            'viewer'=>'NotView'
        ],  //this show Viewer that viewed when none of above route can't be accrue
    ];
    public $ajax_common_route_assignment=[
        'product'=>[
            'http_method'=>'get',
        ]
    ];
    public $ajax_special_route_assignment=[
        // didn't implement this yet!
    ];
    /*
     *
     */
}