<?php
Configure::write('debug', 0);
//echo $_GET['callback'] . '(' . json_encode($categories) . ')'; 
echo json_encode($categories);
//print_r($categories); 
?>