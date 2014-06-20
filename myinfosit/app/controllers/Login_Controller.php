<?php

//APP::import("Controller","NoModel");

App::import('Controller', "NoModelController",false);

//require "NoModel_Controller.php";

class LoginController extends AppController implements NoModelController
{
	//var $name = 'User';
	//var $name = 'User';
	//var $helpers = array('Html', 'Form');
	/*function register()
	 {
	if (!empty($this->data))
	{
	if ($this->User->save($this->data))
	{
	$this->Session->setFlash('Your registration information was accepted.');
	}
	}
	}
	function knownusers()
	{
	$this->set('knownusers', $this->User->findAll(null, array('id', 'username', 'first_name','last_name'), 'id DESC'));
	}*/
	function index() {
		echo var_dump($_SESSION);
	}


}
