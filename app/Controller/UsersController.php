<?php
/**
 * Controller for Users
 * Uses users table is in the Fedora3 MySQL database
 * Version 1.0
 * Copyright 2011-2013 Stuart J. Chalk
 */

class UsersController extends AppController
{
	public function beforeFilter()
	{
		parent::beforeFilter();
		$this->Auth->allow('register','email','login','logout');
		$this->Auth->loginRedirect='/admin/dashboard';
		$this->Auth->logoutRedirect='/';
	}

	// User login
	public function login()
	{
		if($this->request->is('post'))
		{
			if($this->Auth->login())
			{
				return $this->redirect($this->Auth->redirectUrl());
			}
			$this->Flash->set(__('Invalid username or password, try again'));
		}
	}

	// User logout
	public function logout()
	{
		$this->redirect($this->Auth->logout());
	}

	// User register
	public function register()
	{
		if(!empty($this->data))
		{
			$this->User->save($this->data);
			$this->email($this->User->id,'UNF Preserve Registration','Thank you for registering for jafFedora.');
			$this->email('schalk@unf.edu','UNF Preserve Registration',$this->data['User']['username'].' has just registered');
			$this->redirect('/');
		}
	}

	// Send email
	public function email($id,$subject="jafFedora: CakePHP for Fedora",$message="")
	{
		if($message=="")	{ return; }
		$user=$this->User->find('first',array('conditions'=>array('id'=>$id)));
		$this->Email->smtpOptions = array('port'=>'25','timeout'=>'30','host'=>'mail.unf.edu');
		$this->Email->delivery = 'smtp';
		$this->Email->to = 'Stuart Chalk <schalk@unf.edu>';
		$this->Email->from = $user['User']['email'];
		$this->Email->subject = $subject;
		$this->Email->sendAs = 'text';
		$this->Email->send($message);
	    $this->set('smtp_errors', $this->Email->smtpError);
	}
}
?>