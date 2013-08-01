<?php

namespace EntityMetaReader\Mapping;
use Nette\Security\User;

/**
 * Access mapping annotation
 *
 * @Annotation
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Access implements \EntityMetaReader\Annotation
{

	const ALL = TRUE;
	const NONE = FALSE;


	/**
	 * @var array|bool
	 */
	private $read;


	/**
	 * @var array|bool
	 */
	private $write;


	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$this->read = isset($args["read"]) ? $this->parseRoles($args["read"]) : self::ALL;
		$this->write = isset($args["write"]) ? $this->parseRoles($args["write"]) : self::ALL;
	}


	/**
	 * Can role read
	 * @param string $role
	 * @return bool
	 */
	public function isReadable($role = "")
	{
		return $this->read === TRUE ? TRUE : isset($this->read[strtolower($role)]);
	}


	/**
	 * Can role write
	 * @param string $role
	 * @return bool
	 */
	public function isWritable($role = "")
	{
		return $this->write === TRUE ? TRUE : isset($this->write[strtolower($role)]);
	}


	/**
	 * Write access for user
	 * @param User $user
	 * @return bool
	 */
	public function checkWriteAccess(User $user)
	{
		foreach ($user->getRoles() as $role) {
			if ($this->isWritable($role)) return TRUE;
		}

		return FALSE;
	}


	/**
	 * Read access for user
	 * @param User $user
	 * @return bool
	 */
	public function checkReadAccess(User $user)
	{
		foreach ($user->getRoles() as $role) {
			if ($this->isReadable($role)) return TRUE;
		}

		return FALSE;
	}


	/**
	 * @param mixed $roles
	 * @return array
	 */
	protected function parseRoles($roles)
	{
		return is_bool($roles) ? $roles : (array_flip(array_map(function($item){ return strtolower($item); },
												is_array($roles) ? $roles : Explode(" ", $roles))
		));
	}



}
