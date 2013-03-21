<?php
App::uses('AppController', 'Controller');

class ArticlesController extends AppController {
	
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		if (in_array($this->action, array('add', 'edit', 'delete'))) {
			$articleCategoryId = $this->request->data['Article']['category_id'];
			if (isset($user['role']) && $user['role'] === 'admin')
				return true;
			elseif ($this->Article->Category->isOwnedBy($articleCategoryId, $this->Session->read('Auth.User.id')))
				return true;
		}

		return parent::isAuthorized($user);
	}
	
	public function index() {
		/*TODO | BEST PRACTICE: send one xhr call form APP and articlescontroller respond with all articles in selected categories ordered by pubdate
		$articles = $this->Article->find('all',
			array(
				'limit' => 20,
				//'conditions' => array('Article.category_id =' => $article['Category']['id']),
				'order' => array('Article.pubDate ASC'),
				'recursive' => 0,
				'fields' => array('Article.*', 'Category.*', 'ParentCategory.name'),
				'joins' => array(
					array(
						'table' => 'Categories',
						'alias' => 'Category',
						'type' => 'left',
						'conditions' => array('Article.category_id = Category.id')
					),
					array(
						'table' => 'Categories',
						'alias' => 'ParentCategory',
						'type' => 'left',
						'conditions' => array('ParentCategory.id = Category.parent_id')// => $article['Category']['parent_id'] ),
					)
				),
			)
		);
		*/
		
		$this->Article->recursive = 0;
		$this->set('articles', $this->paginate());
	}
	
	public function topArticles(){
		$this->header('Content-type: application/javascript');
		$this->layout = 'ajax';
		$this->Article->unbindModel(array('belongsTo' => array('Category')));
		$articles = $this->Article->find('all', array(
			'limit' => 10,
			'order' => array('Article.love_count' => 'desc', 'Article.pudBate' => 'desc'),
			'joins' => array(
				array(
					'table' => 'Categories',
					'alias' => 'Category',
					'type' => 'left',
					'conditions' => array('Category.id = Article.category_id' ),
				),
				array(
					'table' => 'Categories',
					'alias' => 'ParentCategory',
					'type' => 'left',
					'conditions' => array('ParentCategory.id = Category.parent_id' ),
				)
			),
			'fields' => array('Article.*', 'Category.*', 'ParentCategory.name')
			));
		$this->set('articles', $articles);
	}

	public function view($id = null) {
		if (!$this->Article->exists($id)) {
			throw new NotFoundException(__('Invalid article'));
		}
		$options = array('conditions' => array('Article.' . $this->Article->primaryKey => $id));
		$this->set('article', $this->Article->find('first', $options));
	}

	public function add() {
		if(isset($this->request->data['Article']['confirm'])){
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
