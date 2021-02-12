<?php

/**
 * FileName: seftranslate.php
* Date: 09/09/2015
* Development Aleksey Pakholkov, Andrey Kvasnevskiy - OrdaSoft(http://ordasoft.com)
* @package SefTranslate
* @copyright 2010 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);Aleksey Pakholkov
* Homepage: http://www.ordasoft.com
* @version: 5.0 free
* @license GNU General Public license version 2 or later; see LICENSE.txt
**/




// Don't allow direct linking
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

$GLOBALS['task'] = $task = JRequest::getVar( 'task', "" );
$GLOBALS['option'] = $option = JRequest::getVar( 'option', "com_seftranslate" );

$document = JFactory::getDocument();
require("./components/com_seftranslate/GTranslate.php");
require(JPATH_SITE.'/components/com_seftranslate/dom_parse_traslate.php');
$lang_list=parse_ini_file("./components/com_seftranslate/languages.ini");
$GLOBALS['seftranslate_configuration'] = $seftranslate_configuration;
require_once("./components/com_seftranslate/languages.conf.php");
$document->addScript(JURI::root(true).'/modules/mod_seftranslate/js/jquery.js');
$document->addScript(JURI::root(true).'/modules/mod_seftranslate/js/cookie.js');
$def_lang=isset($seftranslate_configuration['site_language'])?$seftranslate_configuration['site_language']:'en';
$sepa=". _S_E_P_A_R. ";
$site_sub_url=substr($_SERVER['SCRIPT_NAME'],0,strpos($_SERVER['SCRIPT_NAME'] , 'index.php' ));
$map_langs=explode("|",$seftranslate_configuration['map_lang']);
$gt = new Gtranslate;
$ch = curl_init();
?>
  <script>
    function go_link(url)
    {

      location.href=url;
      jQuery.cookie('seflang', '<?php echo $seftranslate_configuration['site_language']; ?>');
    }
  </script>
<?php
  if((is_file(JPATH_SITE."/components/com_seftranslate/map.cch"))and($seftranslate_configuration['cache_map']))
  {
    $fp = fopen(JPATH_SITE."/components/com_seftranslate/map.cch", "r");
    $contents = fread($fp, filesize(JPATH_SITE."/components/com_seftranslate/map.cch"));
    fclose($fp);
    echo $contents;
    echo '<p style="text-align: center; font-size: 10px;"><a title="Sef Translate - joomla software for automatically website translation" href="https//ordasoft.com/Joomla-Extensions/sef-translate-joomla-software-for-automatically-website-translation.html" target="_blank">Sef Translate - joomla software for automatically website translation</a></p>';

    return true;
  }

  $database = JFactory::getDBO();
  $sql="Select * from #__menu WHERE menutype='mainmenu' and published=1";
  $database->setQuery($sql);
  $menus=$database->loadObjectList();

  $li_menu="";
  $content="";
  $content.="<span class=\"notranslate\"><div>";
  if($def_lang!="en")
  {
    $l_func=array_search("en",$lang_list)."_to_".array_search($def_lang,$lang_list);
    $content.="<h2>".$gt->$l_func("Main menu")."</h2>";
  }
  else
  {
    $content.="<h2> Main menu </h2>";
  }
  $content.="<ul>";
  $s_translate=Array();
  $arr_for_translate=Array();
  foreach($menus as $menu)
  {
    if (version_compare(JVERSION, "1.6.0", "lt")){
          $li_menu.="<li><a href=\"".$menu->link."\" title=\"".$menu->name."\">".$menu->name."</a></li>";
          $s_translate[]=$menu->name;
    }else{
      $li_menu.="<li><a href=\"".$menu->link."\" title=\"".$menu->title."\">".$menu->title."</a></li>";
      $s_translate[]=$menu->title;
      //$li_menu.="<li><a href=\"".$menu->link."\" title=\"".$menu->title."\">".$menu->title."</a></li>";
      //$s_translate[]=$menu->title;
    }
  }
  $content.=$li_menu;
  $content.="</ul>";

  foreach($map_langs as $mlang)
  {
    if($seftranslate_configuration['site_language']!=$mlang)
    {
        foreach($s_translate as $translate){
          //check translated text in database
          $ret_value = get_text_translated($translate,$def_lang,$mlang);
          if($ret_value === false )
            $arr_for_translate[]= $translate ;
        }
        // set size for translate block,  top limit;
        $high_size = 2000;
        $size_for_translat = 0 ;
        $arr_for_translate_part = array();
          //exit;
          echo "<!--";

        try
        {

          foreach($arr_for_translate as $text_for_translate){
            if( ($size_for_translat + strlen($text_for_translate) ) < $high_size )  {
              $size_for_translat = $size_for_translat + strlen($text_for_translate);
              $arr_for_translate_part[] = $text_for_translate ;
            } else {
              //translate text array
              array_text_translate($arr_for_translate_part,$def_lang,$mlang) ;
              echo " ";
              $size_for_translat = strlen($text_for_translate);
              $arr_for_translate_part = array();
              $arr_for_translate_part[]= $text_for_translate ;
            }
          }
          if(count($arr_for_translate_part) > 0 ) array_text_translate($arr_for_translate_part,$def_lang,$mlang) ;
          echo "-->";

          $content.="<h2>".text_translate("Main menu",$def_lang,$mlang)."</h2><ul>";
          $li_menu="";
          $i=0;
          foreach($menus as $menu)
          {
            $let=explode("http:",$menu->link);
            if(count($let)<=1) $let=explode("https:",$menu->link);
            
            $li_menu.="<li><a href=\"".((count($let)<=1)?$mlang."/":"" ).$menu->link."\" title=\"".text_translate($s_translate[$i],$def_lang,$mlang)."\">".text_translate($s_translate[$i],$def_lang,$mlang)."</a></li>";
            $i++;
          }
        }
        //catch exception
        catch(Exception $e)
        {
            {
              echo 'Please try translate this page late. Now happned error at translate time: ' .$e->getMessage();
              print_r($e);
              exit;
            } 
        }

        $content.=$li_menu;

        $content.="</ul>";
    }
  }
  $content.="</div></span>";
  $content=str_ireplace("<a ",'<a onClick="go_link()"',$content);
  echo $content;
  echo '<p style="text-align: center; font-size: 10px;"><a title="Sef Translate - joomla software for automatically website translation" href="https//ordasoft.com/Joomla-Extensions/sef-translate-joomla-software-for-automatically-website-translation.html" target="_blank">Sef Translate - joomla software for automatically website translation</a></p>';

  $fp = fopen(JPATH_SITE."/components/com_seftranslate/map.cch"  , "w", 0); #open for writing
  fwrite($fp, $content); #write all of $data to our opened file
  fclose($fp); #close the file

?>
