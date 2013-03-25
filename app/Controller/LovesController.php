<?php
App::uses('AppController', 'Controller');
/**
 * Loves Controller
 *
 * @property Love $Love
 */
class LovesController extends AppController {

	public function beforeFilter() {
		$this->Auth->allow('add');
		parent::beforeFilter();
	}
	
	public function index() {
		$this->Love->recursive = 0;
		$this->set('loves', $this->paginate());
	}

	public function view($id = null) {
		if (!$this->Love->exists($id)) {
			throw new NotFoundException(__('Invalid love'));
		}
		$options = array('conditions' => array('Love.' . $this->Love->primaryKey => $id));
		$this->set('love', $this->Love->find('first', $options));
	}

	public function add() {
		if ($this->request->is('post')) {
			if($this->request->is('ajax')){
				$this->render(false);
				$this->Love->create();
				if ($this->request->data['BNsecretkey']=='buone2013'&&$this->Love->save($this->request->data)) {
					echo "ok";
				} else {
					echo "error";
				}
			}
			elseif($this->Session->check('Auth.User')){
				$this->Love->create();
				if ($this->Love->save($this->request->data)) {
					$this->Session->setFlash(__('The love has been saved'));
					$this->redirect(array('action' => 'index'));
				} else {
					$this->Session->setFlash(__('The love could not be saved. Please, try again.'));
				}
			}
			else{
				$this->Session->setFlash(__('You must login to add loves.'));
				$this->redirect(array('controller' => 'users', 'action' => 'login'));
			}
		}
		$articles = $this->Love->Article->find('list');
		$this->set(compact('articles'));
	}

	public function edit($id = null) {
		if (!$this->Love->exists($id)) {
			throw new NotFoundException(__('Invalid love'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Love->save($this->request->data)) {
				$this->Session->setFlash(__('The love has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The love could not be saved. Please, try again.'));
			}
		} else {
			$options = array('conditions' => array('Love.' . $this->Love->primaryKey => $id));
			$this->request->data = $this->Love->find('first', $options);
		}
		$articles = $this->Love->Article->find('list');
		$this->set(compact('articles'));
	}

	public function delete($id = null) {
		$this->Love->id = $id;
		if (!$this->Love->exists()) {
			throw new NotFoundException(__('Invalid love'));
		}
		$this->request->onlyAllow('post', 'delete');
		if ($this->Love->delete()) {
			$this->Session->setFlash(__('Love deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Love was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
