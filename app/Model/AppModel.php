<?php
/**
* Application model for Cake.
*
* This file is application-wide model file. You can put all
* application-wide model-related methods here.
*
* PHP 5
*
* CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
* Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
*
* Licensed under The MIT License
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright 2005-2012, Cake Software Foundation, Inc. (http://cakefoundation.org)
* @link          http://cakephp.org CakePHP(tm) Project
* @package       app.Model
* @since         CakePHP(tm) v 0.2.9
* @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
*/

App::uses('Model', 'Model');

/**
* Application model for Cake.
*
* Add your application-wide methods in the class below, your models
* will inherit them.
*
* @package       app.Model
*/
class AppModel extends Model {

	public function createSlug ($string, $id=null) {
		$slug = strtolower(Inflector::slug ($string,'-'));

		$i = 0;
		$params = array ();
		$params ['conditions']= array();
		$params ['conditions'][$this->name.'.slug']= $slug;
		if (!is_null($id)) {
			$params ['conditions']['not'] = array($this->name.'.id'=>$id);
		}
		while (count($this->find ('all',$params))) {
			if (!preg_match ('/-{1}[0-9]+$/', $slug )) {
				$slug .= '-' . ++$i;
			} else {
				$slug = preg_replace ('/[0-9]+$/', ++$i, $slug );
			}
			$params ['conditions'][$this->name . '.slug']= $slug;
		}
		return $slug;
	}
	
	public function generateSlug($string) {
			$slug = strtolower(Inflector::slug($string, '-'));
			$count = $this->find( 'count', array(
				'conditions' => array(
					$this->alias . ".slug REGEXP" => "^($slug)(-\d+)?"
				)
			));
			if($count > 0) return $slug . "_" . $count;
			else return $slug;
		}
}
