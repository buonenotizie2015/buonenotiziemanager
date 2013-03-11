<div class="loves index">
	<h2><?php echo __('Loves'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('fb_uid'); ?></th>
			<th><?php echo $this->Paginator->sort('article_id'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($loves as $love): ?>
	<tr>
		<td><?php echo h($love['Love']['id']); ?>&nbsp;</td>
		<td><?php echo h($love['Love']['fb_uid']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($love['Article']['title'], array('controller' => 'articles', 'action' => 'view', $love['Article']['id'])); ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $love['Love']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $love['Love']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $love['Love']['id']), array('class' => 'btn btn-small'), __('Are you sure you want to delete # %s?', $love['Love']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
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
		<li><?php echo $this->Html->link(__('New Love'), array('action' => 'add')); ?></li>
	</ul>
</div>
