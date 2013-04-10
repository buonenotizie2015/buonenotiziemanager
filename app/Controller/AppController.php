<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

App::uses('Controller', 'Controller');
App::import('Xml');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	
	public $helpers = array('BootstrapCake.Bootstrap', 'Text'); 
	
	public $components = array(
		'Session',
		'Auth' => array(
			'loginRedirect' => array('controller' => 'pages', 'action' => 'home'),
			'logoutRedirect' => array('controller' => 'pages', 'action' => 'display', 'home'),
			'authorize' => array('Controller')
		),
		'RequestHandler'
	);
	
	public function beforeFilter() {
		$this->Auth->allow('login', 'getArticles', 'getCategories', 'topArticles');
			
		if($this->Auth->user()){
			if (isset($user['role']) && $user['role'] === 'admin')
				$this->Auth->allow();
			else{
				$this->Auth->deny();
				$this->Auth->allow('index', 'view', 'display', 'logout');
			}
		}
	}
	
	public function isAuthorized($user) {
		if (isset($user['role']) && $user['role'] === 'admin') {
			return true;
		}
		$this->Session->setFlash(__("You do not have enough privileges for this operation."));
		return false;
	}
	


}
