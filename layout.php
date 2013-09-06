<?php

class ZeroXZeroLabThemeLayout extends CM5_ThemeLayout
{
    private $mainmenu = null;
    
    public static $theme_nickname = '0x0lab';
    
    public function get_mainmenu()
    {
        return $this->mainmenu;
    }

    private function __add_menu_entries($parent_link, $childs, $max_depth)
    {
        if ($max_depth <= 0)
            return;
            
        foreach($childs as $p)
        {
            if ($p['status'] !== 'published')
                continue;

            $sublink = $parent_link->createLink($p['title'], url($p['uri']));
            if ($p['uri'] === '/')
                $sublink->setAutoselectMode('equal');
                
            $this->__add_menu_entries($sublink, $p['children'], $max_depth - 1);
        }
    }
    
    private function init_menu()
    {
        $this->mainmenu = new SmartMenu(array('class' => 'menu'));        
        $this->__add_menu_entries($this->mainmenu, CM5_Core::getInstance()->getTree(), 2);
        
        // Append menu
        $this->getDocument()->get_body()->getElementById("main-menu")
                ->append($this->mainmenu->render());
    }
    
    protected function onInitialize()
    {   
        $this->activateSlot();
        $doc = $this->getDocument();    
        $this->getDocument()->title = CM5_Config::getInstance()->site->title;
        $this->getDocument()->add_ref_css(surl('/themes/0x0lab/css/default.css'));
        
        if ($this->getConfig()->{"favicon-url"})
            $this->getDocument()->add_favicon($this->getConfig()->{"favicon-url"});
        
        etag('div id="wrapper"')->push_parent();
        etag('div id="header"',
            tag('h1', CM5_Config::getInstance()->site->title),
            tag('div id="main-menu"')
        );
        etag('div id="main"',
            $def_content = 
            tag('div id="content"'),
            tag('div id="spacer"')
        );
        etag('div id="footer" html_escape_off', 
            $this->getConfig()->footer
        );
        $this->setSlot('default', $def_content);
        
        if ($this->getConfig()->{"extra-css"})
            $this->getDocument()->get_head()->append(tag('style type="text/css" html_escape_off',$this->getConfig()->{"extra-css"}));
            
        // Search widget
        $this->init_menu();
        $this->deactivate();
    }
}

