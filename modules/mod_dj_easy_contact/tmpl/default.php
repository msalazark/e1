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
// getting form fields
$input = JFactory::getApplication()->input;

// get Joomla version
$version = new JVersion;
$jversion = '3';
if (version_compare($version->getShortVersion(), '3.0.0', '<')) {
    $jversion = '2.5';
}

// class for captcha
if($enable_anti_spam == 0){
	$antispam_class="disabled-captcha";
} else if($enable_anti_spam == 1){
	$antispam_class="no-captcha";
} else if($enable_anti_spam == 2){
	$antispam_class="invisible-captcha";
}

// add library for form validation
JHTML::_('behavior.formvalidation');

// message for Joomla 2.5 when style 5 is selected
if($style_file == '5'){
	if($jversion == '2.5'){
		echo JText::_('MOD_DJ_EASYCONTACT_JOOMLA_OLD_MESSAGE');
		return;
	}
	JHTML::_('behavior.modal');
}

$dj_name = $input->post->getString('dj_name');
if(isset($_POST['dj-easy-contact-send-' . $moduleId])) {
  $valid_name = htmlentities($dj_name, ENT_COMPAT, "UTF-8");
  $dj_message = $input->post->getString('dj_message');
  if($dj_message){
  	$valid_message = htmlentities($dj_message, ENT_COMPAT, "UTF-8");
  }	
  
  $dj_email = $input->post->getString('dj_email');
  if($dj_email){
  	$valid_email = htmlentities($dj_email, ENT_COMPAT, "UTF-8");
  }
  
	if ($enable_anti_spam == 1 || $enable_anti_spam == 2) {
		$g_recaptcha_response = $input->post->getString('g-recaptcha-response');
		if($g_recaptcha_response){
	      $captcha = $g_recaptcha_response;
	    }
		/*$contextOpts = array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false
            )
        );*/
		//$response=file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret='".$recaptcha_secret_key."'&response=".$captcha."&remoteip=".$_SERVER['REMOTE_ADDR'], false, stream_context_create($contextOpts));
		
	    // CURL Check
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify');
	    $captchaPost = array(
	    	'secret' => $recaptcha_secret_key,
	    	'response' => $captcha,
	    	'remote_id' => $_SERVER['REMOTE_ADDR']
	    );
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE );
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt( $ch, CURLOPT_POST, TRUE );
	    curl_setopt( $ch, CURLOPT_POSTFIELDS, $captchaPost );
	    $response = curl_exec($ch);
	    
	    if ($response === false) {
	    	header("Content-type: text/html; charset=utf-8");
	    	if (!count(array_diff(ob_list_handlers(), array('default output handler'))) || ob_get_length()) {
	    		while(@ob_end_clean());
	    	}
	    	echo JText::_('MOD_DJ_EASYCONTACT_WRONG_CAPTCHA');
	    	die();
	    }
	    // CURL Check end
	    
    	$result_captcha = json_decode( $response, true );
    	if($result_captcha['success'] != 1){
    		header("Content-type: text/html; charset=utf-8");
    		if (!count(array_diff(ob_list_handlers(), array('default output handler'))) || ob_get_length()) {
    			while(@ob_end_clean());
    		}
    		echo JText::_('MOD_DJ_EASYCONTACT_WRONG_CAPTCHA');
    		die();
    		$error_message .=  ' <span class="error-dj-simple-contact-form">'.JText::_('MOD_DJ_EASYCONTACT_WRONG_CAPTCHA').'</span>';
    	}
	    
	 }

	// get useful info
	$current_url = JText::_('MOD_DJ_EASYCONTACT_SEND_FROM').' '.JURI::current();
	$user_ip = JText::_('MOD_DJ_EASYCONTACT_IP').' '.$_SERVER['REMOTE_ADDR'];
 	jimport('joomla.environment.browser');
    $doc = JFactory::getDocument();
    $browser = JBrowser::getInstance();
    $browserType = JText::_('MOD_DJ_EASYCONTACT_BROWSER_TYPE').' '.$browser->getBrowser();
    $browserVersion = JText::_('MOD_DJ_EASYCONTACT_BROWSER_VERSION').' '.$browser->getMajor();
	$full_agent_string = JText::_('MOD_DJ_EASYCONTACT_FULL_AGENT_STRING').' '.$browser->getAgentString();
	$agreement = '';
	$agreement2 = '';
	if($rodo_enabled){
		$agreement = JText::_('MOD_DJ_EASYCONTACT_AGREEMENT').' '.strip_tags( $rodo_text );
	}
	if($rodo_enabled2){
		$agreement2 = JText::_('MOD_DJ_EASYCONTACT_AGREEMENT2').' '.strip_tags( $rodo_text2 );
	}
	
    if($email_required == 1){
	$message_text = JText::_('MOD_DJ_EASYCONTACT_MESSAGE_INFO'). ' ' . $dj_name.' - '.$dj_email ."\n\n". $dj_message."\n\n".$current_url."\n\n".$user_ip
	."\n\n".$browserType."\n\n".$browserVersion."\n\n".$full_agent_string."\n\n".$agreement."\n\n".$agreement2;
    } else {
    	$message_text = $dj_message."\n\n".$current_url."\n\n".$user_ip
		."\n\n".$browserType."\n\n".$browserVersion."\n\n".$full_agent_string."\n\n".$agreement."\n\n".$agreement2;
    }
   
	// sending email to admin
    $mailSender = JFactory::getMailer();
    $mailSender->setSender(array($fromEmail,$dj_name));
	$mailSender->addRecipient($recipient);
	if($email_required){
		$mailSender->addReplyTo($dj_email, '' );
	}
    $mailSender->setSubject($mySubject);
    $mailSender->setBody($message_text);
	$mailSender->send();

	// sending thanks message
	if($email_thanks && $email_required){
		$agreement_user = "";
		$agreement_user2 = "";
		if($rodo_enabled){
			$agreement_user = "\n\n".JText::_('MOD_DJ_EASYCONTACT_AGREEMENT')."\n".strip_tags( $rodo_text );
		}
		if($rodo_enabled2){
			$agreement_user2 = "\n\n".JText::_('MOD_DJ_EASYCONTACT_AGREEMENT2')."\n".strip_tags( $rodo_text2 );
		}
	
		$mailSender_thanks = JFactory::getMailer();
		$mailSender_thanks->addRecipient($dj_email);
		
	    $mailSender_thanks->setSender(array( $fromEmail, $sendersname ));
	    $mailSender_thanks->addReplyTo($fromEmail,'');
		
	    $mailSender_thanks->setSubject($email_thanks_subject);
	    $mailSender_thanks->setBody($email_thanks_message."\n\n".JText::_('MOD_DJ_EASYCONTACT_YOUR_MESSAGE')."\n".$dj_message.$agreement_user.$agreement_user2);
		
	    $mailSender_thanks->Send();		
	}
    if (!count(array_diff(ob_list_handlers(), array('default output handler'))) || ob_get_length()) {
        while(@ob_end_clean());
    }
	echo 'OK';
    jexit();
} 



