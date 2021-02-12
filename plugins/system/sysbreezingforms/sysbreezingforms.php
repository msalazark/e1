<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.log
 *
 * @copyright   Copyright (C) 2005 - 2015 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Joomla! System Logging Plugin.
 *
 * @since  1.5
 */
class PlgSystemSysbreezingforms extends JPlugin
{

    public function onBeforeRender(){

        if(!file_exists(JPATH_ADMINISTRATOR . '/components/com_breezingforms/breezingforms.php')){ return; }

        $app = JFactory::getApplication();

        try {

            if( JFactory::getApplication()->isAdmin() &&
                (
                    !file_exists(JPATH_SITE.'/media/breezingforms/free_notice.txt') &&
                    (
                        $app->input->getString('option') == 'com_breezingforms' &&
                        $app->input->getString('act', '') != '' &&
                        $app->input->getString('act', '') != 'configuration'
                    )
                )
            ){
                if(JRequest::getInt('bf_noticed', 0) == 1){
                    $msg = ' ';
                    @file_put_contents(JPATH_SITE.'/media/breezingforms/free_notice.txt', $msg);
                    return;
                }

                $message = 'Did you know?<br/>There is BreezingForms Pro - <span style="text-decoration: underline;">no footer</span>, <span style="text-decoration: underline;">latest features</span>, <span style="text-decoration: underline;">priority support</span> and <span style="text-decoration: underline;">BreezingForms Pro for WordPress</span> included!';
                $message .= '<br/><strong><a href="https://crosstec.org/en/downloads/joomla-forms.html" target="_blank">Get it from here</a></strong><br/>';
                $message .= '<br/><a href="'.Juri::getInstance()->toString().'&amp;bf_noticed=1">Ok, I got it.</a>';

                JFactory::getApplication()->enqueueMessage($message);
            }

        }catch(Exception $e){

        }catch(Error $e){

        }
    }

    public function onAfterRender()
    {

        if(!file_exists(JPATH_ADMINISTRATOR . '/components/com_breezingforms/breezingforms.php')){ return; }

        $app = JFactory::getApplication();

        if( $app->input->getString('option') == 'com_menus' && $app->input->getString('view') == 'items' ){

            $body = JResponse::getBody();
            $body = str_replace('&lt;img src=../administrator/components/com_breezingforms/images/icons/component-menu-icons/bf_icon.png width=23px; /&gt;', '', $body);
            $body = str_replace('&lt;img src=../administrator/components/com_breezingforms/images/icons/component-menu-icons/bf_icon.png width=23; /&gt;', '', $body);
            JResponse::setBody($body);
        }
    }
}
