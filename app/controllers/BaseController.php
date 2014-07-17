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
			->glue('style', Partial::headstyle('pnav'))
		;

		$nav = Menu::make()->prepend(Config::get('app.url'))
			->add([
				Menu::make('/', 'adventure')->inheritClass('red', true),
				Menu::make(null, 'user')->inheritClass('orange', true)->inheritPermission('auth', !Auth::check())
					->add([
						Menu::make('/user/login', 'login'),
						Menu::make('/user/login/forgot', 'forgot'),
						Menu::make('/user/login/create', 'create'),
					]),
				Menu::make(null, "my")->inheritClass('yellow', true)->inheritPermission('auth', !Auth::check())
					->add([
						Menu::make('/my/characters', 'characters'),
						Menu::make('/my/weapons', 'weapons')
					]),
				Menu::make(null, 'party')->inheritClass('green', true)->inheritPermission('auth', !Auth::check())
					->add([
						Menu::make('/party/characters', 'characters')
					])
			])
		;

		$template->glue('nav', (string)$nav);

		return $template;
	}
}
