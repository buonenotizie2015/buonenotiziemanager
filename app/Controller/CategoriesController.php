<?php
App::uses('AppController', 'Controller');

class CategoriesController extends AppController {
	
	public $displayField = 'name';
	
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		if (in_array($this->action, array('edit', 'delete'))) {
			$categoryId = $this->request->params['pass'][0];
			if (isset($user['role']) && $user['role'] === 'admin')
				return true;
			elseif ($this->Category->isOwnedBy($categoryId, $this->Session->read('Auth.User.id')))
				return true;
			else{
				$this->Session->setFlash(__('You are not allowed to modify this category!'));
				return false;
			}
		}
		return parent::isAuthorized($user);
	}
	
	public function getCategories() {
		//$this->autoRender = false;
		$this->header('Content-type: application/javascript');
		//$this->response->type(array('jsonp' => 'application/javascript'));
		//$this->response->type('jsonp');
		$this->layout = 'ajax';
		$this->Category->unBindModel(array('hasMany' => array('Article'), 'hasOne' => array('Feed'), 'belongsTo' => array('ParentCategory'), 'hasAndBelongsToMany' => array('User')));
		$this->set('categories', $this->Category->find('threaded', array('recursive' => -1)));
		//$this->set('categories', $this->Category->generateTreeList(null, null, null, ' - '));
	}

	public function index() {
		$this->Category->recursive = 0;
		//$this->Category->find('threaded');
		//$categories = $this->Category->reorder(array('field' => 'name','order' => 'asc'));
		$this->set('categories', $this->paginate());
	}

	public function view($param = null) {
		if(empty($param))
			throw new NotFoundException(__('Wrong object request'));

		$category = $this->Category->find('first', array('conditions' => is_numeric($param) ? array('Category.id' => $param) : array('Category.slug' => $param)));

		if(empty($category))
			throw new NotFoundException(__('Object not found'));
			
		$this->set('category', $category);
		
		//Handling request Rss or Json
		/*$articles = $this->Category->Article->find('all',
			array(
				'limit' => 20,
				'conditions' => array('Article.category_id =' => $category['Category']['id']),
				'joins' => array(array('table' => 'categories',
					'alias' => 'ParentCategory',
					'type' => 'LEFT',
					'conditions' => array(
 						'ParentCategory.id = '.$category['Category']['parent_id'],
					),
					'fields' => 'ParentCategory.name'
				))
			)
		);*/
		$articles = $this->Category->Article->find('all',
			array(
				'limit' => 20,
				'conditions' => array('Article.category_id =' => $category['Category']['id']),
				'joins' => array(
					array(
						'table' => 'Categories',
						'alias' => 'ParentCategory',
						'type' => 'left',
						'conditions' => array('ParentCategory.id =' => $category['Category']['parent_id'] ),
					)
				),
				'fields' => array('Article.*', 'Category.*', 'ParentCategory.name')
			)
		);
		$this->set(compact('articles'));
	}
	
	public function news($param = null) {
		if(empty($param))
			throw new NotFoundException(__('Wrong object request'));

		$category = $this->Category->find('first', array('conditions' => is_numeric($param) ? array('Category.id' => $param) : array('Category.slug' => $param)));

		if(empty($category))
			throw new NotFoundException(__('Object not found'));

		$this->set('category', $category);
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Category->create();
			//set better array data
			$this->Category->validator()->remove('User');
			//set slug
			$parentName = $this->Category->findById($this->request->data['Category']['parent_id']);
			$parentSlug = isset($parentName['Category']) ? $parentName['Category']['name'].'_' : '';
			$this->request->data['Category']['slug'] = Inflector::slug(strtolower($parentSlug.$this->request->data['Category']['name']));
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash(__('The category has been saved'));
				$this->redirect(array('action' => 'view', $this->Category->id));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'));
			}
		}
		
		$parentCategories = $this->Category->ParentCategory->find('list', array('conditions' => array('ParentCategory.parent_id' => null)));
		$users = $this->Category->User->find('list', array('conditions' => array('User.role !=' => 'admin')));
		$feeds = $this->Category->Feed->find('list');
		$this->set(compact('parentCategories', 'users', 'feeds'));
	}

	public function edit($id = null) {
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			//set slug
			$parentName = $this->Category->findById($this->request->data['Category']['parent_id']);
			$parentName = isset($parentName) ? $parentName['Category']['name'].'_' : '';
			$this->request->data['Category']['slug'] = Inflector::slug(strtolower($parentName.$this->request->data['Category']['name']));
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash(__('The category has been saved'));
				$this->redirect(array('action' => 'view', $this->Category->id));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
			$this->request->data = $this->Category->find('first', $options);
		}

		$parentCategories = $this->Category->ParentCategory->find('list', array('conditions' => array('ParentCategory.parent_id' => null)));
		$feeds = $this->Category->Feed->find('list');
		$users = $this->Category->User->find('list', array('conditions' => array('User.role !=' => 'admin')));
		$selUsers = $this->Category->UsersCategory->find('list', array('conditions' => array('UsersCategory.category_id' => $id), 'fields' => array('user_id', 'user_id')));
		$selectedUsers = array();
		foreach($users as $userKey => $userValue){
			if(in_array($userKey, $selUsers))
				array_push($selectedUsers, $userKey);
		}
		$this->set(compact('parentCategories', 'users', 'feeds', 'selectedUsers'));
	}

	public function delete($id = null) {
		$this->Category->id = $id;
		if (!$this->Category->exists()) {
			throw new NotFoundException(__('Invalid category'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Category->delete()) {
			$this->Session->setFlash(__('Category deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Category was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
