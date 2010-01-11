<?=View::factory('header');?>
<h2>Project: <a href="<?=$task->project->generate_url()?>"><?=$task->project->name?></a></h2>
<h3>Task: <?=htmlspecialchars($task->title)?></h3>
<?php if(Auth::instance()->logged_in('login')) echo new View('tracker/_edit_task',array('task'=>$task));?>
<table>
<?php foreach($task->as_array() as $k=>$v):?>
	<tr>
		<td><?=$k?></td>
		<td><?=$v?></td>
	</tr>
<?php endforeach;?>
</table>
<div style="clear:both;"></div>
<?=View::factory('footer');?>