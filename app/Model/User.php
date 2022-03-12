<?php
App::uses('AppModel', 'Model');
App::uses('SimplePasswordHasher', 'Controller/Component/Auth');

/**
 * Basic CakePHP Model to connect to the users table
 * Version: 2.0 (11/05/18)
 * Copyright 2011-2018 Stuart J. Chalk
 */
class User extends AppModel
{
	public $validate = array(
		'username' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A username is required'
			)
		),
		'password' => array(
			'required' => array(
				'rule' => array('notEmpty'),
				'message' => 'A password is required'
			)
		),
		'position' => array(
			'valid' => array(
				'rule' => array('inList', array('admin', 'teacher','student')),
				'message' => 'Please enter a valid role',
				'allowEmpty' => false
			)
		)
	);
	
	public function beforeSave($options = array())
	{
		if (isset($this->data[$this->alias]['password']))
		{
			$passwordHasher = new SimplePasswordHasher();
			$this->data[$this->alias]['password'] = $passwordHasher->hash($this->data[$this->alias]['password']);
		}
		return true;
	}
}