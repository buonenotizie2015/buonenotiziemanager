<?php
App::uses('AppController', 'Controller');

class FeedsController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
	}
	
	public function isAuthorized($user) {
		
		//$feedCategoryId = $this->request->data['Feed']['category_id'];
		if (isset($user['role']) && $user['role'] === 'admin')
			return true;
		elseif ($this->Feed->Category->isOwnedBy($articleCategoryId, $this->Session->read('Auth.User.id')))
			return true;

		return parent::isAuthorized($user);
	}

	public function index() {
		$this->Feed->recursive = 0;
		$this->set('feeds', $this->paginate());
	}
	
	public function view($id = null) {
		if (!$this->Feed->exists($id)) {
			throw new NotFoundException(__('Invalid feed'));
		}
		$options = array('conditions' => array('Feed.' . $this->Feed->primaryKey => $id));
		$this->set('feed', $this->Feed->find('first', $options));
	}

	public function add() {
		//$this->Feed->set('categories', $this->Feed->Category->find('list')); 
		if ($this->request->is('post')) {
			$this->Feed->create();
			if ($this->Feed->save($this->request->data)) {
				$this->Session->setFlash(__('The feed has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feed could not be saved. Please, try again.'));
			}
		}
		//$categories = $this->Feed->Category->find('list', array('fields' => array('Category.id', 'Category.name', 'Category.parent_id')));
		$categories = $this->Feed->Category->generateTreeList(null, null, null, ' - ');
		$this->set(compact('categories'));
	}

	public function edit($id = null) {
		if (!$this->Feed->exists($id)) {
			throw new NotFoundException(__('Invalid feed'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Feed->save($this->request->data)) {
				$this->Session->setFlash(__('The feed has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The feed could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Feed.' . $this->Feed->primaryKey => $id));
			$this->request->data = $this->Feed->find('first', $options);
		}
		//$categories = $this->Feed->Category->find('list');
		$categories = $this->Feed->Category->generateTreeList(null, null, null, ' - ');
		$this->set(compact('categories'));
	}

	public function delete($id = null) {
		$this->Feed->id = $id;
		if (!$this->Feed->exists()) {
			throw new NotFoundException(__('Invalid feed'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Feed->delete()) {
			$this->Session->setFlash(__('Feed deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Feed was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
