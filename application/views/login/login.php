<?=new View('header')?>
<?php if(isset($login_error)):?>
			<td><span class="error"><?=$login_error?></span></td>
<?php endif;?>
<form method="post" action="/login/">
	<table>
		<tr>
			<td>Username</td>
			<td><input name="name" <?=isset($name)?'value="'.$name.'"':'';?> /></td>
<?php if(isset($name_error)):?>
			<td><span class="error"><?=$name_error?></span></td>
<?php endif;?>
		</tr>
		<tr>
			<td>Password</td>
			<td><input name="password" type="password" /></td>
<?php if(isset($pass_error)):?>
			<td><span class="error"><?=$pass_error?></span></td>
<?php endif;?>
		</tr>
	</table>
	<input type="submit" value="Login" />
</form>
<?=new View('footer')?>