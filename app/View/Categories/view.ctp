<div class="row">
	<div class="categories view">
		<h2>Category "<?php echo h($category['Category']['name']); ?>"</h2>
		<dl>
		<!--
			<dt><?php echo __('Id'); ?></dt>
			<dd>
				<?php echo h($category['Category']['id']); ?>
				&nbsp;
			</dd>
		-->
			<?php if (!empty($category['ParentCategory']['name'])): ?>
			<dt><?php echo __('Parent Category'); ?></dt>
			<dd>
				<?php echo $this->Html->link($category['ParentCategory']['name'], array('controller' => 'categories', 'action' => 'view', $category['ParentCategory']['id'])); ?>
				&nbsp;
			</dd>
			<?php endif; ?>
			<?php if (!empty($category['Feed']['id'])): ?>
			<dt><?php echo __('Feed'); ?></dt>
			<dd>
				<?php echo $this->Html->link($category['Feed']['name'], array('controller' => 'feeds', 'action' => 'view', $category['Feed']['id'])); ?>
				&nbsp;
			</dd>
			<dt><?php echo __('Feed Url'); ?></dt>
			<dd>
				<?php echo $category['Feed']['url']; ?>
				&nbsp;
			</dd>
			<?php endif; ?>
		</dl>
	</div>
	<div class="actions">
		<h3><?php echo __('Actions'); ?></h3>
		<ul class="nav nav-pills nav-stacked">
			<li><?php echo $this->Html->link(__('Edit Category'), array('action' => 'edit', $category['Category']['id'])); ?> </li>
			<li><?php echo $this->Form->postLink(__('Delete Category'), array('action' => 'delete', $category['Category']['id']), null, __('Are you sure you want to delete # %s?', $category['Category']['id'])); ?> </li>
			<li><?php echo $this->Html->link(__('List Categories'), array('action' => 'index')); ?> </li>
			<li><?php echo $this->Html->link(__('New Category'), array('action' => 'add')); ?> </li>
		</ul>
	</div>

<?php if (!empty($category['Feed']['id'])):
		$xmlFeed = Xml::build($category['Feed']['url']);
		$feedData = Set::reverse($xmlFeed);
?>
	<div class="related">
		<h3><?php echo __('Related Feed "'.$category['Feed']['name'].'"'); ?></h3>
		
		<h4>Parsed Articles</h4>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Description'); ?></th>
			<th><?php echo __('Pub Date'); ?></th>
			<th><?php echo __('Images'); ?></th>
			<th><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($feedData['rss']['channel']['item'] as $itemField): ?>
			<tr>
				<td><?php echo $itemField['title']; ?></td>
				<td><?php echo html_entity_decode(strip_tags($itemField['description']), ENT_QUOTES, 'UTF-8'); ?></td>
				<td><?php echo date('Y-m-d H:i:s', strtotime($itemField['pubDate'])); ?></td>
				<td><?php $imageURL = $this->RssStalker->findImages($itemField); echo '<img width="100" src="'.$imageURL.'"/>'; ?></td>
				<td>
					<?php $itemLink = is_array($itemField['link']) ? $itemField['link']['@'] : $itemField['link']; ?>
					<?php echo $this->Html->link(__('View source'), $itemLink, array('target' => '_blank', 'class' => 'btn btn-small')); ?>
					<?php echo $this->Form->create('Article', array('id' => 'importArticle', 'type' => 'post', 'url' => array('controller' => 'articles', 'action' => 'add')));
					echo $this->Form->hidden('Article.title', array('default' => $itemField['title']));
					echo $this->Form->hidden('Article.link', array('default' => $itemLink));
					echo $this->Form->hidden('Article.description', array('default' => html_entity_decode(strip_tags($itemField['description']), ENT_QUOTES, 'UTF-8'))));
					echo $this->Form->hidden('Article.pubDate', array('default' => date('Y-m-d H:i:s', strtotime($itemField['pubDate']))));
					echo $this->Form->hidden('Article.image', array('default' => $imageURL));
					echo $this->Form->hidden('Article.category_id', array('default' => $category['Category']['id']));
					echo $this->Form->hidden('Article.confirm', array('default' => 'toConfirm'));

					echo $this->Form->submit(__('Import'), array('class' => 'btn btn-small'));
					echo $this->Form->end(); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	
<!--
		<h4>Feed parsed data</h4>
		<dl>
		<?php //foreach($feedData['rss']['channel'] as $feedFieldName => $feedFieldVal) : ?>
			<?php //if(!is_array($feedFieldVal) && $feedFieldName!='item') : ?>
			<dt><?php //echo __($feedFieldName); ?></dt>
			<dd>
				<?php //echo h($feedFieldVal); ?>
				&nbsp;
			</dd>
			<?php //endif; ?>
		<?php //endforeach; ?>
		</dl>
		
		<pre><?php //print_r($feedData); ?></pre>
-->
		
	</div>

<?php endif; ?>


<?php if (!empty($category['User'])): ?>

	<div class="related">
		<h3><?php echo __('Authorized Users'); ?></h3>

		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Username'); ?></th>
			<th><?php echo __('Role'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($category['User'] as $user): ?>
			<tr>
				<td><?php echo $user['username']; ?></td>
				<td><?php echo $user['role']; ?></td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>

<?php endif; ?>

<?php if (!empty($category['ChildCategory'])): ?>

	<div class="related">
		<h3><?php echo __('Child Categories'); ?></h3>
		<table cellpadding = "0" cellspacing = "0">
		<tr>
			<th><?php echo __('Name'); ?></th>
			<th class="actions"><?php echo __('Actions'); ?></th>
		</tr>
		<?php
			$i = 0;
			foreach ($category['ChildCategory'] as $childCategory): ?>
			<tr>
				<td><?php echo $childCategory['name']; ?></td>
				<td class="actions">
					<?php echo $this->Html->link(__('View'), array('controller' => 'categories', 'action' => 'view', $childCategory['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Html->link(__('Edit'), array('controller' => 'categories', 'action' => 'edit', $childCategory['id']), array('class' => 'btn btn-small')); ?>
					<?php echo $this->Form->postLink(__('Delete'), array('controller' => 'categories', 'action' => 'delete', $childCategory['id']), array('class' => 'btn btn-small'), __('Are you sure you want to delete # %s?', $childCategory['id'])); ?>
				</td>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>

<?php endif; ?>

</div>