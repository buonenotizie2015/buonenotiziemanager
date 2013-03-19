<div class="articles index">
	<h2><?php echo __('Articles'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('title'); ?></th>
			<th><?php echo $this->Paginator->sort('link'); ?></th>
			<th><?php echo $this->Paginator->sort('description'); ?></th>
			<th><?php echo $this->Paginator->sort('pubDate'); ?></th>
			<th><?php echo $this->Paginator->sort('image'); ?></th>
			<th><?php echo $this->Paginator->sort('category_id'); ?></th>
			<th><?php echo $this->Paginator->sort('love_count'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($articles as $article): ?>
	<tr>
		<td><?php echo h($article['Article']['title']); ?>&nbsp;</td>
		<td><?php echo $this->Html->link(__('View source'), $article['Article']['link'], array('target' => '_blank', 'class' => 'btn btn-small')); ?></td>
		<td><?php echo h($article['Article']['description']); ?>&nbsp;</td>
		<td><?php echo h($article['Article']['pubDate']); ?>&nbsp;</td>
		<td><?php echo $article['Article']['image'] ? '<img src="'.$article['Article']['image'].'"/>' : '&nbsp;'; ?></td>
		<td>
			<?php echo $this->Html->link($article['Category']['name'], array('controller' => 'categories', 'action' => 'view', $article['Category']['id'])); ?>
		</td>
		<td><?php echo h($article['Article']['love_count']); ?>&nbsp;</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $article['Article']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $article['Article']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $article['Article']['id']), array('class' => 'btn btn-small'), __('Are you sure you want to delete # %s?', $article['Article']['id'])); ?>
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
		<li><?php echo $this->Html->link(__('New Article'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Category'), array('controller' => 'categories', 'action' => 'add')); ?> </li>
	</ul>
</div>
