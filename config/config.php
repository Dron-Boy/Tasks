<?php
require_once "core/helper.php";
define("USERID",1);
define("DEPARTMENT",2);
date_default_timezone_set('Europe/Moscow');
use Illuminate\Database\Capsule\Manager as Capsule;
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => 'localhost',
    'database'  => 'cpinua_task',
    'username'  => 'cpinua_cpinua',
    'password'  => '11OsBa10sc',
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
?>