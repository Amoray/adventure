<?php

namespace FormBuilder;

/**
* Form Builder
*/
class FormBuilder
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

/**
* Form Default
*/
class Element
{
	const		SECTION		= null;
	const		FORCE		= false;

	public		$template	= null;
	protected	$preserve	= false;

	protected	$name		= null;
	protected	$value		= null;

	protected	$append		= null;

	protected	$error		= false;
	
	protected	$attributes	= array();

	static function make($name, $value=null)
	{
		return new static($name, $value);
	}
	/**
	 * Configures the initial properties for an Element
	 * @param String $name  Element name
	 * @param String $value Element value
	 */
	public function __construct($name, $value=null)
	{
		// Write name attribute and property
		if (null !== $name) 
		{
			$this->name = $name;
			$this->name($name);
		}

		// If successful clear form, otherwise auto fill form.
		// Also write value attribute and property
		if (!Outbound::success() && Outbound::$try && !static::FORCE) 
		{
			$this->value = htmlentities($_POST[$name], ENT_QUOTES, 'UTF-8');
			$this->value(htmlentities($_POST[$name], ENT_QUOTES, 'UTF-8'));
		}
		elseif (!is_null($value))
		{
			$this->value = $value;
			$this->value($value);
		}

		// Some Elements may also have the "Checked" ancestor.
		// This allows Checked form fields to remain checked.
		if (method_exists($this, 'checked')) 
		{
			$this->checked();
		}

		// Some Elements may also have the "Selected" ancestor.
		// This allows Selected form fields to remain selected.
		if (method_exists($this, 'selected')) 
		{
			$this->selected();
		}
		
		// Class is always append
		$this->attributes['class'] = array(
			"name" => "class",
			"value" => "",
			"extra" => false,
			"append" => true
		);

		// Fetch validation logic, used to carry forward error message from previous submits
		$validation = Outbound::validation($name);

		// If any errors found, append them to the field.
		if (array_key_exists('error', $validation) && $validation['error'] instanceof \exception) 
		{
			$this->error($validation['error']->getMessage());
			$this->class('error');
		}
	}

	/**
	 * Call allows various attributes to be stored for use when displaying
	 * @param  string $name   Name of attribute
	 * @param  mixed $values  Whatever data you want to try and stuff into the form
	 * @return $this
	 */
	public function __call($name, $values)
	{
		// If only one parameter is set, set 1 to false
		// The second parameter is used to force strict inserting; used to preserve URLs mostly
		if (1 === count($values)) 
		{
			$values[1] = false;
		}

		// Store values as attributes
		// If an attribute is set to append able i.e. error and class, additional values are appended
		// Otherwise attributes are overwritten
		if (array_key_exists($name, $this->attributes) && true === $this->attributes[$name]['append']) 
		{
			$this->attributes[$name]['value'] .= " ".$values[0];
			$this->attributes[$name]['extra'] = $values[1];
		}
		else
		{
			$this->attributes[$name] = array(
				"name" => $name,
				"value" => $values[0],
				"extra" => $values[1],
				"append" => false
				);
		}

		return $this;
	}

	/**
	 * Required is a shortcut to forcing a field to display as "Required"
	 * @return $this
	 */
	public function required()
	{
		$this->__call('required', '*');

		return $this;
	}

	/**
	 * fromTemplate is a recursive function of sorts.
	 * It generates its template
	 * It applies attributes to that template
	 * It triggers any children or appended Elements to build their templates too.
	 * @param  StampTE $template Template Engine
	 * @param  string $chain    used to delve a little deeper into the template file (extra nesting)
	 */
	public function fromTemplate($template, $chain = "")
	{
		// Set us up with a template
		$this->template = $template->get($chain . static::SECTION);

		// Loop stored attributes
		foreach ($this->attributes as $value) {
			// inject attributes with appropriate methods
			if ('attr' == $value['extra']) 
			{
				$this->template->injectAttr($value['name'], $value['value']);
			}
			else
			{
				$this->template->inject($value['name'], $value['value'], $value['extra']);
			}
		}

		// If anything is appended to this, template it and glue to appended slot
		if ($this->append instanceof Element) 
		{
			$this->append->fromTemplate($template);
			$this->template->glue('append', $this->append->template);
		}

	}

	/**
	 * Append allows additional elements to be added to this one
	 * @param  Element $element An element
	 * @return $this
	 */
	public function append(Element $element)
	{
		$this->append = $element;

		return $this;
	}

	/**
	 * Defines the default option to be selected
	 * @param  boolean $ignore Can be overridden by passing a true
	 * useful when the input comes from an external resource
	 * @return $this
	 */
	public function standard($ignore = false)
	{
		if ($ignore) 
		{
			return $this;
		}

		// if no ATTRIBUTE defined, ignore
		if (!defined('static::ATTRIBUTE')) 
		{
			return $this;
		}

		// if name appears in POST as a key, call an an appropriate function
		if (!array_key_exists($this->name, $_POST)) 
		{
			$call = "add". static::ATTRIBUTE;
			$this->{$call}();
		}

		return $this;
	}

}

