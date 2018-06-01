<?php

use  App\Admin\AdminModule;
use  App\Admin\Controller\DashboardAction;
use  App\Admin\TwigExtension\AdminTwigExtension;

return [
    'admin.prefix'=>'/admin',
    'admin.widgets' => \DI\add([
      \DI\get( App\Admin\Widget\AdminWidget::class )
    ]),
    AdminTwigExtension::class => \DI\autowire()->constructorParameter('widgets',\DI\get('admin.widgets')),
    AdminModule::class => \DI\autowire()->constructorParameter('prefix',\DI\get('admin.prefix')),
    DashboardAction::class =>\DI\autowire()->constructorParameter('widgets',\DI\get('admin.widgets')),

];
