<div class="feeds index">
	<h2><?php echo __('Feeds'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('url'); ?></th>
			<th><?php echo $this->Paginator->sort('category_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($feeds as $feed): ?>
	<tr>
		<td><?php echo h($feed['Feed']['id']); ?>&nbsp;</td>
		<td><?php echo h($feed['Feed']['name']); ?>&nbsp;</td>
		<td><?php echo h($feed['Feed']['url']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($feed['Category']['name'], array('controller' => 'categories', 'action' => 'view', $feed['Category']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $feed['Feed']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $feed['Feed']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $feed['Feed']['id']), array('class' => 'btn btn-small'), __('Are you sure you want to delete # %s?', $feed['Feed']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous'), array(), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ''));
		echo $this->Paginator->next(__('next') . ' >', array(), null, array('class' => 'next disabled'));
	?>
	</div>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Html->link(__('New Feed'), array('action' => 'add')); ?></li>
	</ul>
</div>
