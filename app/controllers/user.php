<?php

use Template\Template AS Template;
use Template\Partial AS Partial;

/**
* Manages user
*/
class user extends BaseController
{
	public function index()
	{
		$template = $this->prepare();

		return $template;
	}

	public function show_login()
	{
		$template = $this->prepare();

		return $template;
	}
}

?>