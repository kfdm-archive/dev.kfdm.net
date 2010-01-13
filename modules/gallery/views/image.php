<?=new View('header')?>
<ul class="breadcrumbs">
	<li><a href="/gallery/">Root</a></li>
<?php foreach($gallery->breadcrumbs() as $g):?>
	<li><a href="<?=$g->generate_url()?>"><?=$g->name?></a></li>
<?php endforeach;?>
<?php if($gallery->id > 0):?>
	<li><a href="<?=$gallery->generate_url()?>"><?=$gallery->name?></a></li>
<?php endif;?>
	<li><a href="<?=$image->generate_url()?>"><?=$image->name?></a></li>
</ul>
<hr />
<?php 
	$prev = $image->prev();
	$next = $image->next();
?>
<div>
<?php if($prev->id):?>
	<a style="float:left" href="<?=$prev->generate_url()?>" title="<?=htmlentities($prev->name)?>">Prev</a>
<?php endif;?>
<?php if($next->id):?>
	<a style="float:right" href="<?=$next->generate_url()?>" title="<?=htmlentities($next->name)?>">Next</a>
<?php endif;?>
	<div style="clear:both;"></div>
</div>
<dl>
	<dt>Name:</dt>
	<dd><?=$image->name?></dd>
	<dt>Image:</dt>
	<dd><img class="image" src="<?=$image->image()?>" alt="<?=$image->name?>" /></dd>
	<dt>Description:</dt>
	<dd><?=$image->description?></dd>
</dl>

<?php if(Auth::instance()->logged_in('login')) echo new View('gallery/_edit_image_form',array('image'=>$image,'gallery'=>$gallery));?>

<?=new View('footer')?>