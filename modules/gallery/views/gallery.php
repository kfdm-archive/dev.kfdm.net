<?=new View('header')?>
<ul class="breadcrumbs">
	<li><a href="/gallery/">Root</a></li>
<?php foreach($gallery->breadcrumbs() as $g):?>
	<li><a href="<?=$g->generate_url()?>"><?=$g->name?></a></li>
<?php endforeach;?>
<?php if($gallery->id > 0):?>
	<li><a href="<?=$gallery->generate_url()?>"><?=$gallery->name?></a></li>
<?php endif;?>
</ul>
<hr />
<?php if(count($subgalleries)>0):?>
Sub Galleries
<ul class="subgalleries">
<?php foreach($subgalleries as $g): ?>
	<li>
		<a href="<?=$g->generate_url()?>" title="<?=$g->name?>" >
			<img src="<?=$g->thumb()?>" alt="<?=$g->name?>"/>
		</a>
	</li>
<?php endforeach;?>
</ul>
<div style="clear:both;"></div>
<?php endif;?>

Gallery: <?=$gallery->name?>
<?php if(count($images)>0):?>
<?=$pagination->render('digg')?>
<ul class="gallery">
<?php foreach($images as $i): ?>
	<li>
		<a href="<?=$i->generate_url()?>" title="<?=$i->name?>" >
			<img src="<?=$i->thumb()?>" alt="<?=$i->name?>"/>
		</a>
	</li>
<?php endforeach;?>
</ul>
<div style="clear:both;"></div>
<?=$pagination->render('digg')?>
<?php endif;?>

<?php if(Auth::instance()->logged_in('login')) echo new View('gallery/_upload_form',array('gallery'=>$gallery));?>

<?=new View('footer')?>