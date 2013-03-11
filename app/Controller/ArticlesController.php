<?php
App::uses('AppController', 'Controller');

class ArticlesController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		$articleCategoryId = $this->request->data['Article']['category_id'];
		if (isset($user['role']) && $user['role'] === 'admin')
			return true;
		elseif ($this->Article->Category->isOwnedBy($articleCategoryId, $this->Session->read('Auth.User.id')))
			return true;

		return parent::isAuthorized($user);
	}
	
	public function index() {
		$this->Article->recursive = 0;
		$this->set('articles', $this->paginate());
	}

	public function view($id = null) {
		if (!$this->Article->exists($id)) {
			throw new NotFoundException(__('Invalid article'));
		}
		$options = array('conditions' => array('Article.' . $this->Article->primaryKey => $id));
		$this->set('article', $this->Article->find('first', $options));
	}

	public function add() {
		if($this->request->data['Article']['confirm']){
			$this->Session->setFlash(__('Check article datas and submit the form.'));
		}
		else{
			if (($this->request->is('post') || $this->request->is('put'))) {
				$this->Article->create();
				if ($this->Article->save($this->request->data)) {
					$this->Session->setFlash(__('The article has been saved'));
					$this->redirect(array('action' => 'view', $this->Article->id));
				} else {
					$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
				}
			} else {
				//$options = array('conditions' => array('Article.' . $this->Article->primaryKey => $id));
				$this->request->data = array('Article' => $this->request->data['Articles']);
			}
		}
		
		$categories = $this->Article->Category->generateTreeList(null, null, null, ' - ');
		$this->set(compact('categories'));
	}

	public function edit($id = null) {
		if (!$this->Article->exists($id)) {
			throw new NotFoundException(__('Invalid article'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Article->save($this->request->data)) {
				$this->Session->setFlash(__('The article has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The article could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Article.' . $this->Article->primaryKey => $id));
			$this->request->data = $this->Article->find('first', $options);
		}
		$categories = $this->Article->Category->find('list');
		$this->set(compact('categories'));
	}

	public function delete($id = null) {
		$this->Article->id = $id;
		if (!$this->Article->exists()) {
			throw new NotFoundException(__('Invalid article'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Article->delete($id, true)) {
			$this->Session->setFlash(__('Article deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Article was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
