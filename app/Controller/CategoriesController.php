<?php
App::uses('AppController', 'Controller');
/**
 * Categories Controller
 *
 * @property Category $Category
 */
class CategoriesController extends AppController {
	
	public $displayField = 'name';
	
	public function beforeFilter() {
		parent::beforeFilter();
		
		if($this->Auth->user()){
			if (isset($user['role']) && $user['role'] === 'admin')
				$this->Auth->allow();
			else
				$this->Auth->allow('index', 'view');
		}
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

	public function index() {
		echo '<pre>';
		print_r($this->params->url);
		echo '</pre>';
		//test check if request .rss extension
		if ($this->RequestHandler->isRss() ) {
			$categories = $this->Category->find('all', array('limit' => 20));
			return $this->set(compact('categories'));
		}
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

		/*OLD CODE
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		$options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
		$this->set('category', $this->Category->find('first', $options));
		*/
	}

	public function add() {
		if ($this->request->is('post')) {
			$this->Category->create();
			//set better array data
			$this->Category->validator()->remove('User');
			//set slug
			$this->request->data['Category']['slug'] = Inflector::slug(strtolower($this->request->data['Category']['name']));
			if ($this->Category->save($this->request->data)) {
				$this->Session->setFlash(__('The category has been saved'));
				$this->redirect(array('action' => 'view', $this->Category->id));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'));
			}
		}
		$parentCategories = $this->Category->ParentCategory->find('list');
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
			$this->request->data['Category']['slug'] = Inflector::slug(strtolower($this->request->data['Category']['name']));
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
		$parentCategories = $this->Category->ParentCategory->find('list');
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
