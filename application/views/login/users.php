<?=new View('header')?>
<?=new View('login/_admin_nav')?>
<h2>User list</h2>
<table>
	<tr>
		<th>id</th>
		<th>username</th>
		<th>email</th>
		<th>logins</th>
		<th>last login</th>
	</tr>
<?php foreach($users as $user):?>
	<tr>
		<td><a href="/login/users/<?=$user->id?>"><?=$user->id?></a></td>
		<td><?=$user->username?></td>
		<td><?=$user->email?></td>
		<td><?=$user->logins?></td>
		<td><?=date(DATE_RFC822,$user->last_login)?></td>
	</tr>
<?php endforeach;?>
</table>
<?=new View('footer')?>