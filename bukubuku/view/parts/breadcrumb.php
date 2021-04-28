<div class="breadcrumb container">
	<ul class="d-flex">
		<li><a href="">HOME</a></li>
		<?php if(isset($parents_name)):?>
		<li><a href=""><?php echo $parents_name;?></a></li>
		<?php endif;?>
		<?php if(isset($page_name)):?>
		<li><?php echo $page_name;?></li>
		<?php endif;?>
	</ul>
</div>