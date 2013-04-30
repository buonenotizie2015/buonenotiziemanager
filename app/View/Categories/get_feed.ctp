<table class="tablesorter table table-condensed table-hover tablefeed" cellpadding = "0" cellspacing = "0">
	<thead>
		<tr>
			<th><?php echo __('Title'); ?></th>
			<th><?php echo __('Description'); ?></th>
			<th><?php echo __('Pub Date'); ?></th>
			<th><?php echo __('Images'); ?></th>
			<th><?php echo __('Actions'); ?></th>
		</tr>
	</thead>
	<tbody>
	<?php
		$i = 0;
	
		foreach ($channel['channel']['item'] as $itemField):
			
			$inserted = false;
			foreach($articles as $article){
				if($article['Article']['title']==$itemField['title'])
					$inserted = true;
			}
			
			if(isset($itemField['pubDate']))
				$pubDate = date('Y-m-d H:i:s', strtotime($itemField['pubDate']));
			else
				$pubDate = date('Y-m-d H:i:s');
	?>
		<tr <?php echo $inserted!=false ? 'class="articleInserted"' : ''; ?> >
			<td><?php echo $itemField['title']; ?></td>
			<td><?php echo html_entity_decode(strip_tags($itemField['description']), ENT_QUOTES, ''); ?></td>
			<td><?php echo isset($itemField['pubDate']) ? $pubDate : '<span class="label label-important">Data mancante</span><br/>'.$pubDate; ?></td>
			<td><?php $imageURL = $this->RssStalker->findImages($itemField, null); echo '<img width="100" src="'.$imageURL.'"/>'; ?></td>
			<td>
				<?php $itemLink = is_array($itemField['link']) ? $itemField['link']['@'] : $itemField['link']; ?>
				<?php echo $this->Html->link(__('View'), $itemLink, array('target' => '_blank', 'class' => 'btn btn-small')); ?>
				<?php echo $this->Form->create('Article', array('id' => 'importArticle', 'type' => 'post', 'url' => array('controller' => 'articles', 'action' => 'add')));
				echo $this->Form->hidden('Article.title', array('default' => $itemField['title']));
				echo $this->Form->hidden('Article.link', array('default' => $itemLink));
				echo $this->Form->hidden('Article.description', array('default' => html_entity_decode(strip_tags($itemField['description']), ENT_QUOTES, '')));
				echo $this->Form->hidden('Article.pubDate', array('default' => $pubDate));
				echo $this->Form->hidden('Article.image', array('default' => $imageURL));
				echo $this->Form->hidden('Article.category_id', array('default' => $categoryid));
				echo $this->Form->hidden('Article.confirm', array('default' => 'toConfirm'));

				echo $this->Form->button(__('Import'), array('type' => 'button', 'class' => 'import-article btn btn-small'));
				echo $this->Form->end(); ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>