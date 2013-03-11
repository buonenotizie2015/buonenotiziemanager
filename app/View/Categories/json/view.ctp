<?php
Configure::write('debug', 0);
echo $_GET['callback'] . '(' . json_encode($articles) . ')'; 
?>