<?php
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) { die( 'Direct Access to '.basename(__FILE__).' is not allowed.' ); }

/**
 * FileName: seftranslate.php
* Date: 03/2017
* Development Aleksey Pakholkov, Andrey Kvasnevskiy - OrdaSoft(http://ordasoft.com)
* @package SefTranslate
* @copyright 2010 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);Aleksey Pakholkov
* Homepage: http://www.ordasoft.com
* @version: 5.0 free $
* @license GNU General Public license version 2 or later; see LICENSE.txt
**/

class pkg_SefTranslateInstallerScript{
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
    }

    /**
     * method to run after an install/update/uninstall method
     *
     * @return void
     */
    function postflight($type, $parent){
        // $parent is the class calling this method
        // $type is the type of change (install, update or discover_install)
        if (version_compare(JVERSION, '3.0.0', 'gt') ) {
              $is_warning = false ;

              //*********************   begin check CURL extension   ******************
              if ( !(function_exists('curl_init')) ) {
                $is_warning = true;
                ?>
                <center>
                <table width="100%" border="0">
                  <tr>
                    <td>
                      <code><font color="red">CURL extension not found!<br /> In order for translate page, you need to compile PHP with support for the CURL extension!</font></code>
                    </td>
                  </tr>
                </table>
                </center>
                <br />
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
                      <code><font color="red">MBSTRING extension not found!<br /> In order for translate page, you need to compile PHP with support for the MBSTRING extension!</font></code>
                    </td>
                  </tr>
                </table>
                </center>
                <br />
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
                      <code><font color="red">SOAP extension not found! <br />In order for translate page with help BING API, you need to compile PHP with support for the SOAP extension!</font></code>
                    </td>
                  </tr>
                </table>
                </center>
                <br />
                <?php
              }
              //********************   end check SOAP extension   *************************

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
                <br />

                <?php
              }

        }

    }
}
