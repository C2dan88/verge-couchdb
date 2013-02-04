<?php

class User extends Base
{
	protected $name;
	protected $email;
	protected $full_name;
	protected $salt;
	protected $password_sha;
	protected $roles;

	
	public function __construct()
	{
		parent::__construct('user');
	}

	public function signup($username, $password)
	{
		$bones = new Bones();
		$bones->couch->setDatabase('_users');
		$bones->couch->login(ADMIN_USER, ADMIN_PASSWORD);

		$this->roles = array();
		$this->name  = preg_replace('/[^a-z0-9-]/', '', strtolower($username));
		$this->_id   = 'org.couchdb.user:' . $this->name;
		                      // get a unique uuid from couchDB. We'll use this for the password salt
		$this->salt         = $bones->couch->generateIDs(1)->body->uuids[0];
		$this->password_sha = sha1($password . $this->salt);

		try
		{
			$bones->couch->put($this->_id, $this->to_json());
		}
		catch(SagCouchException $e)
		{
			if($e->getCode() == '409')
			{
				$bones->set('error', 'A user with this name already existis!');
				$bones->render('user/signup');
				exit;
			}
		}
	}

	public function login($password)
	{
		$bones = new bones();
		$bones->couch->setDatabase('_users');

		if($this->name == '' || $password == '')
		{
			$bones->set('error', 'Please provide valid login credentials.');
			$bones->render('user/login');
			exit;
		}

		try
		{
			$bones->couch->login($this->name, $password, Sag::$AUTH_COOKIE);
			@session_start();
			$_SESSION['username'] = $bones->couch->getSession()->body->userCtx->name;
			session_write_close();
		}
		catch (SagCouchException $e)
		{
			if($e->getCode() == '401')
			{
				$bones->set('error', 'Incorrect login credentials.' /**. 
											'<p>' . $e->getMessage() . '</p>' .
											'<p>[' . $this->name . ', ' . $password . ']</p>' .
											'<p>' . $bf . '</p>' .
											'<pre>' . print_r(['POST' => $_POST, 
																'GET' => $_GET], true)/**/);
				$bones->render('user/login');
				exit;
			}
		}
	}

	public static function logout()
	{
		
		$bones = new bones();
		$bones->couch->login(null, null);
		@session_start();
		session_destroy();
	}

	public function current_user()
	{
		@session_start();
		return (isset($_SESSION['username']) && !empty($_SESSION['username'])) ? $_SESSION['username'] : false;
		session_write_close();
	}

	public static function is_authenticated()
	{
		if(self::current_user())
			return true;

		return false;
	}

	public static function get_by_username($username = null)
	{
		$bones = new Bones();
		$bones->couch->login(ADMIN_USER, ADMIN_PASSWORD);
		$bones->couch->setDatabase('_users');

		$user = new User();
		$document = $bones->couch->get('org.couchdb.user:' . $username)->body;
		$user->_id       =  $document->_id;
		$user->name      =  $document->name;
		$user->email     =  $document->email;
		$user->full_name =  $document->full_name;

		return $user;
	}
}