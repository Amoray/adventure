<?php

namespace Template\Accordion;

/**
*  Accordion
*/
class Accordion extends templateWrapper
{
	const	NAME = "/accordion";

	protected function identify()
	{
		$this->template->setaccordionid(uniqid("accordion_"));
	}

	public function add($title, StampTE $content, $inject = false)
	{
		$bellow = $this->template->get('bellow');

		$bellow->inject('title', $title, $inject);
		$bellow->glue('content', $content);

		$this->template->add($bellow);

		return $this;
	}
}

?>