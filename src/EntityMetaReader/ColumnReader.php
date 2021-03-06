<?php

namespace EntityMetaReader;

/**
 * Column reader.
 *
 * @copyright Copyright (c) 2013 Ledvinka Vít
 * @author Ledvinka Vít, frosty22 <ledvinka.vit@gmail.com>
 *
 */
class ColumnReader extends \Nette\Object implements \Iterator
{

	/**
	 * @var string
	 */
	private $entity;


	/**
	 * @var string
	 */
	private $name;


	/**
	 * @var string
	 */
	private $default;


	/**
	 * List of annotations objects
	 * @var array
	 */
	private $annotations = array();


	/**
	 * List of required annotations
	 * @var array
	 */
	private $requiredAnnotations = array();


	/**
	 * @param string $entity
	 * @param string $name
	 * @param array $annotations
	 * @param mixed $default
	 */
	public function __construct($entity, $name, array $annotations, $default = NULL)
	{
		$this->entity = $entity;
		$this->name = $name;
		$this->annotations = $annotations;
		$this->default = $default;
	}


	/**
	 * @return string
	 */
	public function getEntity()
	{
		return $this->entity;
	}


	/**
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}


	/**
	 * @return array
	 */
	public function getAnnotations()
	{
		return $this->annotations;
	}


	/**
	 * @return mixed
	 */
	public function getDefault()
	{
		return $this->default;
	}


	/**
	 * Check if is need assing property
	 * @return bool
	 */
	public function isNeedAssign()
	{
		return $this->getAnnotation('Doctrine\ORM\Mapping\GeneratedValue') === NULL &&
		$this->isNullable() === FALSE &&
		$this->getDefault() === NULL;
	}


	/**
	 * Get annotation
	 * @param string $type Class type of annotation
	 * @param boolean $required If is required, will be created new instance when not find
	 * @param array|string $arguments  Arguments for constructor of new mapping class
	 * @return mixed
	 */
	public function getAnnotation($type, $required = FALSE, $arguments = NULL)
	{
		foreach ($this->annotations as $annotation) {
			if ($annotation instanceof $type) return $annotation;
		}

		if ($required) {
			if (!isset($this->requiredAnnotations[$type])) {
				$this->requiredAnnotations[$type] = new $type(is_array($arguments) ? $arguments : array("value" => $arguments));
			}
			return $this->requiredAnnotations[$type];
		}

		return NULL;
	}


	/**
	 * Is value column type
	 * @return bool
	 */
	public function isValueType()
	{
		return $this->getAnnotation("Doctrine\ORM\Mapping\Column") ? TRUE : FALSE;
	}


	/**
	 * Is entity column type
	 * @return bool
	 */
	public function isEntityType()
	{
		foreach ($this->getAnnotations() as $annotation) {
			if ($annotation instanceof \Doctrine\ORM\Mapping\OneToOne ||
				$annotation instanceof \Doctrine\ORM\Mapping\ManyToOne)
				return TRUE;
		}
		return FALSE;
	}


	/**
	 * Is entity collection type
	 * @return bool
	 */
	public function isCollectionType()
	{
		foreach ($this->getAnnotations() as $annotation) {
			if ($annotation instanceof \Doctrine\ORM\Mapping\ManyToMany ||
				$annotation instanceof \Doctrine\ORM\Mapping\OneToMany)
				return TRUE;
		}
		return FALSE;
	}


	/**
	 * Get target entity
	 * @return null|string
	 */
	public function getTargetEntity()
	{
		foreach ($this->getAnnotations() as $annotation) {
			if ($annotation instanceof \Doctrine\ORM\Mapping\OneToOne ||
				$annotation instanceof \Doctrine\ORM\Mapping\ManyToOne ||
				$annotation instanceof \Doctrine\ORM\Mapping\OneToMany ||
				$annotation instanceof \Doctrine\ORM\Mapping\ManyToMany)
				return $annotation->targetEntity;
		}
		return NULL;
	}


	/**
	 * Is nullable
	 * @return bool
	 */
	public function isNullable()
	{
		if ($this->isValueType()) {
			$annotation = $this->getAnnotation('Doctrine\ORM\Mapping\Column');
			return $annotation->nullable;

		}

		$annotation = $this->getAnnotation('Doctrine\ORM\Mapping\JoinColumn');
		if ($annotation) {
			return $annotation->nullable;
		}

		return TRUE;
	}


	/*****************************************/
	/** ITERATOR INTERFACE METHODS */


	/**
	 * Rewind array to start
	 * @return mixed
	 */
	public function rewind() {
		return reset($this->annotations);
	}


	/**
	 * @return array
	 */
	public function current() {
		return current($this->annotations);
	}


	/**
	 * @return string
	 */
	public function key() {
		return key($this->annotations);
	}


	/**
	 * @return void
	 */
	public function next() {
		next($this->annotations);
	}


	/**
	 * @return bool
	 */
	public function valid() {
		return  key($this->annotations) !== NULL;
	}

}
