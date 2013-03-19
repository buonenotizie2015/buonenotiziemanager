<div class="articles view">
<h2><?php  echo __('Article'); ?> "<?php echo h($article['Article']['title']); ?>"</h2>
	<dl>
		<dt><?php echo __('Link'); ?></dt>
		<dd><?php echo h($article['Article']['link']); ?>&nbsp;</dd>
		<dt><?php echo __('Description'); ?></dt>
		<dd><?php echo $article['Article']['description']; ?>&nbsp;</dd>
		<dt><?php echo __('PubDate'); ?></dt>
		<dd><?php echo h($article['Article']['pubDate']); ?>&nbsp;</dd>
		<dt><?php echo __('Image'); ?></dt>
		<dd><?php echo $article['Article']['image'] ? '<img src="'.$article['Article']['image'].'"/>' : '&nbsp;'; ?></dd>
		<dt><?php echo __('Category'); ?></dt>
		<dd><?php echo $this->Html->link($article['Category']['name'], array('controller' => 'categories', 'action' => 'view', $article['Category']['id'])); ?>&nbsp;</dd>
		<dt><?php echo __('Love Count'); ?></dt>
		<dd><?php echo h($article['Article']['love_count']); ?>&nbsp;</dd>
	</dl>
</div>
<div class="actions">
	<h3><?php echo __('Actions'); ?></h3>
	<ul class="nav nav-pills nav-stacked">
		<li><?php echo $this->Html->link(__('Edit Article'), array('action' => 'edit', $article['Article']['id'])); ?> </li>
		<li><?php echo $this->Form->postLink(__('Delete Article'), array('action' => 'delete', $article['Article']['id']), null, __('Are you sure you want to delete # %s?', $article['Article']['id'])); ?> </li>
		<li><?php echo $this->Html->link(__('List Articles'), array('action' => 'index')); ?> </li>
		<li><?php echo $this->Html->link(__('New Article'), array('action' => 'add')); ?> </li>
		<li><?php echo $this->Html->link(__('List Categories'), array('controller' => 'categories', 'action' => 'index')); ?> </li>
	</ul>
</div>
