<?php

namespace Template\jQuery;

/**
* Tab
*/
class Tab extends templateWrapper
{
	const	NAME = "/tab";

	protected function identify() {
		$this->template->settabid(uniqid("tabs_"));
	}

	public function add($title, StampTE $content, $inject = false)
	{
		$header = $this->template->get('title');
		$body = $this->template->get('tab');

		$id = uniqid("tab_");

		$header->inject('name', $title, $inject)->settab($id);
		$body->glue('content', $content)->settab($id);

		$this->template->add($header);
		$this->template->add($body);

		return $this;
	}
}


?>