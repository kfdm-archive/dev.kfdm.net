<div>
	<form enctype="multipart/form-data" method="post" action="<?=$gallery->generate_url();?>">
		<fieldset style="float:left">
			<legend>Upload Image</legend>
			<dl>
				<dt>Name</dt>
				<dd><input name="name" <?=isset($_POST['name'])?'value="'.htmlspecialchars($_POST['name']).'"':''?> />
				<dt>File</dt>
				<dd><input name="file" type="file" /></dd>
			</dl>
			<input name="upload_image" type="submit" value="Upload" />
		</fieldset>
	</form>
	<form  method="post" action="<?=$gallery->generate_url();?>">
		<fieldset style="float:left">
			<legend>Link Image</legend>
			<dl>
				<dt>Name</dt>
				<dd><input name="name" <?=isset($_POST['name'])?'value="'.htmlspecialchars($_POST['name']).'"':''?> />
				<dt>File</dt>
				<dd><input name="file" /></dd>
			</dl>
			<input name="link_image" type="submit" value="Upload" />
		</fieldset>
	</form>
	<form  method="post" action="<?=$gallery->generate_url();?>">
		<fieldset style="float:left">
			<legend>New Sub Gallery</legend>
			<dl>
				<dt>Name</dt>
				<dd><input name="name" <?=isset($_POST['name'])?'value="'.htmlspecialchars($_POST['name']).'"':''?> />
			</dl>
			<input name="new_sub_gallery" type="submit" value="Create" />
		</fieldset>
	</form>
	<form  method="post" action="<?=$gallery->generate_url();?>">
		<fieldset style="float:left">
			<legend>Remove Gallery</legend>
			<input name="delete_gallery" type="submit" value="Delete Gallery" onclick="return confirm('Are you sure you want to delete this gallery?')" />
		</fieldset>
	</form>
	<div style="clear:both;"></div>
</div>