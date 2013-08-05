<?php

namespace EntityMetaReader\Mapping;

/**
 * Base name mapping annotation
 *
 * @Annotation
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class Name implements \EntityMetaReader\Annotation
{

	/**
	 * @var string
	 */
	private $name = "";


	/**
	 * @param array $args
	 */
	public function __construct(array $args)
	{
		$this->name = $args["value"];
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return string
	 */
	public function __toString()
	{
		return $this->getName();
	}

}
