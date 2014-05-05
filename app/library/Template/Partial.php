<?php

namespace Template;

/**
* Partial, grabs stamp partials from the parts directory
*/
class Partial
{
	/**
	 * make returns a stamp partial file
	 * @param  string $file no need to include the file extension
	 * @param  string $section automatically get a cut of the file
	 * @return StampTE       a StampTE object
	 */
	static function make($file, $section=null)
	{
		$file = \Config::get('template.partial') . $file . ".tpl";
		if (!file_exists($file)) 
		{
			throw new \Exception("Template file not found");
		}
		else
		{
			$partial = new StampTE(file_get_contents($file));
			if (!is_null($section)) 
			{
				return $partial->get($section);
			}
			else
			{
				return $partial;
			}
		}
	}

	/**
	 * Feel free to add static methods below to shortcut commonly used partials.
	 * 
	 */
	static function headstyle($src)
	{
		return (string)Partial::make('foundation', 'style')
			->setsrc( \URL::asset('styles/'. $src . '.css') );
	}

	static function headscript($src)
	{
		return (string)Partial::make('foundation', 'script')
			->setsrc( \URL::asset('scripts/'. $src . '.js') );
	}
}

?>