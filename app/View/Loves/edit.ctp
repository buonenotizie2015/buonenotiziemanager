<div class="loves form">
<?php echo $this->Form->create('Love'); ?>
	<fieldset>
		<legend><?php echo __('Edit Love'); ?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('fb_uid');
		echo $this->Form->input('article_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Love.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Love.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Loves'), array('action' => 'index')); ?></li>
	</ul>
</div>
