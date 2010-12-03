<?php
require_once(dirname(__FILE__) . '/layout.php');

class ZeroXZeroLabTheme extends CM5_Theme
{
    public function info()
    {
        return array(
            'nickname' => '0x0lab',
            'title' => '0x0Labs Official Theme',
            'description' => 'Theme designed for 0x0lab website.'
        );
    }
    
    public function default_config()
    {
        $version = CM5_Core::get_instance()->get_version();
        return array(
            'footer' => "using <a target=\"_blank\" href=\"http://code.0x0lab.org/p/cm5\">CM5</a>",
            'favicon-url' => '',
            'extra-css' => '',
        );
    }
    
    public function config_options()
    {
        return array(
            'favicon-url' => array('display' => 'Favicon url:'),
            'footer' => array('display' => 'Footer content:', 'type' => 'textarea'),
            'extra-css' => array('display' => 'Extra css to be included:', 'type' => 'textarea')
        );
    }
    
    public function get_layout_class()
    {
        return 'ZeroXZeroLabThemeLayout';
    }
    
    public function event_page_prerender(Event $e)
    {
    	if (($e->arguments['url'] == '') || ($e->arguments['url'] == '/'))
    		$e->filtered_value->title = '0x0lab';	
    }    
    
    public function init()
    {
    	parent::init();
    	CM5_Core::get_instance()->events()->connect('page.pre-render', array($this, 'event_page_prerender'));
    }
}

ZeroXZeroLabTheme::register();
