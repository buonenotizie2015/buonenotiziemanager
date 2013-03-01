<?php
App::uses('AppModel', 'Model');
/**
 * Feed Model
 *
 */
class Feed extends AppModel {
	
	public $displayField = 'name';
	
	public $belongsTo = array(
		'Category' => array(
			'className' => 'Category',
			'foreignKey' => 'category_id',
			'conditions' => '',
			'fields' => '',
			'order' => ''
		)
	);

}
