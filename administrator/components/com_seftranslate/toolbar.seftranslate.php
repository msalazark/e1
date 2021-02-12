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

//$appHelper =& JFactory::getApplicationHelper();

require_once(JPATH_SITE.'/administrator/components/com_seftranslate/toolbar.seftranslate.html.php');
$task=JRequest::getVar( 'task', "" );
	switch ($task) {

	case 'integration':
		menuST::ST_OTHER();
		break;
	case 'about':
		menuST::ST_OTHER();
	break;
	default :
		menuST::ST_EDIT();
}

		
			

?>