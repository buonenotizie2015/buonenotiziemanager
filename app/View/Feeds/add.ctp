<div class="feeds form">
<?php echo $this->Form->create('Feed'); ?>
	<fieldset>
		<legend><?php echo __('Add Feed'); ?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('url');
		echo $this->Form->input('category_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">

		<li><?php echo $this->Html->link(__('List Feeds'), array('action' => 'index')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>
