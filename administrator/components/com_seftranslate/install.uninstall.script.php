<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) { die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); }

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

class com_SefTranslateInstallerScript{
    /**
     * method to install the component
     *
     * @return void
     */
    function install($parent){
        // $parent is the class calling this method
    }
 
    /**
     * method to uninstall the component
     *
     * @return void
     */
    function uninstall($parent){
        // $parent is the class calling this method
        if (version_compare(JVERSION, '3.0.0', 'gt')
            && file_exists(dirname(__FILE__) . "/uninstall.seftranslate.php") ) {
          require_once(dirname(__FILE__) ."/uninstall.seftranslate.php");
        }
    }
 
    /**
     * method to update the component
     *
     * @return void
     */
    function update($parent){
        // $parent is the class calling this method
    }

    /**
     * method to run before an install/update/uninstall method
     *
     * @return void
     */
    function preflight($type, $parent){
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        
        // Save admin settings before updete
        if ( $type == 'update' ) {
            // $realestatemanager_configuration:
            if(is_file(JPATH_ROOT . '/components/com_seftranslate/languages.conf.php') ) {
                copy(JPATH_ROOT . '/components/com_seftranslate/languages.conf.php', JPATH_ROOT . '/components/com_seftranslate/languages.conf.php_bak');
            }
        }    

    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent){
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)


        if ( $type == 'update' ) {
            if(is_file(JPATH_ROOT . '/components/com_seftranslate/languages.conf.php_bak') ) {
                copy(JPATH_ROOT . '/components/com_seftranslate/languages.conf.php_bak', JPATH_ROOT . '/components/com_seftranslate/languages.conf.php');
            }
        }

        if (version_compare(JVERSION, '3.0.0', 'gt')
            && file_exists(dirname(__FILE__) . "/install.seftranslate.php") ) {
          require_once(dirname(__FILE__) . "/install.seftranslate.php");
          com_sef_install2();
        }

    }
}
