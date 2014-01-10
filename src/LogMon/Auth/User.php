<?php
namespace Logmon\Auth;

use Symfony\Component\Security\Core\User\AdvancedUserInterface;

class User implements AdvancedUserInterface
{
	private $id;
	private $password;
	private $email;
	private $enabled;
	private $accountNonExpired;
	private $credentialsNonExpired;
	private $accountNonLocked;
	private $roles;

	public function __construct($email, $password, array $roles = array(), 
		$enabled = true, $userNonExpired = true, $credentialsNonExpired = true, $userNonLocked = true)
	{
		if (empty($email)) {
			throw new \InvalidArgumentException('The email cannot be empty.');
		}
		
		$this->password = $password;
		$this->email = $email;
		$this->enabled = $enabled;
		$this->accountNonExpired = $userNonExpired;
		$this->credentialsNonExpired = $credentialsNonExpired;
		$this->accountNonLocked = $userNonLocked;
		$this->roles = $roles;
	}

	/**
	 * Gets the user email.
	 *
	 * @return string
	 */
	public function getEmail()
	{
		return $this->email;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getRoles()
	{
		return $this->roles;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * {@inheritdoc}
	 */
	public function getSalt()
	{
		return 'logmon.app';
	}

	/**
	 * {@inheritdoc}
	 */
	public function getUsername()
	{
		return null;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isAccountNonExpired()
	{
		return $this->accountNonExpired;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isAccountNonLocked()
	{
		return $this->accountNonLocked;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isCredentialsNonExpired()
	{
		return $this->credentialsNonExpired;
	}

	/**
	 * {@inheritdoc}
	 */
	public function isEnabled()
	{
		return $this->enabled;
	}

	/**
	 * {@inheritdoc}
	 */
	public function eraseCredentials()
	{
	}

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}
}
