<div class="categories form">
<?php echo $this->Form->create('Category'); ?>
	<fieldset>
		<legend><?php echo __('Add Category'); ?></legend>
	<?php
		echo $this->Form->input('Category.name');
		echo $this->Form->input('Category.parent_id', array('options' => $parentCategories, 'empty' => '(no parent)', 'default' => 0));
		echo $this->Form->input('Category.slug');
		echo $this->Form->input('Category.color',
			array(	'placeholder' => 'hex color: #FFCC00',
					'label' => 'Color (child categories automatically gets parent color)'));
		echo $this->Form->input('Category.autoimport');
		echo $this->Form->input('Feed.url', array('label' => 'Feed Url'));
		echo $this->Form->input('Category.User', array('label' => 'Users permissions', 'multiple' => 'checkbox', 'options' => $users));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>

<script type="text/javascript">
	$(function() {
		var catcolors = <?php echo json_encode($parentColors); ?>;

		$('#CategoryParentId').change(function(e) {
			var selcolor = $.grep(catcolors, function(catcolor, i){
				return (catcolor.ParentCategory.id == e.target.value);
			});
			
			$('#CategoryColor').val(selcolor[0] ? selcolor[0].ParentCategory.color ? selcolor[0].ParentCategory.color : '' : '');
		});
	});
</script>

</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?></li>
	</ul>
</div>
