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
if(!defined('DS'))  define('DS', DIRECTORY_SEPARATOR);

/**
* mosSefurl database table class
*/
jimport( 'joomla.database.table');
class mosSefurl extends JTable {

//keys
  /** @var int Primary key */
  var $id=null;
  /** @var varchar(255) */
  var $url=null;
  /** @var varchar(55) */
  var $lang=null;
  /** @var varchar(35) */
  var $hash_org=null;
  /** @var varchar(35) */
  var $hash_dest=null;
  /** @var BLOB */
  var $page_text=null;
  /** @var TEXT */
  var $cookies=null;
  /** @var TEXT */
  var $posts=null;
  /** @var boolean */
  var $manual_translated=null;
  /** @var boolean */
  var $use_as_base_url=null;
  /** @var boolean */
  var $use_orig_text=null;
  /** @var datetime */
  var $date=null;
  /** @var int */
  var $hits=null;
  /** @var boolean */
  var $published=null;
  /** @var boolean */
  var $approved=null;
  /** @var boolean */
  var $checked_out=null;
  /** @var checked_out_time */
  var $checked_out_time=null;

  /**
  * @param database - A database connector object
  */
  function __construct( &$db ) {
    parent::__construct( '#__seftranslate_urls', 'id', $db );
  }

  function loadForBaseUrl($hash_org){

    $this->_db->setQuery("SELECT id FROM #__seftranslate_urls WHERE hash_org = '$hash_org' and use_as_base_url=1 ");
    $id = intval( $this->_db->loadResult() );
    if ($id) {
      $this->load($id );
      return $this;
    }
    else return null;
  }

  function loadForHashDest($hash_dest){

    $this->_db->setQuery("SELECT id FROM #__seftranslate_urls WHERE hash_dest = '$hash_dest' ");
    $id = intval( $this->_db->loadResult() );
    if ($id) {
      $this->load($id );
      return $this;
    }
    else return null;
  }
}
