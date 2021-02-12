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

if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );

require(JPATH_SITE.'/components/com_seftranslate/languages.conf.php');  

$db = JFactory::getDBO();
if($seftranslate_configuration['update']){
  echo "Database saved<br/>";
}
else{
  echo 'no update<br/>';
  $query="DROP TABLE IF EXISTS `#__seftranslate_urls`";
  $query="DROP TABLE IF EXISTS `#__seftranslate_entity`";
  $query="DROP TABLE IF EXISTS `#__seftranslate_files`";
  $query="DROP TABLE IF EXISTS `#__seftranslate_url_connect`";
  $query="DROP TABLE IF EXISTS `#__sef_translate_version`";
  $db->setQuery($query);
  $db->query();
 
}
function com_uninstall()
{
  echo "Uninstalled! ";
}