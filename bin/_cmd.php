<?php

//Make it look like a web server
isset($_SERVER['SERVER_NAME'])		or $_SERVER['SERVER_NAME'] = 'localhost';
isset($_SERVER['HTTP_USER_AGENT'])	or $_SERVER['HTTP_USER_AGENT'] = 'console';

//From index.php Driver
define('IN_PRODUCTION', FALSE);
$kohana_application = '../application';
$kohana_modules = '../modules';
$kohana_system = '../system';

version_compare(PHP_VERSION, '5.2', '<') and exit('Kohana requires PHP 5.2 or newer.');
error_reporting(E_ALL & ~E_STRICT);
ini_set('display_errors', TRUE);
define('EXT', '.php');

$kohana_pathinfo = pathinfo(__FILE__);
define('DOCROOT', $kohana_pathinfo['dirname'].DIRECTORY_SEPARATOR);
define('KOHANA',  $kohana_pathinfo['basename']);
is_link(KOHANA) and chdir(dirname(realpath(__FILE__)));

$kohana_application = file_exists($kohana_application) ? $kohana_application : DOCROOT.$kohana_application;
$kohana_modules = file_exists($kohana_modules) ? $kohana_modules : DOCROOT.$kohana_modules;
$kohana_system = file_exists($kohana_system) ? $kohana_system : DOCROOT.$kohana_system;

define('APPPATH', str_replace('\\', '/', realpath($kohana_application)).'/');
define('MODPATH', str_replace('\\', '/', realpath($kohana_modules)).'/');
define('SYSPATH', str_replace('\\', '/', realpath($kohana_system)).'/');

unset($kohana_application, $kohana_modules, $kohana_system);


//From Bootstrap.php
define('KOHANA_VERSION',  '2.3.4');
define('KOHANA_CODENAME', 'buteo regalis');
define('KOHANA_IS_WIN', DIRECTORY_SEPARATOR === '\\');
define('SYSTEM_BENCHMARK', 'system_benchmark');
require SYSPATH.'core/Benchmark'.EXT;
Benchmark::start(SYSTEM_BENCHMARK.'_total_execution');
Benchmark::start(SYSTEM_BENCHMARK.'_kohana_loading');
require SYSPATH.'core/utf8'.EXT;
require SYSPATH.'core/Event'.EXT;
require SYSPATH.'core/Kohana'.EXT;
Kohana::setup();

//Extras
restore_error_handler();
restore_exception_handler();

class Console_Controller extends Controller { var $template = 'kohana_error_disabled'; }
Kohana::$instance = new Console_Controller();
ob_end_flush();

defined('KOHANA_CONSOLE') or define('KOHANA_CONSOLE',TRUE);
