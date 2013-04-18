<div class="categories form">
<?php echo $this->Form->create('Category'); ?>
	<fieldset>
		<legend><?php echo __('Edit Category'); ?></legend>
	<?php
		echo $this->Form->input('id', array('type' => 'hidden'));
		echo $this->Form->input('name');
		echo $this->Form->input('parent_id', array('options' => $parentCategories, 'empty' => '(no parent)'));
		echo $this->Form->input('slug');
		if ($this->Session->read('Auth.User.role') === 'admin'){
			echo $this->Form->input('User', array('multiple' => 'checkbox', 'options' => $users, 'selected' => $selectedUsers));
		}
	?>
	</fieldset>
<?php echo $this->Form->end(__('Submit')); ?>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $this->Form->value('Category.id')), null, __('Are you sure you want to delete # %s?', $this->Form->value('Category.id'))); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?></li>
	</ul>
</div>