/**
* Wrapper
*/
class Wrapper extends Element
{
	const		STYLE		= null;
	const		SECTION		= 'WRAPPER';
	const		CHILD		= 'CHILD';

	protected	$children	= array();
	protected	$child		= null;

	static function wrap($name)
	{
		return new static($name);
	}

	public function __construct($name)
	{
		parent::__construct($name);
	}

	/**
	 * Create a child for the wrapper based on the wrapper's CHILD name
	 * @param  String $name  Name to apply to the child
	 * @param  String $value Value to apply to the child
	 * @return new class
	 */
	static function child($name, $value = null)
	{
		$class = 'Form\\'. static::CHILD;

		return new $class($name, $value);
	}

	/**
	 * Add children elements to the wrapper
	 * @param Element $element 
	 */
	public function add(Element $element)
	{
		if (empty($this->child)) 
		{
			$this->children[] = $element;
		}

		return $this;
	}

	/**
	 * fromTemplate is a recursive function of sorts.
	 * It generates its template
	 * It applies attributes to that template
	 * It triggers any children or appended Elements to build their templates too.
	 * @param  StampTE $template Template Engine
	 * @param  string $chain    used to delve a little deeper into the template file (extra nesting)
	 */
	public function fromTemplate($template, $chain = null)
	{
		if (defined("static::STYLE")) 
		{
			$this->class(static::STYLE);
		}
		
		parent::fromTemplate($template, $chain);

		foreach ($this->children as $value) {
			$value->fromTemplate($template, $chain . static::SECTION .".");
			$this->template->add($value->template);
		}
	}
}

/**
* checking - automated "checking" of radio/checkboxes/selects/
*/
class Checking extends Element
{
	const		ATTRIBUTE	= "Checked";

	public function checked()
	{
		if (array_key_exists($this->name, $_POST) && $this->value == $_POST[$this->name]) 
		{
			$this->addChecked();
		}
	}

	public function addChecked()
	{
		$this->__call('checked', array('checked', 'attr'));
	}
}

/**
* Selecting - automated "Selecting" of Drop downs and Combo Boxes
*/
class Selecting extends Element
{
	const		ATTRIBUTE	= "Selected";

	public function selected()
	{
		if (array_key_exists($this->name, $_POST) && $this->value == $_POST[$this->name]) 
		{
			$this->addSelected();
		}
	}
	
	public function addSelected()
	{
		$this->__call('selected', array('selected', 'attr'));
	}
}

/**
* Hidden
*/
class Hidden extends Element
{
	const		SECTION		= 'hidden';
}

/**
* Input
*/
class Input extends Element
{
	const		SECTION		= 'text';
}

/**
* Check Box
*/
class Checkbox extends Element
{
	const		SECTION		= 'checkbox';
}

/**
* Text Box
*/
class Text extends Element
{
	const		SECTION		= 'textarea';
}


/**
* Radio Wrapper
*/
class Radio extends Wrapper
{
	const		STYLE		= 'radio';
	const		SECTION		= 'radio';
	const		CHILD		= 'RadioButton';
}

/**
* Radio Button
*/
class RadioButton extends Checking
{
	const		SECTION		= 'button';
	const		FORCE		= true;

}

/**
* Date Input
*/
class Date extends Element
{
	const		SECTION		= 'date';
}

/**
* Number Input
*/
class Number extends Element
{
	const		SECTION		= 'number';
}

/**
* HoneyPot Input
*/
class HoneyPot extends Element
{
	const		SECTION		= 'honeypot';
	const		HONEYPOT	= 'information';

	public function __construct($name, $value = null)
	{
		parent::__construct($name, $value);

		$this->title(ucwords(static::HONEYPOT));
		$this->name(static::HONEYPOT);
		$this->class('hpelement');
	}
}


/**
* Outbound
*/
class Outbound
{
	static		$try		= false;
	static		$success	= false;
	static		$error		= false;
	static		$validate	= false;

