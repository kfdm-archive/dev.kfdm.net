<?=View::factory('header');?>
<h2>Project: <a href="<?=$task->project->generate_url()?>"><?=$task->project->name?></a></h2>
<h3>Task: <?=htmlspecialchars($task->title)?></h3>
<table>
<?php foreach($task->as_array() as $k=>$v):?>
	<tr>
		<td><?=$k?></td>
		<td><?=$v?></td>
	</tr>
<?php endforeach;?>
</table>
<?=View::factory('footer');?>