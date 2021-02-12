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

/**
* mosSefentity database table class
*/
class mosSefentity extends JTable {

//keys

  /** @var int Primary key */
  var $id=null;
  /** @var varchar(10) */
  var $lang_from=null;
  /** @var varchar(10) */
  var $lang_to=null;
  /** @var varchar(35) */
  var $hash=null;
  /** @var BLOB */
  var $entity_text=null;
  /** @var datetime */
  var $date=null;
  /** @var int */
  var $hits=null;


  /**
  * @param database - A database connector object
  */
  function __construct( &$db ) {
    parent::__construct( '#__seftranslate_entity', 'id', $db );
  }


  function loadForHash($hash,$lang_from,$lang_to){

    $this->_db->setQuery("SELECT id FROM #__seftranslate_entity WHERE hash = '$hash' and lang_from = '$lang_from' and lang_to = '$lang_to' ");
    $id = intval( $this->_db->loadResult() );

    if ($id) {
      $this->load($id );
      return $this;
    }
    else return null;
  }  


}

