<?php

class ZeroXZeroLabThemeLayout extends CM5_ThemeLayout
{
    private $mainmenu = null;
    
    public static $theme_nickname = 'default';
    
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

            $sublink = $parent_link->create_link($p['title'], url($p['uri']));
            if ($p['uri'] === '/')
                $sublink->set_autoselect_mode('equal');
                
            $this->__add_menu_entries($sublink, $p['children'], $max_depth - 1);
        }
    }
    
    private function init_menu()
    {
        $this->mainmenu = new SmartMenu(array('class' => 'menu'));        
        $this->__add_menu_entries($this->mainmenu, CM5_Core::get_instance()->get_tree(), 2);
        
        // Append menu
        $this->get_document()->get_body()->getElementById("main-menu")
                ->append($this->mainmenu->render());
    }
    
    protected function __init_layout()
    {   
        $this->activate();
        $doc = $this->get_document();    
        $this->get_document()->title = GConfig::get_instance()->site->title;
        $this->get_document()->add_ref_css(surl('/themes/0x0lab/css/default.css'));
        
        if ($this->get_config()->{"favicon-url"})
            $this->get_document()->add_favicon($this->get_config()->{"favicon-url"});
        
        etag('div id="wrapper"')->push_parent();
        etag('div id="header"',
            tag('h1', GConfig::get_instance()->site->title),
            tag('div id="main-menu"')
        );
        etag('div id="main"',
            $def_content = 
            tag('div id="content"'),
            tag('div id="spacer"')
        );
        etag('div id="footer" html_escape_off', 
            $this->get_config()->footer
        );
        $this->set_default_container($def_content);
        
        if ($this->get_config()->{"extra-css"})
            $this->get_document()->get_head()->append(tag('style type="text/css" html_escape_off',$this->get_config()->{"extra-css"}));
            
        // Search widget
        $this->init_menu();
        $this->deactivate();
    }
}

?>
