<?php
Configure::write('debug', 0);
for($i=0;$i<count($articles);$i++){
	$articles[$i]['Article']['description'] = strip_tags($articles[$i]['Article']['description']);
	$articles[$i]['Article']['image'] = urlencode($articles[$i]['Article']['image']);
	$articles[$i]['Article']['link'] = urlencode($articles[$i]['Article']['link']);
}
echo json_encode($articles); 
?>