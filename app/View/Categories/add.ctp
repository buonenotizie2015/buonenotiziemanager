<div class="categories form">
<?php echo $this->Form->create('Category'); ?>
	<fieldset>
		<legend><?php echo __('Add Category'); ?></legend>
	<?php
		echo $this->Form->input('Category.name');
		echo $this->Form->input('Category.parent_id', array('options' => $parentCategories, 'empty' => '(no parent)', 'default' => 0));
		echo $this->Form->input('Category.slug');
		echo $this->Form->input('Feed.url', array('label' => 'Feed Url'));
		echo $this->Form->input('Category.User', array('label' => 'Users permissions', 'multiple' => 'checkbox', 'options' => $users));
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?></li>
	</ul>
</div>
