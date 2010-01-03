<?=new View('header')?>
<?=new View('login/_admin_nav')?>
<h2>User View</h2>
<table>
	<tr>
		<th>id</th>
		<th>username</th>
		<th>email</th>
		<th>logins</th>
		<th>last login</th>
	</tr>
	<tr>
		<td><?=$user->id?></td>
		<td><?=$user->username?></td>
		<td><?=$user->email?></td>
		<td><?=$user->logins?></td>
		<td><?=date(DATE_RFC822,$user->last_login)?></td>
	</tr>
</table>
<form method="POST" >
	<fieldset>
		<legend>Roles</legend>
		<input type="hidden" name="id" value="<?=$user->id?>" />
		<table>
<?php foreach($roles as $role):?>
			<tr>
				<td><input type="checkbox" name="roles[<?=$role->id?>]" <?=(in_array($role->id,$user_roles))?'checked="checked"':''?> /><?=$role->name?></td>
				<td><?=$role->description?></td>
			</tr>
<?php endforeach;?>
		</table>
		<input type="submit" name="save_roles" value="Save Roles" />
	</fieldset>
</form>
<form method="POST" >
	<fieldset>
		<legend>Password</legend>
		<input type="hidden" name="id" value="<?=$user->id?>" />
		<input type="password" name="password" />
		<input type="submit" name="reset" value="Reset Password" />
	</fieldset>
</form>
<form method="POST" >
	<fieldset>
		<legend>Management</legend>
		<input type="hidden" name="id" value="<?=$user->id?>" />
		<input type="submit" name="delete" value="Delete User" onclick="return confirm('Are you sure you want to delete this user?');" />
	</fieldset>
</form>
<?=new View('footer')?>