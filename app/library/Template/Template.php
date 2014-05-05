<?php

namespace Template;

/**
* Templates is a StampTE wrapper
*/
class Template
{
	const		TEMPLATE_DIR	= '/app/views/templates/';
	static		$icons			= false;
	static		$hrefs			= false;

	static function make($file)
	{
		$file = \Config::get('template.path') . $file . ".tpl";
		if (!file_exists($file)) 
		{
			throw new \Exception("Template file not found");
		}
		else
		{
			return new StampTE(file_get_contents($file));
		}
	}

	static function icon($icon)
	{
		if (!self::$icons) 
		{
			self::$icons = self::stamp('icons');
		}

		$template = self::$icons->copy();
		
		return $template->get($icon);
	}

	static function glue($addto, $point, array $stamps)
	{
		if (is_object($addto) && 'StampTE' == get_class($addto))
		{
			foreach ($stamps as $value) 
			{
				if ('StampTE' == get_class($value))
				{
					$addto->glue($point, $value);
				}
			}
		}
		elseif (is_array($addto))
		{
			$object = array_pop($addto);
			foreach ($stamps as $value) 
			{
				if ('StampTE' == get_class($value))
				{
					$object->glue($point, $value);
				}
			}
			$addto[] = $object;
		}

	}

	static function add(&$addto, array $stamps)
	{
		if (is_object($addto) && 'StampTE' == get_class($addto))
		{
			foreach ($stamps as $value) 
			{
				if ('StampTE' == get_class($value))
				{
					$addto->add($value);
				}
			}
		}
		elseif (is_array($addto))
		{
			$object = array_pop($addto);
			foreach ($stamps as $value) 
			{
				if ('StampTE' == get_class($value))
				{
					$object->add($value);
				}
			}
			$addto[] = $object;
		}
	}
}








?>