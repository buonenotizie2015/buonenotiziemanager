<div class="feeds view">
<h2><?php  echo __('Feed'); ?></h2>
	<dl>
		<dt><?php echo __('Name'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['name']); ?>
			&nbsp;
		</dd>
		<dt><?php echo __('Url'); ?></dt>
		<dd>
			<?php echo h($feed['Feed']['url']); ?>
			&nbsp;
		</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul>
		<li><?php echo $this->Html->link(__('Edit Feed'), array('action' => 'edit', $feed['Feed']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Feed'), array('action' => 'delete', $feed['Feed']['id']), null, __('Are you sure you want to delete # %s?', $feed['Feed']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Feeds'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Feed'), array('action' => 'add')); ?> </li>
	</ul>
</div>
<div class="related">
	<h3><?php echo __('Related Feeds'); ?></h3>
	<?php if (!empty($category['feeds'])): ?>
	<table cellpadding = "0" cellspacing = "0">
	<tr>
		<th><?php echo __('Name'); ?></th>
		<th><?php echo __('Url'); ?></th>
		<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php
		$i = 0;
		foreach ($category['feeds'] as $feeds): ?>
		<tr>
			<td><?php echo $feeds['name']; ?></td>
			<td><?php echo $feeds['url']; ?></td>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('controller' => 'feeds', 'action' => 'view', $feeds['id'])); ?>
				<?php echo $this->Html->link(__('Edit'), array('controller' => 'feeds', 'action' => 'edit', $feeds['id'])); ?>
				<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'feeds', 'action' => 'delete', $feeds['id']), null, __('Are you sure you want to delete # %s?', $feeds['id'])); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</table>
<?php endif; ?>

	<div class="actions">
		<ul>
			<li><?php echo $this->Html->link(__('New Feeds'), array('controller' => 'feeds', 'action' => 'add')); ?> </li>
		</ul>
	</div>
</div>