<?php
App::uses('AppModel', 'Model');

class Category extends AppModel {

	public $displayField = 'name';
	
	public $actsAs = array('Tree');

	public $validate = array(
		'name' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				'required' => false,
				'message' => 'This field is required'
			)
		),
		'slug' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This slug already exists',
				'required' => false,
				'allowEmpty' => true,
			)
		),
		'color' => array(
			'hexcolor' => array(
				'rule'    => '/^#([a-f0-9]{6})$/i',
				'message' => 'Color must be in HEX format, for example: #FFCC00',
				'allowEmpty' => false,
			)
		),
		'auto_import' => array(
			'rule' => array('boolean'),
			'required' => false,
        	'allowEmpty' => true,
		)
	);

	public $belongsTo = array(
		'ParentCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
			'conditions' => '',
			'fields' => '',
			'order' => 'Category.lft ASC'
		)
	);

	public $hasMany = array(
		'ChildCategory' => array(
			'className' => 'Category',
			'foreignKey' => 'parent_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		),
		'Article' => array(
			'className' => 'Article',
			'foreignKey' => 'category_id',
			'dependent' => true,
			'conditions' => '',
			'fields' => '',
			'order' => '',
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);
	
	public $hasOne = array(
		'Feed' => array(
			'className' => 'Feed',
			'dependent' => true
		)
	);
	
	public $hasAndBelongsToMany = array(
		'User' => array(
			'className' => 'User',
			'joinTable' => 'users_categories',
			'foreignKey' => 'category_id',
			'associationForeignKey' => 'user_id',
			'unique' => 'keepExisting',
		)
	);

	public function isOwnedBy($category, $user) {
		return $this->UsersCategory->field('category_id', array('category_id' => $category, 'user_id' => $user)) === $category;
	}

}
