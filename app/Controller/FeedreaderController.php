<?php
App::uses('AppController', 'Controller');

class FeedreaderController extends AppController {

	public $components = array('RequestHandler');
	public $uses = array('Utility.Aggregator');

    public function index() {
        $aggregatedfeeds = $this->Aggregator->find('all', array(
        	'conditions' => array(
        		'Zingarate' => 'http://www.zingarate.com/feed',
        		'Chefuturo' => 'http://www.chefuturo.it/feed'
        	)
        ));
        $this->set('aggregatedfeeds', $aggregatedfeeds);
    }
}
?>