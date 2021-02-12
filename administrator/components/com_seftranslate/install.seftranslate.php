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

if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

if(!defined('DS'))
  define('DS', DIRECTORY_SEPARATOR);

if (version_compare(JVERSION, '3.0.0', 'lt')) {
  require(JPATH_SITE.'/components/com_seftranslate/languages.conf.php');
  require(JPATH_SITE.'/administrator/components/com_seftranslate/install.seftranslate.helper.php');

  //print_r($seftranslate_configuration);
  $GLOBALS['seftranslate_configuration'] = $seftranslate_configuration ;

  include_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
  include_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
} else {

  include_once(rtrim(JPATH_SITE,DS).DS.'components'.DS.'com_seftranslate'.DS.'languages.conf.php');
  include_once(rtrim(JPATH_ADMINISTRATOR,DS).DS.'components'.DS.'com_seftranslate'.DS.'install.seftranslate.helper.php');
  //print_r($seftranslate_configuration);
  $GLOBALS['seftranslate_configuration'] = $seftranslate_configuration ;

  include_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
  include_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
}
$GLOBALS['mosConfig_live_site'] = $mosConfig_live_site = JURI::base() ; 
/*
if (version_compare(JVERSION, '3.0.0', 'ge')) {
com_install();
} */

if ( !(function_exists('update_to_version_3_1')) ) {
  function update_to_version_3_1(){
    return;
  }
}
if ( !(function_exists('update_to_version_3_0')) ) {
  function update_to_version_3_0(){
    
    $database = JFactory::getDBO();

    echo "Creating table for file html page save... ";
    $query= "CREATE TABLE IF NOT EXISTS `#__seftranslate_files` (
                  `id` int(11) unsigned NOT NULL auto_increment,
                  `url` varchar(255) NOT NULL default '',
                  `file_name` varchar(255) NOT NULL default '',
                  `lang` varchar(55) NOT NULL default '',
                  `hash_org` varchar(35) NOT NULL default '',
                  `hash_dest` varchar(35) NOT NULL default '',
                  `cookies` TEXT NOT NULL default '',
                  `posts` TEXT NOT NULL default '',
                  `manual_translated` tinyint(1) NOT NULL default '0',
                  `use_as_base_url` tinyint(1) NOT NULL default '0',
                  `use_orig_text` tinyint(1) NOT NULL default '0',
                  `date` datetime NOT NULL default '0000-00-00 00:00:00',
                  `hits` int(11) NOT NULL default '0',
                  `published` tinyint(1) NOT NULL default '1',
                  `approved` tinyint(1) NOT NULL default '1',
                  `checked_out` int(11) NOT NULL default '0',
                  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
                  PRIMARY KEY  (`id`),
                  INDEX (`hash_dest`),
                  INDEX (`hash_org`),
                  INDEX (`lang`),
                  INDEX (`url`,`lang`)
                  ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARACTER SET utf8 ";
    $database->setQuery($query);
    $database->query();
    echo $database->getErrorMsg();
    if (array_search($database->getPrefix() . "seftranslate_files", $database->getTableList(), true))
      echo "[Ok]<br />";
    else
      exit;

    echo "Creating table for connect urs... ";
    $query = "CREATE TABLE IF NOT EXISTS #__seftranslate_url_connect (
                  `id` int(11) unsigned NOT NULL auto_increment,
                  `url_origin` varchar(255) NOT NULL default '',
                  `url_dest` varchar(255) NOT NULL default '',
                  `lang` varchar(55) NOT NULL default '',
                  `hash_org` varchar(35) NOT NULL default '',
                  `hash_dest` varchar(35) NOT NULL default '',
                  `manual_translated` tinyint(1) NOT NULL default '0',
                  `date` datetime NOT NULL default '0000-00-00 00:00:00',
                  `checked_out` int(11) NOT NULL default '0',
                  `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
                  PRIMARY KEY  (`id`),
                  INDEX (`hash_dest`),
                  INDEX (`hash_org`),
                  INDEX (`lang`),
                  INDEX (`url_origin`,`lang`),
                  INDEX (`url_dest`,`lang`)
                  ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARACTER SET utf8 ";
    $database->setQuery($query);
    $database->query();
    echo $database->getErrorMsg();
    if (array_search($database->getPrefix() . "seftranslate_url_connect", $database->getTableList(), true))
      echo "[Ok]<br />";
    else
      exit;

    return;
  }
}
if ( !(function_exists('update_to_version_5_0')) ) {
  function update_to_version_5_0() {
      $database = JFactory::getDBO();

      $query = "CREATE TABLE IF NOT EXISTS  `#__seftranslate_api` (
              `id` int(11) NOT NULL AUTO_INCREMENT,
              `service` varchar(255) NOT NULL,
              `key` text NOT NULL,
              PRIMARY KEY  (`id`)
          ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARSET=utf8;";
      $database->setQuery($query);
      $database->execute();   


      return;
  }
}

