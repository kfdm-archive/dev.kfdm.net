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
			<input name="delete_image" type="submit" value="Delete Image" onclick="return confirm('Are you sure you want to delete this image?')" />
		</fieldset>
	</form>
	<form method="post" action="<?=$image->generate_url();?>">
		<fieldset style="float:left">
			<legend>Move Image</legend>
			<select name="gallery">
<?php foreach($this->_gallery_select($gallery) as $k=>$v):?>
				<option value="<?=$k?>" <?=($gallery->id==$k)?'disabled="disabled" selected="selected"':''?>><?=($gallery->id==$k)?$v.' (Current)':$v?></option>
<?php endforeach;?>
			</select>
			<input name="move_image" type="submit" value="Move Image" />
		</fieldset>
	</form>
	<form method="post" action="<?=$image->generate_url();?>">
		<fieldset style="float:left">
			<legend>Set as Gallery Image</legend>
			<input name="gallery" type="hidden" value="<?=$gallery->id?>" />
			<input name="default_image" type="submit" value="Set as Default" />
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