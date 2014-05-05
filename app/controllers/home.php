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
		// os_#os?# osv_#osv?# b_#b?# bv_#bv# vcss_#css?# #device# #path?#"

		$template = $this->prepare();

		return $template;
		Partial::make('foundation', 'style');
		// return Partial::make('foundation');
	}
}

?>