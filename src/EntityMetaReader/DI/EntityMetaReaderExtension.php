<?php

namespace EntityMetaReader\DI;

use Nette\Config\CompilerExtension;

/**
 * Entity meta reader installer - extension
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class EntityMetaReaderExtension extends CompilerExtension {


	/**
	 * @var array
	 */
	private $defaults = array();


	public function loadConfiguration()
	{
		$config = $this->getConfig($this->defaults);
		$builder = $this->getContainerBuilder();

		$entityMetaReader = $builder->addDefinition($this->prefix('entityReader'))
			->setClass('EntityMetaReader\EntityReader');


	}


}