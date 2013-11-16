<?php
App::uses('AppController', 'Controller');

class FeedreaderController extends AppController {

	public $components = array('RequestHandler');
	public $uses = array('Utility.Aggregator');

    public function index() {
    	//$categories = $this->Category->find('all', array("NOT" => array("Feed.url" => array(null, "")))); 

        $aggregatedfeeds = $this->Aggregator->find('all', array(
        	'conditions' => array(
        		'Zingarate' => 'http://www.zingarate.com/feed',
        		'Chefuturo' => 'http://www.chefuturo.it/feed'
        	)
        ));
        $this->set(compact('aggregatedfeeds', 'categories'));
    }
}
?>