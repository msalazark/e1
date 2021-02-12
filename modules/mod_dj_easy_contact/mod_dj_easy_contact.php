<?php
/**
 * @version $Id: mod_dj_easy_contact.php 20 2015-02-06 15:57:45Z marcin $
 * @package DJ-EasyContact
 * @copyright Copyright (C) 2012 DJ-Extensions.com, All rights reserved.
 * @license http://www.gnu.org/licenses GNU/GPL
 * @author url: http://dj-extensions.com
 * @author email contact@dj-extensions.com
 * @developer Marcin Łyczko - marcin.lyczko@design-joomla.eu
 *
 *
 * DJ-EasyContact is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DJ-EasyContact is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DJ-EasyContact. If not, see <http://www.gnu.org/licenses/>.
 *
 */
 
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

// get Joomla version
$version = new JVersion;
$jversion = '3';
if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
    $jversion = '2.5';
}

// get module ID
$moduleId = $module->id;
        
// get style
$style = $params->get('styles', '');
if ($style == 0){
	$style_file = '1';
} else if($style == 1){
	$style_file = '2';
} else if($style == 2){
	$style_file = '3';
} else if($style == 3){
	$style_file = '4';
} else if($style == 4){
	$style_file = '5';
}

// load stylesheet
JHtml::stylesheet(JUri::base().'modules/mod_dj_easy_contact/assets/mod_dj_easy_contact.css', array(), true);
JHtml::stylesheet(JUri::base().'modules/mod_dj_easy_contact/assets/style'.$style_file.'.css', array(), true);

// add scripts
$doc = JFactory::getDocument();
$jquery= $params->get('jquery', '');
if($jquery == 1){
    if ($jversion == '3') {
        JHtml::_('jquery.framework', true);
    } else {
        $doc->addScript('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js');
    }
}

//get language code
$lang = JFactory::getLanguage();
$lang_tag = $lang->getTag();
$lang_code_array = explode("-", $lang_tag);
$lang_code =  $lang_code_array[0];

//Email Parameters
$email_required = $params->get('email_required', true);
$recipient = $params->get('email_recipient', '');
$fromEmail = @$params->get('from_email', '');
$sendersname = @$params->get('from_email_name', '');
$mySubject = $params->get('email_subject', '');
$redirectURLSwitch = $params->get('redirect_url_switch', false);
$redirectURL = $params->get('redirect_url', '');
$redirectJS = "";
if(($redirectURLSwitch == true) && ($redirectURL != "")){
	$redirectJS = 'jQuery(location).attr("href", "'.$redirectURL.'");';
}

// Text Parameters
$introtext = $params->get('introtext', '');
$message_type= $params->get('message_type', true);
$message_label= $params->get('message_label', true);

// Consent parameters
$rodo_enabled = $params->get('rodo_enabled', false);
$rodo_text = $params->get('rodo_text', false);
$rodo_enabled2 = $params->get('rodo_enabled2', false);
$rodo_text2 = $params->get('rodo_text2', false);

// Captcha Parameters
$enable_anti_spam = $params->get('enable_anti_spam', true);
$recaptcha_site_key = $params->get('recaptcha_site_key', '');
$recaptcha_secret_key = $params->get('recaptcha_secret_key', '');
$invisible_captcha_badge = $params->get('invisible_captcha_badge', '');
if ($invisible_captcha_badge == 0){
	$invisible_captcha_badge_class = 'bottomleft';
} else if($invisible_captcha_badge == 1){
	$invisible_captcha_badge_class = 'bottomright';
} else if($invisible_captcha_badge == 2){
	$invisible_captcha_badge_class = 'inline';
} else if($invisible_captcha_badge == 3){
	$invisible_captcha_badge_class = 'csshidden';
}

// thanks options
$email_thanks = $params->get('email_thanks', true);
$email_thanks_subject = $params->get('thanks_subject', '');
$email_thanks_message = $params->get('email_thanks_message', '');

// Module Class Suffix Parameter
$mod_class_suffix = $params->get('moduleclass_sfx', '');

$error_message = '';
$valid_name = '';
$valid_email = '';
$valid_message = '';

