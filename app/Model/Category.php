<?php
App::uses('AppModel', 'Model');

class Category extends AppModel {
	
	public $displayField = 'name';
	
	public $actsAs = array('Tree');

	public $validate = array(
		/*'slug' => array(
			'isUnique' => array(
				'rule' => 'isUnique',
				'message' => 'This slug already exists.'
			)
		),
		*/
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
			'className'    => 'Feed',
			'dependent'    => true
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
	
	public function beforeSave($data){
		foreach (array_keys($this->hasAndBelongsToMany) as $model){
			if(isset($data[$this->name][$model])){
				$data[$model][$model] = $data[$this->name][$model];
				unset($data[$this->name][$model]);
			}
		}
		return $data;
	}
	
	public function isOwnedBy($category, $user) {
		return $this->UsersCategory->field('category_id', array('category_id' => $category, 'user_id' => $user)) === $category;
	}

}
