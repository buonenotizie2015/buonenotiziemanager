<?php

App::uses('AppHelper', 'View/Helper');

class RssStalkerHelper extends AppHelper {
	
	public function findImages($item) {
		
		$allowedExt = array('jpg', 'jpeg', 'png');
		
		foreach($item as $key => $value){
			if(is_array($value)){
				$this->findImages($value);
			}
			else{
				if(in_array(substr(strrchr($value,'.'),1), $allowedExt)){
					$this->imagelink = $value;
				}
			}
		}
		return $this->imagelink;
    }
}

?>