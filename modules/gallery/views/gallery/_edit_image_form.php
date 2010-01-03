<div>
	<form method="post" action="<?=$image->generate_url();?>">
		<fieldset style="float:left">
			<legend>Edit Image</legend>
			<dl>
				<dt>Name</dt>
				<dd><input name="name" value="<?=htmlspecialchars($image->name);?>" /></dd>
				<dt>Description</dt>
				<dd><textarea name="description"><?=htmlspecialchars($image->description);?></textarea></dd>
			</dl>
			<input name="edit_image" type="submit" value="Edit Image" />
		</fieldset>
	</form>
<!-- 
	<form method="post" action="<?=$image->generate_url();?>">
		<fieldset style="float:left">
			<legend>Manipulate Image</legend>
			<input name="rotate_left" type="submit" value="Rotate Image Left" />
			<input name="rotate_right" type="submit" value="Rotate Image Right" />
		</fieldset>
	</form>
 -->	
	<div style="clear:both;"></div>
</div>