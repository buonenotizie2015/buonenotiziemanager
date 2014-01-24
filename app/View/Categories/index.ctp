<div class="categories index">
	<h2><?php echo __('Categories'); ?></h2>
	<table cellpadding="0" cellspacing="0">
	<tr>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<th><?php echo $this->Paginator->sort('slug'); ?></th>
			<th><?php echo $this->Paginator->sort('parent_id'); ?></th>
			<th><?php echo $this->Paginator->sort('Feed'); ?></th>
			<th><?php echo $this->Paginator->sort('Autoimport'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
	</tr>
	<?php foreach ($categories as $category):
			$isParent = $category['ParentCategory']['name'] ? false : true; ?>
	<tr <?php echo $isParent ? 'style="background-color:#eee;"' : ''; ?>>
		<td><?php echo $isParent ? '<b style="text-transform:uppercase;">'.h($category['Category']['name']).'</b>' : '&rarr; '.h($category['Category']['name']); ?>&nbsp;</td>
		<td><?php echo h($category['Category']['slug']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($category['ParentCategory']['name'], array('controller' => 'categories', 'action' => 'view', $category['ParentCategory']['id'])); ?>
		</td>
		<td>
			<?php echo $this->Html->link($category['Feed']['name'], array('controller' => 'feeds', 'action' => 'view', $category['Feed']['id'])); ?>
		</td>
		<td>
			<?php if(!$isParent) echo $category['Category']['autoimport']==0 ? 'No' : 'Yes'; ?>
		</td>
		<td class="actions">
			<?php echo $this->Html->link(__('View'), array('action' => 'view', $category['Category']['id']), array('class' => 'btn btn-small')); ?>
			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $category['Category']['id']), array('class' => 'btn btn-small')); ?>
			<?php if(!$isParent) echo $this->Html->link(__('Import Feed Articles'), array('action' => 'importCategoryArticles', $category['Category']['id']), array('class' => 'btn btn-small'), 'Vuoi davvero importare tutti gli Articoli dal feeed di questa categoria?'); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $category['Category']['id']), array('class' => 'btn btn-small'), __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
	</table>
	<!--<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?></p>-->
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
		<li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?></li>
		<li><?php echo $this->Html->link(__('Repair Order Category'), array('action' => 'repair')); ?></li>
		<li><?php echo $this->Html->link(__('Import all Articles (only where autoimport=Yes)'), array('action' => 'importAllArticles'), array(), 'Vuoi davvero importare tutti gli Articoli dai feed di tutte le Categorie?'); ?></li>
	</ul>
</div>
