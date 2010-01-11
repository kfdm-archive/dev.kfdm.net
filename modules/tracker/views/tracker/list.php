<?=View::factory('header');?>
<?php foreach($projects as $project):?>
<h2><a href="<?=$project->generate_url()?>"><?=$project->name?></a></h2>
<?php	if($project->task_count===0):?>
<div>No Tasks Currently Assigned to <?=$project->name?></div>
<?php	else:?>
<table>
	<tr>
		<th>ID</th>
		<th>Title</th>
		<th>Notes</th>
		<th>Type</th>
		<th>Owner</th>
		<th>Reporter</th>
	</tr>
<?php		foreach($project->tasks as $task):?>
	<tr>
		<th><a href="<?=$task->generate_url()?>"><?=$task->id?></a></th>
		<th><?=htmlspecialchars($task->title)?></th>
		<th><?=htmlspecialchars($task->notes)?></th>
		<th><?=htmlspecialchars($task->type)?></th>
		<th><?=$task->owner->username?></th>
		<th><?=$task->reporter->username?></th>
	</tr>
<?php		endforeach;?>
</table>
<?php	endif;?>
<?php endforeach;?>
<?=View::factory('footer');?>