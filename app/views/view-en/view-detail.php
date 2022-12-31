<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<form class="stdform stdform2" method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/dispatch">
<div class="breadcrumbwidget">
	<ul class="breadcrumb">
		<li><a href="<?php echo NS_ROOT_URL ?>">Home</a> <span class="divider">/</span></li>
		<li><a href="<?php echo '<?php echo $app_base_url; ?>' ?>"><?php echo $controller ?></a> <span class="divider">/</span></li>
		<li class="active">Detail</li>
	</ul>
</div><!--breadcrumbs-->
<div class="pagetitle">
	<div class="row-fluid">
		<div class="span6"><h1><?php echo $controller ?></h1></div>
		<div class="span6">
			<div id="header-button">
				<?php echo '<?php echo new Widget\Hidden(\'token\', $token); ?>' ?>
				<?php echo '<?php echo new Widget\Hidden(\'' . $pk . '\', ' . '$datas[\'' . $pk . '\']' . '); ?>' ?> 
				<ul>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'print\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-print"></span> Print\'); ?>' ?></li>
					<li><?php echo '<?php echo new Widget\Bootstrap\Button(\'dispatch\', \'back\', Widget\Button::BUTTON_SUBMIT, \'<span class="icon-arrow-left"></span> Back\'); ?>' ?></li>
				</ul>
			</div>
		</div>
	</div>
</div><!--pagetitle-->

<div class="maincontent">
	<div class="contentinner">
		<h4 class="widgettitle nomargin shadowed">Detail <?php echo $controller ?></h4>
		<div class="widgetcontent bordered shadowed nopadding">
			<?php foreach($columns as $column => $value): ?>
			<?php if($column == $pk) continue; ?>
			<p>
				<label><?php echo $value; ?></label>
				<span class="field">
					<?php echo '<?php echo $datas[\'' . $column . '\']; ?>' ?> 
				</span>
			</p>
			<?php endforeach; ?>
		</div>
	</div><!--contentinner-->
</div><!--maincontent-->
</form>