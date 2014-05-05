<?php

namespace Template;

/**
* Wrapper
*/
class Wrapper 
{
	protected $template = null;

	public function __construct($folder) {
		$this->template = \template::stamp($folder.static::NAME);
		$this->identify();
	}

	public function __toString()
	{
		return (string)$this->template;
	}

	public function export()
	{
		return $this->template;
	}
}

?>