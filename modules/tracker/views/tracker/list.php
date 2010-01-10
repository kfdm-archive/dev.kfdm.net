<?=View::factory('header');?>
<?php foreach($projects as $project):?>
<h2><?=$project->name?></h2>
<table>
	<tr>
		<th>ID</th>
		<th>Title</th>
		<th>Notes</th>
		<th>Type</th>
	</tr>
<?php foreach($project->tasks as $task):?>
	<tr>
		<th><?=$task->id?></th>
		<th><?=htmlspecialchars($task->title)?></th>
		<th><?=htmlspecialchars($task->notes)?></th>
		<th><?=htmlspecialchars($task->type)?></th>
	</tr>
<?php endforeach;?>
</table>
<?php endforeach;?>
<?=View::factory('footer');?>