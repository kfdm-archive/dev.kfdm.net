<div>
	<form method="post" action="<?=$task->generate_url()?>">
		<fieldset style="float:right">
			<legend>Assign to</legend>
				<select name="owner">
<?php foreach($this->_user_select('login') as $k=>$v):?>
					<option value="<?=$k?>" <?=($task->owner->id==$k)?'disabled="disabled" selected="selected"':''?>><?=($task->owner->id==$k)?$v.' (Current)':$v?></option>
<?php endforeach;?>
				</select>
				<input name="assign_task" type="submit" value="Assign" />
		</fieldset>
	</form>
	<form method="post" action="<?=$task->generate_url()?>">
		<fieldset style="float:right">
			<legend>Move to</legend>
				<select name="project">
<?php foreach($this->_project_select() as $k=>$v):?>
					<option value="<?=$k?>" <?=($task->project->id==$k)?'disabled="disabled" selected="selected"':''?>><?=($task->project->id==$k)?$v.' (Current)':$v?></option>
<?php endforeach;?>
				</select>
				<input name="move_task" type="submit" value="Assign" />
		</fieldset>
	</form>
</div>