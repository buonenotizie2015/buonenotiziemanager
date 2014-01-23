<div class="categories form">
<?php echo $this->Form->create('Category'); ?>
	<fieldset>
		<legend><?php echo __('Edit Category'); ?></legend>
	<?php
		echo $this->Form->input('Category.id', array('type' => 'hidden'));
		echo $this->Form->input('Category.name');
		echo $this->Form->input('Category.parent_id', array('options' => $parentCategories, 'empty' => '(no parent)'));
		echo $this->Form->input('Category.slug');
		echo $this->Form->input('Category.color', 
			array('value'=> !empty($parentColor)? $parentColor['Category']['color']: null,
				'placeholder' => 'hex color: #FFCC00',
				'label' => 'Color (child categories automatically gets parent color)'));
		echo $this->Form->checkbox('Category.auto_import');
		echo $this->Form->input('Feed.id', array('type' => 'hidden'));
		echo $this->Form->input('Feed.url', array('label' => 'Feed Url'));
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
