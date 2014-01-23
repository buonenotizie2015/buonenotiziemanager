<?php
App::uses('AppController', 'Controller');
App::uses('RssStalkerLib', 'Lib');

class CategoriesController extends AppController {
	
	public $displayField = 'name';
	public $helper = array('RssStalker', 'Js');
	public $components = array('RequestHandler');
	var $uses = array('Category', 'Article');
	
	public function beforeFilter() {
		parent::beforeFilter();
	}

	public function isAuthorized($user) {
		if (in_array($this->action, array('edit', 'delete'))) {
			$categoryId = $this->request->params['pass'][0];
			if (isset($user['role']) && $user['role'] === 'admin')
				return true;
			elseif ($this->Category->isOwnedBy($categoryId, $this->Session->read('Auth.User.id'))&&$this->action=='edit')
				return true;
			elseif ($this->action=='delete' && $user['role']=='author'){
				$this->Session->setFlash(__('You are not allowed to delete any category!'));
				return false;
			}
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
		$this->paginate = array('limit' => $this->Category->find('count'));
		$this->set('categories', $this->paginate());
	}
	
	public function repair() {
		$this->Category->recover();
		$this->redirect(array('action' => 'index'));
	}

	public function view($param = null) {
		if(empty($param))
			throw new NotFoundException(__('Wrong object request'));

		$category = $this->Category->find('first', array('conditions' => is_numeric($param) ? array('Category.id' => $param) : array('Category.slug' => $param)));

		if(empty($category))
			throw new NotFoundException(__('Object not found'));
			
		$this->set('category', $category);
		
		$articles = $this->Category->Article->find('all',
			array(
				'limit' => 0,
				'conditions' => array('Article.category_id =' => $category['Category']['id']),
				'order' => array('Article.pubDate DESC'),
				'joins' => array(
					array(
						'table' => 'Categories',
						'alias' => 'ParentCategory',
						'type' => 'left',
						'conditions' => array('ParentCategory.id =' => $category['Category']['parent_id'] ),
					)
				),
				'fields' => array('Article.*', 'Category.*', 'ParentCategory.name, ParentCategory.color')
			)
		);
		
		$this->set(compact('articles'));
	}
	
	public function getFeed() {
		$this->layout = false;
		if(isset($this->data['feedUrl'])){
			$xmlFeed = Xml::build($this->data['feedUrl']);
			$feedData = Set::reverse($xmlFeed);
			$channel['channel'] = $feedData['rss']['channel'];
			$this->set('channel', $channel);
			
			$articles = $this->Category->Article->find('all',
				array(
					'limit' => 0,
					'conditions' => array('Article.category_id =' => $this->data['categoryID']),
					'order' => array('Article.pubDate DESC'),
					'fields' => array('Article.*')
				)
			);
			$this->set(compact('articles'));
			
			$this->set('categoryid', $this->data['categoryID']);
		}
	}

	public function importCategoryArticles($id = null) {
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		$options = array('conditions' => array('Category.' . $this->Category->primaryKey => $id));
		$category =  $this->Category->find('first', $options);

		$this->set('category', $category);

		if (!empty($category['Feed']['id'])){
			$xmlFeed = Xml::build($category['Feed']['url']);
	        $feedData = Set::reverse($xmlFeed);

	        //IS RSS OR ATOM?
	        $channel = isset($feedData['rss']) ? $feedData['rss']['channel']['item'] : $feedData['feed']['entry'];

	        $insertedArticles = $this->Category->Article->find('all',
				array(
					'limit' => 0,
					'conditions' => array('Article.category_id =' => $category['Category']['id']),
					'order' => array('Article.pubDate DESC'),
					'joins' => array(
						array(
							'table' => 'Categories',
							'alias' => 'ParentCategory',
							'type' => 'left',
							'conditions' => array('ParentCategory.id =' => $category['Category']['parent_id'] ),
						)
					),
					'fields' => array('Article.*', 'Category.*', 'ParentCategory.name, ParentCategory.color')
				)
			);

	        $savedArticles = array();
	        $unsavedArticles = array();

	        $RssStalkerLib = new RssStalkerLib();

	        foreach ($channel as $itemField){
	        	$article['title'] = $itemField['title'];
                //$itemLink = is_array($itemField['link']) ? $itemField['link']['@'] : $itemField['link'];
                
                $article['link'] = $RssStalkerLib->findLink($itemField['link'], null);
                $article['image'] = $RssStalkerLib->findImages($itemField, null);

                if(isset($feedData['rss'])){
                    //RSS structure
                    $article['pubDate'] = isset($itemField['pubDate']) ? date('Y-m-d H:i:s', strtotime($itemField['pubDate'])) : date('Y-m-d H:i:s');
                    $article['description'] = isset($itemField['description']) ? html_entity_decode(strip_tags($itemField['description']), ENT_QUOTES, '') : 'Descrizione non disponibile';

                } else {
                    //ATOM STRUCTURE
                    $article['pubDate'] = isset($itemField['updated']) ? date('Y-m-d H:i:s', strtotime($itemField['updated'])) : date('Y-m-d H:i:s');
                    $article['description'] = isset($itemField['summary']) ? html_entity_decode(strip_tags($itemField['summary']['@']), ENT_QUOTES, '') : 'Descrizione non disponibile';

                }

                $article['category_id'] = $category['Category']['id'];
                
                $article['inserted'] = false;
                foreach($insertedArticles as $insertedArticle){
                    if($insertedArticle['Article']['link']==$article['link']){
                        $article['inserted'] = true;
                        break;
                    }
                }
                if(!$article['inserted']){
					$this->Article->create();
                    $this->Article->save($article);
                    array_push($savedArticles, $article);
                }
                else {
                	array_push($unsavedArticles, $article);
                }

	        }

    	}

    	$this->set('savedArticles', $savedArticles);
    	$this->set('unsavedArticles', $unsavedArticles);
        
	}

	public function importAllArticles(){
		 $categories = $this->Category->find('all', array(
		 	'conditions' => array('Category.auto_import' => 1, 'Category.parent_id !=' => null)
		 ));

		 $this->set('categories', $categories);
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
		$parentCategories = $this->Category->ParentCategory->find('list', array('conditions' => array('ParentCategory.parent_id' => null)));
		$parentColors = $this->Category->ParentCategory->find('all', array('conditions' => array('ParentCategory.parent_id' => null), 'recursive' => -1));
		$users = $this->Category->User->find('list', array('conditions' => array('User.role !=' => 'admin')));
		$feeds = $this->Category->Feed->find('list');
		$this->set(compact('parentCategories', 'users', 'feeds', 'parentColors'));
		
		if ($this->request->is('post')) {
			$this->Category->create();
			//set better array data
			$this->Category->validator()->remove('User');
			
			$parentName = $this->Category->findById($this->request->data['Category']['parent_id']);
			
			//set slug
			if(empty($this->request->data['Category']['slug'])){
				$parentSlug = isset($parentName['Category']) ? $parentName['Category']['name'].'-' : '';
				$this->request->data['Category']['slug'] = $this->Category->createSlug($parentSlug.$this->request->data['Category']['name']);
			}

			//set feed
			if(!empty($this->request->data['Feed']['url'])){
				$parentHead = isset($parentName['Category']) ? $parentName['Category']['name'].' ' : '';
				$this->request->data['Feed']['name'] = $parentHead.$this->request->data['Category']['name'];
				//$this->request->data['Feed']['category_id'] = $this->request->data['Category']['id'];
			} else {
				$this->Category->validator()->remove('Feed');
			}
			
			if ($this->Category->saveAll($this->request->data)) {
				$this->Session->setFlash(__('The category has been saved'));
				$this->redirect(array('action' => 'view', $this->Category->id));
			} else {
				$this->Session->setFlash(__('The category could not be saved. Please, try again.'));
			}
		}
	}

	public function edit($id = null) {
		if (!$this->Category->exists($id)) {
			throw new NotFoundException(__('Invalid category'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			
			//set slug
			$parentCat = $this->Category->findById($this->request->data['Category']['parent_id']);
			$parentSlug = isset($parentCat['Category']) ? $parentCat['Category']['name'].'-' : '';
			$this->request->data['Category']['slug'] = $this->Category->createSlug($parentSlug.$this->request->data['Category']['name'], $id);

			//check feed url
			if(empty($this->request->data['Feed']['url'])){
				$this->request->data = Set::remove($this->request->data, 'Feed');
			}

			//check color
			if(empty($this->request->data['Category']['color'])&&!empty($this->request->data['Category']['parent_id'])){
				$this->Category->validator()->remove('color');
				$this->request->data['Category']['color'] = $parentCat['Category']['color'];
			}
			
			if ($this->Category->saveAll($this->request->data)) {
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
		$category = $this->Category->find('first', array('conditions' => array('Category.id' => $id)));
		$parentColor = $this->Category->find('first', array('conditions' => array('Category.id' => $category['Category']['parent_id']), 'recursive' => -1));
		$this->set(compact('parentCategories', 'users', 'feeds', 'selectedUsers', 'parentColor'));
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
