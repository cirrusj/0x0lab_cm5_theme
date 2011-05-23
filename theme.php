<?php
require_once(__DIR__ . '/layout.php');

class CM5_Theme_ZeroXZeroLab extends CM5_Theme
{
	public function getDefaultConfiguration()
	{
		$version = CM5_Core::getInstance()->getVersion();
		return array(
            'footer' => "using <a target=\"_blank\" href=\"http://code.0x0lab.org/p/cm5\">CM5</a>",
            'favicon-url' => '',
            'extra-css' => '',
		);
	}

	public function getConfigurableFields()
	{
		return array(
            'favicon-url' => array('label' => 'Favicon url:'),
            'footer' => array('label' => 'Footer content:', 'type' => 'textarea'),
            'extra-css' => array('label' => 'Extra css to be included:', 'type' => 'textarea')
		);
	}

	public function getLayoutClass()
	{
		return 'ZeroXZeroLabThemeLayout';
	}

	public function onInitialize()
	{
		parent::onInitialize();
		CM5_Core::getInstance()->events()->connect('page.pre-render', function($e){
			if (($e->arguments['url'] == '') || ($e->arguments['url'] == '/'))
			$e->filtered_value->title = '0x0lab';
		});
	}
}

return array(
	'class' => 'CM5_Theme_ZeroXZeroLab',
	'nickname' => '0x0lab',
	'title' => '0x0Labs Official Theme',
	'description' => 'Theme designed for 0x0lab website.'
);