if ( !(function_exists('com_sef_install2')) ) {
  function com_sef_install2(){
    com_sef_install();
  }
}

if ( !(function_exists('com_sef_install')) ) {
  function com_sef_install()
  {
    global $seftranslate_configuration, $mosConfig_live_site;

    //**************************   begin check version PHP   **************************
    $is_warning = false;

    if ( (phpversion()) < 5 ) {
    ?>
    <center>
      <table width="100%" border="0">
        <tr>
          <td>
            <code>Installation status: <font color="red">fault</font></code>
          </td>
        </tr>
        <tr>
          <td>
            <code><font color="red">This component works correctly under PHP version 5.0 and higher.</font></code>
          </td>
        </tr>
      </table>
    </center>

    <?php
      return '<h2><font color="red">Component installation fault</font></h2>';
    }
  //**********************   end check version PHP   ***********************


    //******************   database ******************
    $database = JFactory::getDBO();
    $tableList = $database->getTableList();
    echo 'cheking database<br/>';
    $prefix=$database->getPrefix();
    if (array_search($prefix.'seftranslate_version',$tableList)){
      $database->setQuery("SELECT * FROM #__seftranslate_version");
      $version = $database->loadAssoc();
    }


    if (isset($version)){

      echo 'database already exist<br/>';
      if(isset($version['number'])){    //если база данных есть,  и она новая
        //nothin to do
        //print_r($version);
        echo "Update<br />";
        $version =  floatval($version['number'] . "." . $version['version']) ;
        print_r($version);
        switch ($version) {
            case 2.1:

              update_to_version_3_0();
              update_to_version_3_1();

              break;
            case 3.0:
              update_to_version_3_1();
              break;
            case 3.1:
            case 4.2:
            case 4.3:
            case 4.4:
            case 4.5:
            case 4.6:
            case 4.7:
            case 4.8:
              update_to_version_5_0();
              break;



              default:
              break;
        }
        echo 'end upgrading database';

        $vers = explode('.',$seftranslate_configuration['release']['version']);
        $query= "UPDATE #__seftranslate_version
                  set version= '".$vers[1]."', number ='".$vers[0]."'
                  WHERE id = 2";
        $database->setQuery($query);
        $database->query();
      }
    }
    else {//если базы данных нет, создаем её

      echo 'install database <br/>';

      $query = "DROP TABLE IF EXISTS `#__seftranslate_urls`, `#__seftranslate_entity`,
         `#__seftranslate_version`, `#__seftranslate_files`, `#__seftranslate_url_connect`";
      $database->setQuery($query);
      $database->query();
      echo $database->getErrorMsg();


      echo "Creating table for database html page save... ";
      $query= "CREATE TABLE IF NOT EXISTS `#__seftranslate_urls` (
                `id` int(11) unsigned NOT NULL auto_increment,
                `url` varchar(255) NOT NULL default '',
                `lang` varchar(55) NOT NULL default '',
                `hash_org` varchar(35) NOT NULL default '',
                `hash_dest` varchar(35) NOT NULL default '',
                `page_text` mediumblob,
                `cookies` TEXT NOT NULL default '',
                `posts` TEXT NOT NULL default '',
                `manual_translated` tinyint(1) NOT NULL default '0',
                `use_as_base_url` tinyint(1) NOT NULL default '0',
                `use_orig_text` tinyint(1) NOT NULL default '0',
                `date` datetime NOT NULL default '0000-00-00 00:00:00',
                `hits` int(11) NOT NULL default '0',
                `published` tinyint(1) NOT NULL default '1',
                `approved` tinyint(1) NOT NULL default '1',
                `checked_out` int(11) NOT NULL default '0',
                `checked_out_time` datetime NOT NULL default '0000-00-00 00:00:00',
                PRIMARY KEY  (`id`),
                INDEX (`hash_dest`),
                INDEX (`hash_org`),
                INDEX (`lang`),
                INDEX (`url`,`lang`)
                ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARACTER SET utf8 ";
      $database->setQuery($query);
      $database->query();
      echo $database->getErrorMsg();
      if (array_search($database->getPrefix() . "seftranslate_urls", $database->getTableList(), true))
        echo "[Ok]<br />";
      else
        exit;

      echo "Creating table for entity_text page save... ";
      $query= "CREATE TABLE IF NOT EXISTS `#__seftranslate_entity` (
                `id` int(11) unsigned NOT NULL auto_increment,
                `lang_from` varchar(10) NOT NULL default '',
                `lang_to` varchar(10) NOT NULL default '',
                `hash` varchar(35) NOT NULL default '',
                `entity_text` mediumblob,
                `date` datetime NOT NULL default '0000-00-00 00:00:00',
                `hits` int(11) NOT NULL default '0',
                PRIMARY KEY  (`id`),
                INDEX (`hash`,`lang_from`,`lang_to`)
                ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARACTER SET utf8 ";
      $database->setQuery($query);
      $database->query();
      echo $database->getErrorMsg();
      if (array_search($database->getPrefix() . "seftranslate_entity", $database->getTableList(), true))
        echo "[Ok]<br />";
      else
        exit;

      echo "Creating table for sef translate version save... ";
      $query= "CREATE TABLE IF NOT EXISTS #__seftranslate_version (
                `id` int(11) unsigned NOT NULL auto_increment,
                `version` varchar(11) NOT NULL default 0,
                `number` varchar(11),
                PRIMARY KEY  (`id`),
                INDEX (`id`)
                ) ENGINE=MyISAM AUTO_INCREMENT=2  CHARACTER SET utf8 ";
      $database->setQuery($query);
      $database->query();
      echo $database->getErrorMsg();
      if (array_search($database->getPrefix() . "seftranslate_version", $database->getTableList(), true))
        echo "[Ok]<br />";
      else
        exit;


      update_to_version_3_0();
      update_to_version_3_1();
      update_to_version_5_0();

      $vers = explode('.',$seftranslate_configuration['release']['version']);
      $query= "INSERT INTO #__seftranslate_version (`version`,`number`) VALUES ('$vers[1]','$vers[0]')";
      $database->setQuery($query);
      $database->query();
      echo $database->getErrorMsg();
      echo "end install database<br/>";
   }

  //******************   end database ******************


  //*********************   begin check CURL extension   ******************
  if ( !(function_exists('curl_init')) ) {
    $is_warning = true;
    ?>
    <center>
    <table width="100%" border="0">
      <tr>
        <td>
          <code><font color="red">CURL extension not found! In order for translate page, you need to compile PHP with support for the CURL extension!</font></code>
        </td>
      </tr>
    </table>
    </center>
    <?php
  }
  //********************   end check CURL extension   ************************
  //**********************   begin check mbstring extension   ********************
  if ( !(function_exists('mb_detect_order')) ) {
    $is_warning = true;
    ?>
    <center>
    <table width="100%" border="0">
      <tr>
        <td>
          <code><font color="red">MBSTRING extension not found! In order for translate page, you need to compile PHP with support for the MBSTRING extension!</font></code>
        </td>
      </tr>
    </table>
    </center>
    <?php
  }
  //********************   end check mbstring extension   *************************
  //**********************   begin check SOAP extension   ********************
  if ( !(class_exists('SoapClient')) ) {
    $is_warning = true;
    ?>
    <center>
    <table width="100%" border="0">
      <tr>
        <td>
          <code><font color="red">SOAP extension not found! In order for translate page with help BING API, you need to compile PHP with support for the SOAP extension!</font></code>
        </td>
      </tr>
    </table>
    </center>
    <?php
  }
  //********************   end check SOAP extension   *************************

  SEFInstallHelper::setAdminMenuImages();
  ?>
    <center>
    <table width="100%" border="0">
      <tr>
        <td>
          <strong>Sef Translate</strong><br/>
          <br/>
          This component is published under the <a
           href="<?php echo $mosConfig_live_site."components/com_seftranslate/doc/LICENSE.txt"; ?>"
           target="new">License</a>.
        </td>
      </tr>
    </table>
    </center>

<?php
  if( !$is_warning ) {

    # Show installation result to user
    ?>
    <center>
    <table width="100%" border="0">
      <tr>
        <td>
          <code>Installation: <font color="green">succesful</font></code>
        </td>
      </tr>
    </table>
    </center>

    <?php
  }
  if($is_warning) return '<h2><font color="red">The SefTranslate Component installed with a warning about a missing PHP extension! Please read carefully and uninstall SefTranslate. Next fix your PHP installation and then install SefTranslate again.</font></h2>';
  }
}
?>
