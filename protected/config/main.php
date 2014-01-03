<?php

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Первый сайт',
    'language'=>'ru',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
	),

	'modules'=>array(
		// uncomment the following to enable the Gii tool
        'admin',
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'123',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1','::1'),
		),

	),

	// application components
	'components'=>array(
        'ih'=>array('class'=>'CImageHandler'),
        'authManager' => array(
            // Будем использовать свой менеджер авторизации
            'class' => 'PhpAuthManager',
            // Роль по умолчанию. Все, кто не админы, модераторы и юзеры — гости.
            'defaultRoles' => array('guest'),
        ),
        'clientScript'=>array(
            'packages'=>array(
                'jquery' => array(
                    'baseUrl' => 'jquery',
                    'js' => array('jquery-1.10.2.js'),
                ),
                'treeview' => array(
                    'baseUrl' => 'jquery/treeview',
                    'js' => array('jquery.treeview.js'),
                    'css' => array('jquery.treeview.css'),
                    'depends' => array('jquery'),
                ),
                'contextmenu' => array(
                    'baseUrl' => 'jquery/contextmenu',
                    'js' => array('jquery.contextmenu.js'),
                    'css' => array('jquery.contextmenu.css'),
                    'depends' => array('jquery'),
                ),
                'cookie' => array(
                    'baseUrl' => 'jquery',
                    'js' => array('jquery.cookie.js'),
                    'depends' => array('jquery'),
                ),
                'ui' => array(
                    'baseUrl' => 'jquery',
                    'js' => array('jquery-ui-1.9.2.custom.min.js'),
                    'depends' => array('jquery'),
                )
            )
        ),
        'user'=>array(
            'class' => 'WebUser',
            // …
        ),
		// uncomment the following to enable URLs in path-format

		'urlManager'=>array(
			'urlFormat'=>'path',
            'showScriptName'=>false,
			'rules'=>array(
				/*'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',*/
                array(
                    'class' => 'application.components.PageUrlRule',
                    'connectionID' => 'db',
                ),

			),
		),
		/*'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),*/
		// uncomment the following to use a MySQL database

		'db'=>array(
			'connectionString' => 'mysql:host=localhost;dbname=yii-my-cms',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
            'tablePrefix' => "cms_",
			'charset' => 'utf8',
            'enableProfiling' => true,
            'enableParamLogging' => true,
		),

		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
                array(
                    'class' => 'ext.yii-debug-toolbar.YiiDebugToolbarRoute',
                    'ipFilters'=>array('127.0.0.1'),
                ),
            ),
        ),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'webmaster@example.com',
	),
);