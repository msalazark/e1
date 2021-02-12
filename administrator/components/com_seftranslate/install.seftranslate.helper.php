<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
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

$mainframe = JFactory::getApplication('site');
$url = JURI::root() ;
$mosConfig_live_site = $GLOBALS['mosConfig_live_site']=substr_replace($url, '', -1, 1);
      
$GLOBALS['path'] = $mosConfig_live_site."/administrator/components/com_seftranslate/images/";
$path = $GLOBALS['path'];

class SEFInstallHelper
{ 
  static function getComponentId()
  {
    global $database;
    static $id;
    $database = JFactory::getDBO();
    if (version_compare(JVERSION, "1.6.0", "lt")) {
        if (!$id) {
          $database->setQuery("SELECT id FROM #__components WHERE `option`='com_seftranslate' AND `parent`=0 ");
          $id = $database->loadResult();
        }
        return $id;
    } else if (version_compare(JVERSION, "1.6.0", "ge") ) {
      $database->setQuery("SELECT extension_id FROM #__extensions WHERE `element`='com_seftranslate' ");
      $id = $database->loadResult();
      return $id;
    } else {
      echo "Sanity test. Error version check!";
      exit;
    }
  }

  static function getParentId() {
      $id = SEFInstallHelper::getComponentId();
      global $database;
      $database = JFactory::getDBO();
      if (version_compare(JVERSION, "1.6.0", "lt")) {
          //
      } else if (version_compare(JVERSION, "1.6.0", "ge") ) {
          $database->setQuery("SELECT id FROM #__menu WHERE title='Sef Translate' and level=1 and parent_id=1 and component_id=$id");
          $parent_id = $database->loadResult();
          return $parent_id;
      } else {
          echo "Sanity test. Error version check!";
          exit;
      }
  }

  static function setAdminMenuImages(){

    global $database, $path;
    $database = JFactory::getDBO();
    $id = SEFInstallHelper::getComponentId();
    if (version_compare(JVERSION, "1.6.0", "lt")) {
        // Main menu
        $database->setQuery("UPDATE #__components SET admin_menu_img = '" . $path . "dm_component_16.png' WHERE id=$id");
        $database->query();

        // Submenus
        $submenus = array();
        $submenus[] = array( 'image' => $path.'dm_edit_16.png', 'name'=>'URLs' );
        $submenus[] = array( 'image' => $path.'dm_component_16.png', 'name'=>'Settings' );
        $submenus[] = array( 'image' => $path.'dm_credits_16.png', 'name'=>'About' );

        foreach ($submenus as $submenu) {
            $database->setQuery("UPDATE #__components SET admin_menu_img = '" . $submenu['image'] .
               "' WHERE parent=$id AND name = '" . $submenu['name'] . "';");
            $database->query();
        }
    } else if (version_compare(JVERSION, "1.6.0", "ge") ) {
        $parent_id = SEFInstallHelper::getParentId();

        // Main menu
        $database->setQuery("UPDATE #__menu SET img = 'class:component' ".
          " WHERE title='Sef Translate' and level=1 and parent_id=1 and component_id=$id");
        $database->query();

        // Submenus
        $submenus = array();
        $submenus[] = array('img' => 'class:component', 'title' => 'Sef Translate','alias'=>'Sef Translate');
        $submenus[] = array('img' => 'class:config', 'title' => 'Settings','alias'=>'Settings');
        $submenus[] = array('img' => 'class:info', 'title' => 'About','alias'=>'About');
        $submenus[] = array('img' => 'class:weblinks', 'title' => 'Help','alias'=>'Help');


        foreach ($submenus as $submenu) {
            $database->setQuery("UPDATE #__menu SET img = '" . $submenu['img'] . "' WHERE component_id=$id AND parent_id = '" . $parent_id . "' and level=2  AND title = '" . $submenu['title'] . "';");
            $database->query();
            $database->setQuery("UPDATE #__menu SET alias = '" . $submenu['alias'] . "'" . "\n WHERE component_id=$id AND title = '" . $submenu['title'] . "';");
            $database->query();
        }
    } else {
        echo "Sanity test. Error version check!";
        exit;
    }

  }

}

