<?php

namespace Navigation;

use Template\Template AS Template;

/**
* Menu
*/
class Menu
{
	private		$route				= null;
	private		$name				= null;
	private		$prepend			= null;

	private		$children			= array();
	private		$permission			= array();
	private		$inheritPermission	= array();
	private		$class				= array();
	private		$inheritClass		= array();
	private		$depth				= 0;


	static function make($route = null, $name = null)
	{
		$menu = new Menu();

		if (!is_null($route)) 
		{
			$menu->route = $route;
		}

		if (!is_null($name)) 
		{
			$menu->name = $name;
		}

		return $menu;
	}

	public function add($items)
	{
		if (is_array($items)) 
		{
			foreach ($items as $item) 
			{
				$this->add($item);
			}

			return $this;
		}

		if ($items instanceof Menu) 
		{
			$items->setDepth($this->depth + 1);
			$items->setInheritClass($this->inheritClass);
			$items->setInheritPermission($this->inheritPermission);

			$this->children[] = $items;

			return $this;
		}

		throw new \Exception("Cannot add menu/item to stack");
		
	}

	public function __toString()
	{
		return (string)$this->menuize();
	}

	public function itemize($template)
	{
		$item = $template->get('item');

		$item->setdepth( $this->depth );
		$item->setslug( str_replace('/', ' r_', rtrim($this->route, '/')) );
		$item->setslugcomplete( preg_replace('/[^\da-z]/i', '', $this->name) );
		$item->setclass( implode(' ', $this->class) );


		$label = $item->get( (is_null($this->route) ? 'group' : 'link') );
		
		$label
			->setlink($this->name)
			->sethref($this->prepend . $this->route);
		;

		$item->add($label);

		if (count($this->children)) 
		{
			$item->glue('menu', (string)$this->menuize());
		}

		return $item;
	}

	public function menuize()
	{
		$template = Template::make('parts/nav');

		$menu = $template->get('menu');
		$menu->setdepth( $this->depth );
		$menu->setclass( implode(' ', $this->class) );

		foreach ($this->children as $child) 
		{
			$child->prepend($this->prepend);
			$menu->add($child->itemize($menu));
		}

		return $menu;	
	}

	public function find($route)
	{
		foreach ($children as $child) 
		{
			$found = $child->find($route);
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

	public function prepend($path)
	{
		$this->prepend = $path;

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

	public function inheritClass($class, $plus=false)
	{
		if ($plus) 
		{
			$this->addClass($class);
		}

		$this->inheritClass[] = $class;

		return $this;
	}

	public function setInheritPermission(array $permissions)
	{
		$this->inheritPermission = $permissions;

		foreach ($permissions as $name => $permission) 
		{
			$this->addPermission($name, $permission);
		}

		return $this;
	}

	public function inheritPermission($name, $perm, $plus=false)
	{
		if (array_key_exists($name, $this->inheritPermission)) 
		{
			throw new \Exception("Permission already exists, cannot overwrite.");
		}
		else
		{
			if ($plus) 
			{
				$this->addPremission($name, $perm);
			}
			$this->inheritPermission[$name] = $perm;
		}

		return $this;
	}

}

?>