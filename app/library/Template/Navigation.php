<?php

namespace Navigation;

/**
* Form Builder
*/
class Navigation
{
	protected	$name		= 'default/form';
	protected	$template	= null;
	protected	$form		= array();
	protected	$fieldset	= null;
	protected	$attributes	= array();

	/**
	 * Create a template object from location
	 * @param string $name location of the template file within the template dir
	 */
	public function __construct($name = null) {
		if ($name) 
		{
			$this->template = \template::stamp($name);
			$this->name = $name;
		}
	}

	public function __call($name, $values)
	{
		if (2 === count($values)) 
		{
			$values[1] = false;
		}

		$this->attributes[$name] = array(
			"name" => $name,
			"value" => $values[0],
			"preserve" => $values[1]
			);

		return $this;
	}

	/**
	 * Create a new fieldset
	 * @param  String $class   class of fieldset
	 * @param  string $legend title of fieldset
	 * @return this         chaining
	 */
	public function fieldset($class = "fieldset", $legend = null, $note = null)
	{
		if (!empty($this->fieldset))
		{
			$this->form[] = $this->fieldset;
			$this->fieldset = null;
		}
		$this->fieldset = $this->template->get('section');

		$this->fieldset->setclass($class);
		if ($legend) 
		{
			$this->fieldset->setlegend($legend);
		}
		if ($note) 
		{
			$this->fieldset->setnote($note);
		}


		return $this;
	}

	/**
	 * echo thing
	 * @return string Yup, is does the echo thing
	 */
	public function __toString()
	{
		$this->form[] = $this->fieldset;
		$this->fieldset = null;


		foreach ($this->form as $value) {
			$this->template->add($value);
		}

		foreach ($this->attributes as $value) {
			$this->template->inject($value['name'], $value['value'], $value['preserve']);
		}

		return (string)$this->template;
	}

	/**
	 * Add an element to the existing form.
	 * @param  $element a form element
	 */
	public function add(element $element)
	{
		if (empty($this->fieldset)) 
		{
			$this->fieldset = $this->template->get('section');
		}

		$element->fromTemplate($this->fieldset);
		$this->fieldset->add($element->template);	

		return $this;
	}

}



?>