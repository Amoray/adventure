<?php

namespace Navigation\Menu;

/**
* Menu
*/
class Menu
{
	private		$children			= array();
	private		$permission			= array();
	private		$inheritPermmission	= array();
	private		$class				= array();
	private		$inheritClass		= array();
	private		$depth				= -1;

	static function make()
	{
		return new Menu();
	}

	static function child($index, $name)
	{
		$item = new Menu();

		$item->index($index);
		$item->name($name);

		return $item;
	}

	public function find($index)
	{
		foreach ($children as $child) 
		{
			$found = $child->find($index);
			if ($found instanceof Menu) 
			{
				return $found;
			}
		}
		return false;
	}

	public function addChildren()
	{
		$children = func_get_args();
		foreach ($children as $value) 
		{
			$this->addChild($value);
		}

		return $this;
	}

	public function addChild(Menu $item)
	{
		$item->setDepth($this->depth + 1);
		$item->setInheritClass($this->inheritClass);
		$item->setInheritPermmission($this->inheritPermmission);

		$this->children[] = $item;

		return $this;
	}

	public function addPermission($name, $perm)
	{
		if (array_key_exists($name, $this->permission)) 
		{
			throw new \Exception("Permission already exists, cannot overwrite.");
		}
		else
		{
			$this->permission[$name] = $perm;
		}

		return $this;
	}

	public function addClass($class)
	{
		$this->class[] = $class;

		return $this;
	}

	public function setDepth($depth)
	{
		$this->depth = $depth;

		return $this;
	}

	public function setInheritClass(array $classes)
	{
		$this->inheritClass = $classes;

		foreach ($classes as $class) 
		{
			$this->addClass($class);
		}

		return $this;
	}

	public function inheritClass($class)
	{
		$this->inheritClass[] = $class;

		return $this;
	}

	public function setInheritPermmission(array $permissions)
	{
		$this->inheritPermmission = $permissions;

		foreach ($permissions as $name => $permission) 
		{
			$this->addPermission($name, $permission);
		}

		return $this;
	}

	public function inheritPermmission($name, $perm)
	{
		if (array_key_exists($name, $this->inheritPermmission)) 
		{
			throw new \Exception("Permission already exists, cannot overwrite.");
		}
		else
		{
			$this->inheritPermmission[$name] = $perm;
		}

		return $this;
	}

}

?>