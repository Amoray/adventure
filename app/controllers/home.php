<?php

use Template\Template AS Template;
use Template\Partial AS Partial;

/**
* Manages homepages
*/
class home extends BaseController
{
	public function index()
	{
		$template = $this->prepare();

		return $template;
		Partial::make('foundation', 'style');
		// return Partial::make('foundation');
	}
}

?>