// check recipient
if ($recipient === "") {
  $myReplacement = '<span class="error-dj-simple-contact-form">'.JText::_('MOD_DJ_EASYCONTACT_NO_RECIPIENT').'</span>';
  print $myReplacement;
  return true;
}

// add class special class if email field is active or not
$is_email_field_on = 'email-field-active';
if($email_required != 1){
	$is_email_field_on = 'email-field-not-active';
} ?>

<?php if($style_file == '5'){ ?>
	<div id="modal-dj-easy-contact-box" class="modal hide fade">
	  <div class="modal-header">
	    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span>&nbsp;</span></button>
	    <h3 id="myModalLabel"><?php echo JText::_('MOD_DJ_EASYCONTACT_MODAL_HEADER'); ?></h3>
	  </div>
	  <div class="modal-body">
	  		<div class="modal-dj-easy-contact-message"></div>
<?php } ?>

			<div class="dj-simple-contact-form style-<?php echo $style_file.' '.$mod_class_suffix.' '.$is_email_field_on.' badge-'.$invisible_captcha_badge_class; ?>">
				<form class="easy-contact-form form-validate <?php echo $antispam_class; ?>" id="dj-easy-contact-form-<?php echo $moduleId; ?>" action="<?php echo JFactory::getURI(); ?>" method="post">
					<span class="dj-simple-contact-form-introtext"><?php echo $introtext; ?></span>
			
					<?php
					if ($error_message != '') {
					  print $error_message;
					}
					if($style_file != '5'){
						$placeholder_name = "placeholder='".JText::_('MOD_DJ_EASYCONTACT_NAME_LABEL')."'";
						$placeholder_email = "placeholder='".JText::_('MOD_DJ_EASYCONTACT_EMAIL_LABEL')."'";
						$placeholder_message = "placeholder='".$message_label."'";
					} else {
						$placeholder_name = "";
						$placeholder_email = "";
						$placeholder_message = "";
					} ?>

					<div class="dj-simple-contact-form">
			
						<?php // print name input ?>
						<div class="dj-simple-contact-form-row name">
							<input <?php echo $placeholder_name; ?> class="dj-simple-contact-form inputbox required <?php echo $mod_class_suffix; ?>" type="text" name="dj_name" id="dj_name-<?php echo $moduleId; ?>" value="<?php echo $valid_name; ?>" required="required" />
							<?php if($style_file == '5'){ ?>
								<span class="highlight"></span>
								<span class="bar"></span>
							<?php } ?>
							<label for="dj_name-<?php echo $moduleId; ?>" style="display: none;"><?php echo JText::_('MOD_DJ_EASYCONTACT_NAME_LABEL'); ?></label>
						</div>
						
						<?php // print email input ?>
						<?php if($email_required == 1){ ?>
							<div class="dj-simple-contact-form-row email">
								<input <?php echo $placeholder_email; ?> class="dj-simple-contact-form inputbox required validate-email <?php echo $mod_class_suffix; ?>" type="email" name="dj_email" id="dj_email-<?php echo $moduleId; ?>" value="<?php echo $valid_email; ?>" required="required" />
								<?php if($style_file == '5'){ ?>
									<span class="highlight"></span>
									<span class="bar"></span>
								<?php } ?>
								<label for="dj_email-<?php echo $moduleId; ?>" style="display: none;"><?php echo JText::_('MOD_DJ_EASYCONTACT_EMAIL_LABEL'); ?></label>
							</div>
						<?php } ?>
			
						<?php // print message input ?>
						<?php if($message_type == 1){ ?>
							<div class="dj-simple-contact-form-row message">
							<textarea <?php echo $placeholder_message; ?> class="dj-simple-contact-form textarea required <?php echo $mod_class_suffix; ?>" name="dj_message" id="dj_message-<?php echo $moduleId; ?>" cols="4" rows="4" required="required"><?php echo $valid_message; ?></textarea>
							<?php if($style_file == '5'){ ?>
								<span class="highlight"></span>
								<span class="bar"></span>
							<?php } ?>
							<label for="dj_message-<?php echo  $moduleId; ?>" style="display: none;"><?php echo $message_label; ?></label>
							</div>
						<?php } else { ?>
							<div class="dj-simple-contact-form-row message">
							<input <?php echo $placeholder_message; ?> class="dj-simple-contact-form inputbox required <?php echo  $mod_class_suffix; ?>" type="text" name="dj_message" id="dj_message-<?php echo $moduleId; ?>" value="<?php echo $valid_message; ?>" required="required" />
							<?php if($style_file == '5'){ ?>
								<span class="highlight"></span>
								<span class="bar"></span>
							<?php } ?>
							<label for="dj_message-<?php echo $moduleId; ?>" style="display: none;"><?php echo $message_label; ?></label>
							</div>
						<?php } ?>
						
						<?php // print agreement  checkbox ?>
						<?php if($rodo_enabled){ ?>
							<fieldset class="dj-simple-contact-form-row terms-conditions checkboxes required" id="terms_and_conditions" aria-required="true" required="required">
								<input type="checkbox" name="dj_easy_contact_terms_and_conditions_input" id="dj_easy_contact_terms_and_conditions_input-<?php echo  $moduleId; ?>" value="0">                	
							 	<label class="label_terms" for="dj_easy_contact_terms_and_conditions_input-<?php echo  $moduleId; ?>" id="terms_and_conditions-lbl"><?php echo $rodo_text; ?> *</label>
							</fieldset>
						<?php } ?>
						
						<?php if($rodo_enabled2){ ?>
							<fieldset class="dj-simple-contact-form-row terms-conditions2 checkboxes required" id="terms_and_conditions2" aria-required="true" required="required">
								<input type="checkbox" name="dj_easy_contact_terms_and_conditions_input2" id="dj_easy_contact_terms_and_conditions_input2-<?php echo  $moduleId; ?>" value="0">                	
							 	<label class="label_terms" for="dj_easy_contact_terms_and_conditions_input2-<?php echo  $moduleId; ?>" id="terms_and_conditions2-lbl"><?php echo $rodo_text2; ?> *</label>
							</fieldset>
						<?php } ?>
						
						<?php //print anti-spam ?>
						<?php /*if ($enable_anti_spam == 1 && $recaptcha_site_key && $recaptcha_secret_key) { ?>
						    <div class="captcha-box">
						    	<div class="g-recaptcha dj-easycontact-g-recaptcha" data-sitekey="<?php echo $recaptcha_site_key; ?>"></div>
						    </div>
						<?php }*/ ?>
						
						<?php if ( ($enable_anti_spam == 1 || $enable_anti_spam == 2) && $recaptcha_site_key && $recaptcha_secret_key ) { ?>
							<?php if ($enable_anti_spam == 1 ) {?>
								<div class="djeasycontact-g-recaptcha" id="djeasycontact-g-recaptcha-<?php echo $moduleId; ?>"
							          data-sitekey="<?php echo $params->get('recaptcha_site_key', ''); ?>"
							          data-size="normal"
							          data-callback="">
							    </div>
							<?php } else {?>
								<div class="djeasycontact-g-recaptcha" id="djeasycontact-g-recaptcha-<?php echo $moduleId; ?>"
							          data-sitekey="<?php echo $params->get('recaptcha_site_key', ''); ?>"
							          data-callback="<?php echo 'DJEasyContactSubmit'.$moduleId; ?>"
							          data-size="invisible">
							    </div>
							<?php } ?>
						<?php } ?>
						
						<?php // print button ?>
						<input type="hidden" name="dj-easy-contact-send-<?php echo $moduleId; ?>" value="true">
						<?php if($style_file == '5'){ ?>
							<div class="button-box">
								<button id="dj-easy-contact-send-<?php echo $moduleId; ?>" class="dj-simple-contact-form button submit <?php echo $mod_class_suffix; ?>">
									<span><?php echo JText::_('MOD_DJ_EASYCONTACT_BUTTON_LABEL'); ?></span>
								</button>
							</div>
						<?php } else { ?>
							<div class="button-box">
								<input id="dj-easy-contact-send-<?php echo $moduleId; ?>" class="dj-simple-contact-form button submit <?php echo $mod_class_suffix; ?>" type="submit" value="<?php echo JText::_('MOD_DJ_EASYCONTACT_BUTTON_LABEL'); ?>" />
							</div>
						<?php } ?>
					</div>
				</form>
			</div>
			
<?php if($style_file == '5'){ ?>
		</div>
	</div>
	<a href="#modal-dj-easy-contact-box" role="button" class="btn dj-easy-contact-modal-button" data-toggle="modal"><span>&nbsp;</span></a>
<?php } ?>