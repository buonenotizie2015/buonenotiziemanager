<?php
if (!isset($documentData)) {
	$documentData = array();
}
if (!isset($channelData)) {
	$channelData = array();
}
if (!isset($channelData['title'])) {
	$channelData['title'] = $title_for_layout;
}

echo $this->Rss->document($documentData, $this->Rss->channel(array(), $channelData, $this->fetch('content')));
?>