$script = '
(function($){
	$(document).ready(function(){
		$("#dj-easy-contact-form-'.$moduleId.' .button.submit").click(function(event){
			event.preventDefault();
			var form = $(this).parents("form").get(0);
			var isValid = document.formvalidator.isValid($(this).parents("form").get(0));

			if (isValid) {
				'.( ($enable_anti_spam == 2) ? 'grecaptcha.execute();' : 'DJEasyContactSubmit'.$moduleId.'();').'
				return;
			} 
		});

		window.DJEasyContactSubmit'.$moduleId.' = function(){
			var form = $("#dj-easy-contact-form-'.$moduleId.'");
			form.trigger("submit");
		};

		$("#dj-easy-contact-form-'.$moduleId.'").submit(function(event){
			event.preventDefault();
			var form = $(this);
			var formData =  form.serialize();
			jQuery.ajax({
				type: "POST",
				async: false,
				url : form.action,
				data : formData
			}).done(function(response) {
				if(response=="OK"){
					if('.$style.' == "4"){
						jQuery( ".modal-body .modal-dj-easy-contact-message" ).append( "<div class=\'alert alert-success\'><button type=\'button\' data-dismiss=\'alert\' class=\'close\'>×</button><div>'.JText::_('MOD_DJ_EASYCONTACT_AJAX_OK').'</div></div>" );
						jQuery( ".modal-body .modal-dj-easy-contact-message .alert-error" ).remove();
						jQuery( ".modal-body .dj-simple-contact-form" ).hide();
						jQuery( "#modal-dj-easy-contact-box .close, .modal-backdrop" ).click(function() {
						  jQuery( ".modal-dj-easy-contact-message .alert" ).remove();
						  jQuery( ".modal-body .dj-simple-contact-form" ).show();
						});
					} else{
						alert("'.JText::_('MOD_DJ_EASYCONTACT_AJAX_OK').'");
						jQuery( "#system-message-container .alert" ).remove();
					}
					form.find(".dj-simple-contact-form-row input, .dj-simple-contact-form-row textarea").each(function(posi, input){
						jQuery(input).val("");
						jQuery("input[type=\'checkbox\']").prop("checked", false);
					});	
					'.$redirectJS.'
				}else{
					alert(response);
				}
			}).fail(function(xhr, status, error) {
				if (error != "") {
					alert(error);
				} else {
					alert("'.JText::_('MOD_DJ_EASYCONTACT_AJAX_FAIL').'");   
				}
			});
		});
		if('.$style.' == "4"){
			jQuery(".dj-easy-contact-modal-button").on("click", function(e) {
				e.preventDefault();
				jQuery("html, body").animate({scrollTop: 0}, 500);
			});
			
			// copy messages from message container to modal
			modalBody = jQuery(".modal-body");
			var submitBtn = modalBody.find(".dj-simple-contact-form .button");
			submitBtn.click(function(event){
				var msgCont = jQuery("#system-message-container");
				var msgContDiv = jQuery("#system-message-container div");
				if (msgContDiv.length > 0) {
					var rvMsgCont = jQuery(".modal-body .modal-dj-easy-contact-message");
					rvMsgCont.html(msgCont.html());
					msgCont.html(" ");
				}
			});
			
			// modal fix for email label
			var emailField = modalBody.find(".dj-simple-contact-form-row.email");
			if (emailField.length > 0) {
				if (jQuery(".dj-simple-contact-form .email input").val().length == 0) {
				      jQuery(".dj-simple-contact-form .email input").addClass("empty-email-field");
				}
				jQuery( "#modal-dj-easy-contact-box, .dj-simple-contact-form.button" ).click(function() {
					if (jQuery(".dj-simple-contact-form .email input").val().length == 0) {
					      jQuery(".dj-simple-contact-form .email input").addClass("empty-email-field");
					} else {
						jQuery(".dj-simple-contact-form .email input").removeClass("empty-email-field");
					}
				});
			}
		}
	});
})(jQuery);
';

$doc->addScriptDeclaration($script);

if ($enable_anti_spam == 1 || $enable_anti_spam == 2) {
	
	// removing plg_captcha_recaptcha/recaptcha.min.js and existing api.js script 
	// we have a workaround for that
	
	$headerdata = $doc->getHeadData();
	$scripts = $headerdata['scripts'];
	$headerdata['scripts'] = array();
	
	$plgPath = preg_quote('media/plg_captcha_recaptcha/js/', '/');
	$apiPath = preg_quote('https://www.google.com/recaptcha/api.js', '/');
	foreach ($scripts as $url => $type) {
		if (preg_match('#' .$plgPath. '#s', $url) == false 
			&& preg_match('#' .$apiPath. '#s', $url) == false) {
			$headerdata['scripts'][$url] = $type;
		}
	}
	$doc->setHeadData($headerdata);
	
	// Adding our own reCaptcha init script and API
	$doc->addScript(JUri::base().'modules/mod_dj_easy_contact/assets/recaptcha_init.js');
	JHtml::script('https://www.google.com/recaptcha/api.js?hl='.$lang_code.'&amp;onload=DJEasyContactInitCaptcha&amp;render=explicit');
}

require JModuleHelper::getLayoutPath('mod_dj_easy_contact', $params->get('layout', 'default'));
?>