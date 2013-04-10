<?php
Configure::write('debug', 0);
for($i=0;$i<count($articles);$i++){
	$articles[$i]['Article']['description'] = strip_tags($articles[$i]['Article']['description']);
}
//echo '{ "datas" : "'.$reqPage.' - '.$fromArt.'",';
echo json_encode($articles);
?>