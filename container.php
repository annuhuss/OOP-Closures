<?php

/**
* <container.php>
* Topics:  Use of Lambda functions, Closures and Shared instance for 
*		   simplifying code in a Container class 
* 
* @author Muhammad Anwar Hussain<anwar_hussain.01@yahoo.com>
* Created on: 7th June 2019
*/

trait Accessibility
{
	protected function grantAccess($userid, $facility)
	{
		echo "@ $userid - [ access to $facility ]:   Granted". '<br>';
	}

	protected function preventAccess($userid, $facility)
	{
		echo "@ $userid - [ access to $facility ]:   Denied". '<br>';
	}
	
	protected function invalidUser($userid, $facility)
	{
		echo "@ $userid - [ access to $facility ] : Invalid User!". '<br>';
	}
}

class User
{	
	use Accessibility;

	public function accessToFacility(Administration $admin, $facility, $user_id, $user_pass)
	{	
		[$user_testify, $user_status] = $admin->validateUserData($user_id, $user_pass);

		if ($user_testify)
		{ 
			if ($admin->checkFacility($user_status, $facility))
			{
				$this->grantAccess($user_id, $facility);
			}
			else 
			{
				$this->preventAccess($user_id, $facility);
			}
		}
		else
		{
			 $this->invalidUser($user_id, $facility);
		}
	}		 
}

class Administration 
{
	private $data = [];
	protected $facilities = array();
	
	public function setUserInfo(array $user_data = [])
	{
		$this->data[] = $user_data;
	}

	public function setFacility(array $facility)
	{
		$this->facilities[] =  $facility;
	}

	public function validateUserData($u_id, $u_pass)
	{ 
		$response = false;
		foreach($this->data as $u_data)
		{ 
			if ($u_data['userid'] == $u_id && $u_data['password'] == $u_pass)
			{
				$response = true;
				return [$response, $u_data['status']];			
			}		
		}
		
 		return $response;
	}

	public function checkFacility ($status, $facility)
	{ 
		foreach($this->facilities as $item)
		{	
			if ($item['fkey'] == $facility)
			{  
				if ($item['category'] == 'common' || $item['category'] == $status)
				{
 					return $item['fname'];
				}
				return false;
			}
		}
	}
}

class Container
{
	protected $items = [];

	function __set($key, $item)
	{
		$this->items[$key] = $item;
	}

 	function __get($key)
	{
		if (!isset($this->items[$key]))
    		{
      			throw new Exception(sprintf('Item "%s" is not defined.', $key));
    		}
		return is_callable($this->items[$key]) ? $this->items[$key]($this) : $this->items[$key];
	}

 	public function getInstance($callable)
	{    
    		return function () use ($callable)
    		{
			static $_instance;
      			if (is_null($_instance))
      			{
        			$_instance = $callable();
      			}
      			return $_instance;
   		 };
  	}
}


$c = new Container();

// for Administration
$c->admin = $c->getInstance(function(){return new Administration;});

// Administration instance
$admin = $c->admin;

// set the facilities by the admin
$admin->setFacility(array('fkey' => 'coffee', 'fname' => 'accessToCoffee', 'category' => 'common'));
$admin->setFacility( array('fkey' => 'copy', 'fname' => 'accessToPrinter', 'category' => 'common'));
$admin->setFacility(array('fkey' => 'table-tennis',  'fname' => 'accessToTableTennis', 'category' => 'student'));
$admin->setFacility(array('fkey' => 'long-tennis', 'fname' => 'accessToLongTennis', 'category' => 'teacher'));
$admin->setFacility(array('fkey' => 'library', 'fname' => 'accessToLibrary', 'category' => 'common'));
$admin->setFacility( array('fkey' => 'restroom', 'fname' => 'accessToRestroom', 'category' => 'teacher'));

// set the user's info by the admin
$admin->setUserInfo(array('userid'=>'s_name1', 'password'=>'s_password1', 'status'=>'student'));
$admin->setUserInfo(array('userid'=>'s_name2', 'password'=>'s_password2', 'status'=>'student'));

$admin->setUserInfo(array('userid'=>'t_name1', 'password'=>'t_password1', 'status'=>'teacher'));
$admin->setUserInfo(array('userid'=>'t_name2', 'password'=>'t_password2', 'status'=>'teacher'));


// for Users
$c->user = $c->getInstance(function(){return new User;});

// User instance for students
$s_user = $c->user;

// let's say $admin is your access-card by which a facility is accessible
$s_user->accessToFacility($admin, 'copy', 's_name1', 's_password1');
$s_user->accessToFacility($admin, 'coffee', 's_name2', 's_password2');
$s_user->accessToFacility($admin, 'copy', 's_name2', 's_password2');
$s_user->accessToFacility($admin, 'long-tennis', 's_name2', 's_password2');

// access by an unauthorized student with userid 's_name'
$s_user->accessToFacility($admin, 'coffee', 's_name', 's_password');


// User instance for teachers, which is infact the same as the students
$t_user = $c->user;

$t_user->accessToFacility($admin, 'coffee', 't_name1', 't_password1');
$t_user->accessToFacility($admin, 'copy', 't_name2', 't_password2');
$t_user->accessToFacility($admin, 'table-tennis', 't_name2', 't_password2');
$t_user->accessToFacility($admin, 'library', 't_name1', 't_password1');

// access by an unauthorized teacher with userid 't_name'
$t_user->accessToFacility($admin, 'restroom', 't_name', 't_password');

?>
