<!DOCTYPE html>
<html>
	<head>
		<title>dev.kfdm.net</title>
		<meta charset="utf-8">
	</head>
	<body>
		<nav>
			<ul>
				<li><a href="/gallery/">Gallery</a>
				<li><a href="/quotes/">Quotes</a></li>
<?php if(Auth::instance()->logged_in()):?>
				<li><a href="/logout/">Logout</a></li>
<?php else:?>
				<li><a href="/login/">Login</a></li>
<?php endif;?>
			</ul>
		</nav>
		