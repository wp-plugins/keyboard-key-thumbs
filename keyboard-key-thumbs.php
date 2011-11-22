<?php 
/*
Plugin Name: Keyboard Key Thumbs
Description: This plugin is intended to help those that need to write an article that contains keyboard key combinations. It simply replace the shortcode [key] with the tag <kdb> and display a key image.
Version: 0.2
Author: Shadow silver
Plugin URI: http://crealm.altervista.org/mainblog/keyboard-key-thumbs/
Author URI: http://crealm.altervista.org/mainblog
License: LGPL3
*/

class Keyboard_Key_Thumbs
{
    function __construct()
    {
        add_shortcode('key', array( &$this, 'kbd_substitution'));
        //add_shortcode('galley', array( &$this, 'kbd_substitution'));
        add_action('wp_head', array( &$this, 'kbd_css'),10,1);
    }
    
    function kbd_css($content)
    {
        echo '<link rel="stylesheet" type="text/css" href="'. plugins_url() . "/" . dirname(plugin_basename(__FILE__)) . '/kdb-shortcode.css" />'; 
        //return  $link;
    }

    function kdb_add_tag($value)
    {
        $class = "kdb-shortcode";
        if (preg_match("/shift/i",$value) > 0)
        { $class .= " shift"; $value=ucfirst($value);}
        else if (preg_match("/ctrl|control/i",$value) > 0)
        { $class .= " control"; $value=ucfirst($value);}
        else if (preg_match("/alt/i",$value) > 0)
        { $class .= " alt"; $value=ucfirst($value);}
        else if (preg_match("/enter|invio/i",$value) > 0)
        { $class .= " enter"; $value=ucfirst($value);}
        else if (preg_match("/win|windows/i",$value) > 0)
        { $class .= " win"; $value="<span>"  . ucfirst($value) . "</span>";}
        else if (preg_match("/back|backspace|indietro/i",$value) > 0)
        { $class .= " back"; $value="<span>"  . ucfirst($value) . "</span>";}
        else if ( strlen($value) == 1)
        { $class .= " char";}
        else if ( strlen($value) > 1 && strlen($value) < 4)
        { $class .= " middle";}
        else if ( strlen($value) > 3 && strlen($value) < 6)
        { $class .= " large";}
        
        return "<kdb class=\"$class\">" . $value . "</kdb>";
    }

    function kbd_substitution( $atts, $content=null)
    {
        if ($content == null)
            return "";

        $content = preg_replace("/(\w+) \+ (\w+)/","$1+$2",$content);
        $key_expressions = preg_split("/ /",$content);
        $return ="";

        foreach ($key_expressions as $key_expression)
        {
            $key_combinations = preg_split("/\+/",$key_expression);
            $key_combinations = array_map( array( &$this,"kdb_add_tag"),$key_combinations);

            $return .= "<span class=\"kdb-shortcode\">" . implode("+",$key_combinations) . "</span> ";
        }

        return $return;
        
    }

}


$kdb_shortcode = new  Keyboard_Key_Thumbs();

?>
