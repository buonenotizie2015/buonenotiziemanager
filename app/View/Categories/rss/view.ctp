<?php
Configure::write('debug', 0);
App::uses('Sanitize', 'Utility');

$this->set('channelData', array(
	'title' => __($category['ParentCategory']['name'].' - '.$category['Category']['name']),
	'link' => $this->Html->url('/', true),
	'language' => 'it-it'
));

foreach ($articles as $article) {
	echo $this->Rss->item(array(), array(
		'title' => $article['Article']['title'],
		'link' => $article['Article']['link'],
		'description' => $article['Article']['description'],
		'pubDate' => $article['Article']['pubDate']
	));
}
?>