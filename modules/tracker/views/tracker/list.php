<?=View::factory('header');?>
<table>
	<tr>
		<th>ID</th>
		<th>Short</th>
		<th>Long</th>
		<th>Type</th>
		<th>Owner</th>
		<th>Reporter</th>
	</tr>
<?php foreach($tasks as $task):?>
	<tr>
		<th><?=$task->id?></th>
		<th><?=htmlspecialchars($task->short)?></th>
		<th><?=htmlspecialchars($task->long)?></th>
		<th><?=htmlspecialchars($task->type)?></th>
		<th><?=$task->owner->username?></th>
		<th><?=$task->reporter->username?></th>
	</tr>
<?php endforeach;?>
</table>
<?=View::factory('footer');?>