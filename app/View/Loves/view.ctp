<div class="loves view">
<h2><?php  echo __('Love'); ?></h2>
	<dl>
		<dt><?php echo __('Fb Uid'); ?></dt>
		<dd>
			<?php echo h($love['Love']['fb_uid']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Article'); ?></dt>
		<dd>
			<?php echo $this->Html->link($love['Article']['title'], array('controller' => 'articles', 'action' => 'view', $love['Article']['id'])); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Html->link(__('Edit Love'), array('action' => 'edit', $love['Love']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Love'), array('action' => 'delete', $love['Love']['id']), null, __('Are you sure you want to delete # %s?', $love['Love']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Loves'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Love'), array('action' => 'add')); ?> </li>
	</ul>
</div>
