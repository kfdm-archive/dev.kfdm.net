<?=new View('header')?>
<?=$pagination->render('digg')?>
<dl>
<?php foreach($quotes as $quote):?>
	<dt><?=$quote->postdate?></dt>
	<dd><?=htmlspecialchars($quote->quote)?></dd>
<?php endforeach;?>
</dl>
<?=$pagination->render('digg')?>
<?=new View('footer')?>