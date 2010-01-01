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
				<dd><input name="url" /></dd>
			</dl>
			<input name="link_image" type="submit" value="Upload" />
		</fieldset>
	</form>
	<div style="clear:both;"></div>
</div>