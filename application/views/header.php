<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>dev.kfdm.net</title>
		<!--[if IE]>
			<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->
		<link rel="stylesheet" href="/style.css" media="screen" />
	</head>
	<body class="<?=strtolower( substr( get_class($this),0,-11) ) ?>">
		<div id="container">
			<nav>
				<div>
					<h1>dev.kfdm.net</h1>
					<ul>
						<li><a href="/gallery/">Gallery</a></li>
						<li><a href="/quotes/">Quotes</a></li>
						<li><a href="/tracker/">Tracker</a></li>
<?php if(Auth::instance()->logged_in()):?>
						<li><a href="/logout/">Logout (<?=Auth::instance()->get_user()->username?>)</a></li>
<?php else:?>
						<li><a href="/login/?next=/<?=url::current()?>/">Login</a></li>
<?php endif;?>
					</ul>
				</div>
			</nav>

<?php if(isset($global_errors)) View::factory('_global_errors')->render(TRUE);?>
<?php if(isset($global_notices)) View::factory('_global_notices')->render(TRUE);?>

			<div id="content">