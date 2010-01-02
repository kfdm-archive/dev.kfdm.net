<?php defined('SYSPATH') OR die('No direct access allowed.');

$config['quotes/(latest|browse|rating)'] = 'quotes/browse/$1';
$config['quotes/(latest|browse|rating)/page/([0-9]+)'] = 'quotes/browse/$1/$2';
