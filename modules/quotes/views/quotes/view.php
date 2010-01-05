<?=new View('header')?>
<?=new View('quotes/_nav')?>
<form method="POST">
	<a href="<?=$quote->generate_url()?>"><?=$quote->id?></a> |
	Rating: <?=$quote->rating?> |
	<?=$quote->post_date()?> |
	<input type="hidden" name="id" value="<?=$quote->id?>" />
	<input type="submit" name="rating" value="+" />
	<input type="submit" name="rating" value="-" />
</form>
<hr />
<?=htmlspecialchars($quote->quote)?>

<?=new View('footer')?>