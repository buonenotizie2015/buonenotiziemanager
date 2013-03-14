<?php
Configure::write('debug', 0);
//echo $_GET['callback'] . '(' . json_encode($articles) . ')'; 
for($i=0;$i<count($articles);$i++){
	$articles[$i]['Article']['description'] = strip_tags($articles[$i]['Article']['description']);
}
echo json_encode($articles);
?>