	static function SendMail()
	{
		static::$try = true;

		$mail = \System::Mail();
		$mail->isHTML(true);

		// PHP does not allow static::$validate to be invokable, workaround and clutter
		// static::$validate = $validate after validations are complete.
		$validate = new validate();
		$validate('request')->required();

		switch (\System::$request) 
		{
			case 'gov_service':

				$validate('company_name')->required();
				$validate('contact_name')->required();
				$validate('phone_number')->required();
				$validate('email_address')->required();
				$validate('describe')->required();

				$mail->Subject = "Government Service Request";
				// $mail->addAddress('gradyw@omegacom.ca');
				// $mail->addAddress('warrens@omegacom.ca');
				break;

			case 'health_service':

				$validate('company_name')->required();
				$validate('contact_name')->required();
				$validate('phone_number')->required();
				$validate('email_address')->required();
				$validate('describe')->required();

				$mail->Subject = "Health Service Request";
				// $mail->addAddress('gradyw@omegacom.ca');
				// $mail->addAddress('darenl@omegacom.ca');
				break;

			case 'gov_rental':

				$validate('company_name')->required();
				$validate('contact_name')->required();
				$validate('phone_number')->required();
				$validate('email_address')->required();
				$validate('describe')->required();

				$mail->Subject = "Government Rental Request";
				// $mail->addAddress('gradyw@omegacom.ca');
				// $mail->addAddress('warrens@omegacom.ca');
				break;

			case 'gov_quote':

				$validate('company_name')->required();
				$validate('contact_name')->required();
				$validate('phone_number')->required();
				$validate('email_address')->required();
				$validate('describe')->required();

				$mail->Subject = "Government Quote Request";
				// $mail->addAddress('russc@omegacom.ca');
				// $mail->addAddress('warrens@omegacom.ca');
				break;

			case 'health_quote':

				$validate('company_name')->required();
				$validate('contact_name')->required();
				$validate('phone_number')->required();
				$validate('email_address')->required();
				$validate('describe')->required();

				$mail->Subject = "Health Quote Request";
				// $mail->addAddress('darenl@omegacom.ca');
				// $mail->addAddress('warrens@omegacom.ca');
				break;

			case 'gov_page':
			case 'health_page':

				$validate('pager_number')->required()->max(14)->phone(7);
				$validate('message')->required()->max(240);

				// $mail->addAddress('page@omegacom.ca');
				break;

			case 'quick':

				$validate('contact_name')->required();
				$validate('phone_number')->required();
				$validate('describe')->required();

				// $mail->addAddress('@omegacom.ca');
				break;
		}

		static::$validate = $validate;

		$mail->addAddress('amelia@spincaster.com');

		if ( static::honeypot() && static::valid() ) 
		{
			$mail->Body    = static::content();
			$mail->AltBody = strip_tags(static::content());

			if (!$mail->send()) 
			{
				static::$success = false;
				static::$error = new \exception($mail->ErrorInfo);
			}
			else
			{
				static::$success = true;
			}

		}
	}

	static function honeypot()
	{
		if (array_key_exists(HoneyPot::HONEYPOT, $_POST) && !empty($_POST[HoneyPot::HONEYPOT])) 
		{
			static::$error = new \exception('HONEYPOT', 1);
			return false;
		}
		return true;
	}

	static function content()
	{
		$string = "<html><body><pre>";
		$post = $_POST;

		unset($post['process'], $post['request'], $post['template']);

		foreach ($post as $key => $value) {
			$string .= htmlentities($key, ENT_QUOTES, 'UTF-8') ." => ". htmlentities($value, ENT_QUOTES, 'UTF-8') ."\r\n";
		}
		$string .= "</pre></body></html>";

		return $string;
	}

	static function success()
	{
		return static::$success && static::valid();
	}

	static function error()
	{
		return static::$error;
	}

	static function valid()
	{
		if (false == static::$validate) 
		{
			$validate = new validate();
		}
		else
		{
			$validate = static::$validate;
		}
		return $validate->success();
	}

	static function honeypotted()
	{
		return static::$error instanceof \exception && 1 === static::$error->getCode();
	}

	static function validation($key = null)
	{
		if (false == static::$validate) 
		{
			$validation = new validate();
		}
		else
		{
			$validation = static::$validate;
		}
		return $validation->checklist($key);
	}
}


/**
* Validation Class
*/
class validate
{
	protected	$checklist	= array();
	protected	$success	= true;

	public function __invoke($key, $value = null)
	{
		if (is_null($value))
		{
			$this->checklist[$key] = array("value" => $_POST[$key]);
		}
		else
		{
			$this->checklist[$key] = array("value" => $value);
		}

		return $this;
	}

	private function end()
	{
		$test = end($this->checklist);
		$key = key($this->checklist);

		return array($test['value'], $key);
	}

	private function fail(\Exception $e)
	{
		list($test, $key) = $this->end();

		$this->success = false;
		$this->checklist[$key]['error'] = $e;
	}

	public function required()
	{
		list($test, $key) = $this->end();

		if (empty($test)) 
		{
			$this->fail(new \Exception("This is a required field."));
		}

		return $this;
	}

	public function phone($length)
	{
		list($test, $key) = $this->end();

		$test = preg_replace("/[^\+0-9]/", "", $test);
		if (strlen($test) != $length) 
		{
			$this->fail(new \Exception("This phone number requires {$length} digits."));
		}

		return $this;
	}

	public function max($length)
	{
		list($test, $key) = $this->end();

		if (strlen($test) > $length) 
		{
			$this->fail(new \Exception("This field must be {$length} characters or fewer."));
		}

		return $this;
	}

	public function min($length)
	{
		list($test, $key) = $this->end();

		if (strlen($test) < $length) 
		{
			$this->fail(new \Exception("This field must be {$length} characters or more."));
		}

		return $this;
	}


	public function success()
	{
		return $this->success;
	}

	public function checklist($key = null)
	{
		if (array_key_exists($key, $this->checklist)) 
		{
			return $this->checklist[$key];
		}
		else
		{
			return array();
		}
	}

}
?>