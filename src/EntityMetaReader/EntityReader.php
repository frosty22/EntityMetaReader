<?php

namespace EntityMetaReader;

/**
 * Entity meta-data annotations reader
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class EntityReader extends \Nette\Object
{

	/**
	 * @var \Doctrine\Common\Annotations\Reader
	 */
	private $reader;


	/**
	 * @param \Doctrine\Common\Annotations\Reader $reader
	 */
	public function __construct(\Doctrine\Common\Annotations\Reader $reader)
	{
		$this->reader = $reader;
	}


	/**
	 * Get list of entity columns and annotations
	 * @param string $entity
	 * @return ColumnReader[]
	 */
	public function getEntityColumns($entity)
	{
		$reflection = new \ReflectionClass($entity);
		$properties = $reflection->getProperties();

		$values = $reflection->getDefaultProperties();

		$columns = array();
		foreach ($properties as $property) {
			$annotations = $this->reader->getPropertyAnnotations($property);
			foreach ($annotations as $annotation) {
				if ($annotation instanceof \Doctrine\ORM\Mapping\Annotation) {
					$columns[$property->getName()] = new ColumnReader($entity, $property->getName(),
														$annotations, $values[$property->getName()]);
					break;
				}
			}
		}

		return $columns;
	}


}
