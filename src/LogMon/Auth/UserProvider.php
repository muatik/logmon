<?php
namespace Logmon\Auth;

use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use LogMon\Auth\User;

class UserProvider implements UserProviderInterface
{
	private $db;

	/**
	 * Constructor.
	 *
	 */
	public function __construct(\Doctrine\MongoDB\Database $db)
	{
		$this->db = $db;
	}

	/**
	 * {@inheritdoc}
	 */
	public function loadUserByUsername($email)
	{
		if (!$userRecord = $this->getUserRecord($email)) {
			throw new UsernameNotFoundException(sprintf('This user "%s" does not exist.', $email));
		}else
			$user = new User(
				$userRecord['email'],
				$userRecord['password'],
				explode(',', $userRecord['roles']), true, true, true, true
			);

		$user->setId($userRecord['_id']);

		return $user;
	}

	/**
	 * {@inheritDoc}
	 */
	public function refreshUser(UserInterface $user)
	{
		if (!$user instanceof User) {
			throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
		}
		
		return $user;
	}

	/**
	 * {@inheritDoc}
	 */
	public function supportsClass($class)
	{
		return $class === 'Gigablah\\Silex\\OAuth\\Security\\User\\User';
	}
	
	public function register($email, $password = null, $userInfo = null)
	{
		$user = array();
		
		if (!$email)
			throw new \Exception('Email is required.');
		
		if ($this->getUserRecord($email))
			throw new \Exception(sprintf('The email "%s" already exist.',$email));
		
		$user['email'] = $email;
		
		if (isset($userInfo))
			$user = array_merge($user,$userInfo);
		
		$user['createAt'] = time();
		$user['lastLoginAt'] = time();
		$user['roles'] = 'ROLE_USER';
		
		$user['ip'] = $_SERVER['REMOTE_ADDR'];

		if (isset($password)){
			$passwordEncoder = new MessageDigestPasswordEncoder();
			$user['password'] = $passwordEncoder->encodePassword($password,'logmon.app');
		}

		$this->db->users->insert($user);
		
		$userRecord = $this->getUserRecord($email);
		
		return array('_id' => $userRecord['_id'],'email' => $userRecord['email']);
	}
	
	public function getUsers()
	{
		$users = $this->db->users->find();
		$userList = array();
		foreach($users as $user)
			$userList[$user['email']]=$user;
			
		return $userList;
	}
	
	
	public function updateLastLoginAt($email)
	{
		$criteria = array('email' => $email);
		$user = $this->db->users->findOne($criteria);		
		$user['lastLoginAt'] = time();
		$user['ip'] = $_SERVER['REMOTE_ADDR'];	
		$this->db->users->save($user);
	}
	
	public function updateUserInfo($email, $userInfo)
	{
		$criteria = array('email' => $email);
		$user = $this->db->users->findOne($criteria);
		if (!$user) return false;

		foreach($userInfo as $i=>$k)
			$user[$i] = $k;
		
		$this->db->users->save($user);
	}
	
	public function getUserRecord($email)
	{
		$criteria = array('email' => $email);
		$userRecord = $this->db->users->findOne($criteria);
		if ($userRecord)
			return $userRecord;
		else
			return false;
	}
}
