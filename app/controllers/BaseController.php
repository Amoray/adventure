<?php

use Template\Template AS Template;
use Template\Partial AS Partial;
use Navigation\Menu AS Menu;

class BaseController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	protected function setupLayout()
	{
		if ( ! is_null($this->layout))
		{
			$this->layout = View::make($this->layout);
		}
	}

	protected function prepare()
	{
		$route = Route::getCurrentRoute()->getUri();
		$route = preg_replace('/\{.*?\}/', '', $route);
		$route = preg_replace('/\/\/+/', '/', $route);
		$route = str_replace('/', ' ', $route);

		$template = Template::make('foundation');
		$template
			->setos(BrowserDetect::osFamily())
			->setosv(BrowserDetect::osVersionMajor() ."_". BrowserDetect::osVersionMinor())
			->setb(BrowserDetect::browserFamily())
			->setbv(BrowserDetect::browserVersionMajor() ."_". BrowserDetect::browserVersionMinor())
			->setcss(BrowserDetect::cssVersion())
			->setdevice(BrowserDetect::deviceModel())
			->setpath($route)
			->setTitle('Home')
			->glue('style', Partial::headstyle('reset'))
			->glue('style', Partial::headstyle('standard'))
		;

		$nav = Menu::make()
			->addChildren( 
				Menu::child('/', 'Home')->inheritClass('green'),
				Menu::child('/login', 'Login')->inheritClass('blue')->inheritPermission('auth', !Auth::check())
					->addChildren( Menu::child('/login/forgot', 'Forgot'))
			)

		;

		$template->glue('nav', $nav);

		return $template;
	}
}
