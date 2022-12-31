<?php echo '<?php use \Inationsoft\NS; use \Inationsoft\NS\UI\Widget; ?>' ?>
<form class="stdform stdform2" method="post" action="<?php echo '<?php echo $app_base_url; ?>' ?>/dispatch">
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
<script type="text/javascript">
	window.print();
	window.close();
</script>