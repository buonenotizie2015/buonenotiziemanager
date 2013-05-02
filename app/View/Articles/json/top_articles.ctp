<?php
$toescape = array('%21'=>'!', '%2A'=>'*', '%27'=>"'", '%28'=>'(', '%29'=>')', '%3B'=>';', '%2C'=>',', '%2F'=>'/', '%3F'=>'?', '%3A'=>':', '%40'=>'@', '%26'=>'&', '%3D'=>'=', '%2B'=>'+', '%24'=>'$');
Configure::write('debug', 0);
for($i=0;$i<count($articles);$i++){
	$articles[$i]['Article']['description'] = strip_tags($articles[$i]['Article']['description']);
	$articles[$i]['Article']['image'] = strtr(rawurlencode($articles[$i]['Article']['image']), $toescape);
	$articles[$i]['Article']['link'] = strtr(rawurlencode($articles[$i]['Article']['link']), $toescape);
}
echo json_encode($articles); 
?>