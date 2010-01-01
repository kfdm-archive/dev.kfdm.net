<?=new View('header')?>
<ul>
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
<dl>
	<dt>Name:</dt>
	<dd><?=$image->name?></dd>
	<dt>Image:</dt>
	<dd><img class="image" src="<?=$image->image()?>" alt="<?=$image->name?>" /></dd>
	<dt>Description:</dt>
	<dd><?=$image->description?></dd>
</dl>
<?=new View('footer')?>