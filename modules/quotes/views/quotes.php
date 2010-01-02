<?=new View('header')?>
<?=new View('quotes/_nav')?>
<?=$pagination->render('digg')?>
<dl>
<?php foreach($quotes as $quote):?>
	<dt>
		<form method="POST">
			<a href="<?=$quote->generate_url()?>"><?=$quote->id?></a> |
			Rating: <?=$quote->rating?> |
			<?=$quote->post_date()?> |
			<input type="hidden" name="id" value="<?=$quote->id?>" />
			<input type="submit" name="rating" value="+" />
			<input type="submit" name="rating" value="-" />
		</form>
	</dt>
	<dd><?=htmlspecialchars($quote->quote)?></dd>
<?php endforeach;?>
</dl>
<?=$pagination->render('digg')?>
<?=new View('footer')?>