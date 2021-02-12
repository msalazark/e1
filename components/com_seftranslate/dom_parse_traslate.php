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


require_once(JPATH_SITE.'/components/com_seftranslate/GTranslate.php');
require_once(JPATH_SITE.'/components/com_seftranslate/sefurl.class.php');  
require_once(JPATH_SITE.'/components/com_seftranslate/sefentity.class.php' );
require_once(JPATH_SITE.'/components/com_seftranslate/languages.conf.php');
require_once(JPATH_SITE.'/components/com_seftranslate/bing_access_token_authentication.php' );

$GLOBALS['all_elements'] = $all_elements = array ();
$GLOBALS['glob_gt_id'] = 0;

function array_text_translate($arr_text,$lang_from='en',$lang_to='ru')
{

  global  $seftranslate_configuration;

  $translator =  $seftranslate_configuration['translator'] ;

  $ret_value = array();
  for($kk=0; $kk < count($arr_text); $kk++ ){

    //if empty or only digitals or only X (sings where to we replace strings) - no need traslate
    if(trim($arr_text[$kk],"X") == "" || ctype_digit($arr_text[$kk]) || trim($arr_text[$kk])=="") continue;

    $text_for_translate = preg_replace("/[[:cntrl:]]/i", "",trim($arr_text[$kk],"\x00..\x1F \t\n\r\0\x0B") ) ;
    if($text_for_translate == "" ) continue ;


    mb_detect_order("UTF-8,ISO-8859-1,windows-1252,iso-8859-15");
//     $ret_value[] = html_entity_decode(
//       mb_convert_encoding(
//         ereg_replace("[[:cntrl:]]", "",trim($arr_text[$kk],"\x00..\x1F \t\n\r\0\x0B") ),
//          'HTML-ENTITIES', mb_detect_encoding($arr_text[$kk]))  , ENT_NOQUOTES, 'UTF-8' );
    $ret_value[] = html_entity_decode(
      mb_convert_encoding(
        $text_for_translate,
         'HTML-ENTITIES', mb_detect_encoding($text_for_translate))  , ENT_NOQUOTES, 'UTF-8' );
  }

  if(count($ret_value) == 0) return $ret_value; 

  switch($translator){
    case 'bing' :
      $ret_value = bing_array_text_translate_v3($ret_value,$lang_from,$lang_to);
      break;
    case 'gtranslate' :
      $ret_value = gtranslate_array_text_translate($ret_value,$lang_from,$lang_to);
      break;
    default :
      echo "some error in translator choose, please connect to developers";
      break;
  }
  return $ret_value;
}


function bing_array_text_translate_v3($arr_text,$lang_from='en',$lang_to='ru'){
  global  $seftranslate_configuration,$seftranslate_error;

  //DBQuery
  $database = JFactory::getDBO();

  $ret_value = $arr_text;


  $lang_from2 = $lang_from;
  $lang_to2 = $lang_to;
  if( $lang_from == 'zh-CN' )  $lang_from2 = 'zh-CHS';
  if( $lang_to == 'zh-CN' )  $lang_to2 = 'zh-CHS';
  if( $lang_from == 'zh-TW' )  $lang_from2 = 'zh-CHT';
  if( $lang_to == 'zh-TW' )  $lang_to2 = 'zh-CHT';

  
  if (!empty($seftranslate_configuration['azure_subscription_key']) ) {
    //Create the AccessTokenAuthentication object.
    $authObj      = new AccessTokenAuthentication();
    //Get the Access token.
    $accessToken  = $authObj->getTokens();
  } else  { throw new Exception("Please set AZURE subscription key"); exit;}


  foreach ($arr_text as  $value) {
    $requestBody[] =   array (
          'Text' => $value,
      ) ;

  }

  $content = json_encode($requestBody);

  $textType = "plain";

  try
  {
    $translateUrl = "https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&from=".$lang_from2.
      "&to=".$lang_to2."&textType=".$textType;
        try {
            //Initialize the Curl Session.
            $ch = curl_init();
                        //Create an Http Query.//
            //$paramArr = http_build_query($params);
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $translateUrl);
            //curl_setopt($ch, CURLOPT_HEADER, 1);            
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Ocp-Apim-Subscription-Key: " . $seftranslate_configuration['azure_subscription_key'],"Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
              array("Authorization: "."Bearer" . " " . $accessToken,"Content-Type: application/json","Content-length: " . strlen($content)));

            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //Execute the  cURL session.
            $strResponse = curl_exec($ch);
            //Get the Error Code returned by Curl.
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            //Close the Curl Session.
            curl_close($ch);

            //Decode the returned JSON string.
            $arrResponse = json_decode($strResponse);


            if ( is_object($arrResponse) && property_exists ($arrResponse,"statusCode") ){
                throw new Exception($arrResponse->message);
            }else if( is_object($arrResponse) && property_exists ($arrResponse,"error") ){
                throw new Exception($arrResponse->error->message);
      //echo ":11111111:".$objResponse->error->message;
            }

        } catch (Exception $e) {
            throw new Exception( "Exception-".$e->getMessage() );
        }

  }
  catch(Exception $e)
  {
    if( isset( $seftranslate_configuration['debug'] ) && !empty( $seftranslate_configuration['debug'] ) ) { throw $e; exit;}
      else {
        $seftranslate_error = true;
        return $ret_value;
      }
  }

  $ret_value = array();
  for($kk=0; $kk < count($arrResponse); $kk++ ){

    $TranslatedText = "";
    $TranslatedText = $arrResponse[$kk]->translations[0]->text ;
    $TranslatedText =  html_entity_decode($TranslatedText, ENT_NOQUOTES, 'UTF-8' );
    $ret_value[] = $TranslatedText ;

    $is_translated_before = get_text_translated($arr_text[$kk],$lang_from,$lang_to);
    if($is_translated_before !== false || trim($TranslatedText) == "" ) continue;


    //save to database
    $efentity = new mosSefentity($database);
    $efentity->hash = md5( $arr_text[$kk] );
    $efentity->entity_text = $TranslatedText ;
    $efentity->lang_from = $lang_from ;
    $efentity->lang_to = $lang_to ;
    $efentity->hits = 1 ;
    $efentity->date = date("Y-m-d H:i:s");
    $efentity->checkin();
    $efentity->store();
  }
  return $ret_value;
}


function gtranslate_array_text_translate($arr_text,$lang_from='en',$lang_to='ru'){
  global  $gt, $l_func,$seftranslate_configuration,$seftranslate_error;
  //DBQuery
  $database = JFactory::getDBO();

  $ret_array = array();
  $ret_value = $arr_text;

  if(isset($GLOBALS['gt']) ){

    try
    {
      $lang_list=parse_ini_file(JPATH_SITE.'/components/com_seftranslate/languages.ini');
      $lang_from2 = array_search($lang_from,$lang_list);
      $lang_to2 = array_search($lang_to,$lang_list);

      $l_func=$lang_from2."_to_".$lang_to2;

      $ret_array = $gt->$l_func($arr_text);
    }
    catch(Exception $e)
    {
      if( isset( $seftranslate_configuration['debug'] ) && !empty( $seftranslate_configuration['debug'] ) ) { throw $e; exit;}
      else {
        $seftranslate_error = true;
        return $ret_value;
      }
    }
  
  } else {
    $gt = new Gtranslate;
    if (!empty($seftranslate_configuration['api_google_translate_key'])) {
//      $gt->setApiKey('ABQIAAAAJqx9rI7QMow-DvzxzeY-fBQJCzZJtfoY9aWkeS1c9RYTdZv3sRSR2-NKlnge6FqsD8ME__F16afYBQ');
      $gt->setApiKey($seftranslate_configuration['api_google_translate_key']);
    }
    if($seftranslate_configuration['userip']) $gt->setUserIp( $_SERVER['REMOTE_ADDR'] );
    $gt->setRequestType('curl');
    $gt->setHttpReferer($_SERVER['HTTP_HOST']);
    $gt->setApiVersion(2);
    $gt->setUrl('https://www.googleapis.com/language/translate/v2');

    $lang_list=parse_ini_file(JPATH_SITE.'/components/com_seftranslate/languages.ini');
    $lang_from2 = array_search($lang_from,$lang_list);
    $lang_to2 = array_search($lang_to,$lang_list);  
  
    $l_func=$lang_from2."_to_".$lang_to2;
    
    $GLOBALS['gt'] = $gt ;
    $GLOBALS['l_func'] = $l_func ;

    try
    {
              
      $ret_array = $gt->$l_func($arr_text);
    }
    catch(Exception $e)
    { 
      if( isset( $seftranslate_configuration['debug'] ) && !empty( $seftranslate_configuration['debug'] ) ) { throw $e; exit;}
      else {
        $seftranslate_error = true;
        return $ret_value;
      }
    }
  }




  
  $ret_value = array();
  for($kk=0; $kk < count($arr_text); $kk++ ){
    
    $translatedText  =  html_entity_decode($ret_array[$kk]->translatedText, ENT_NOQUOTES, 'UTF-8' );
    $ret_value[] = $translatedText;

    $is_translated_before = get_text_translated($arr_text[$kk],$lang_from,$lang_to);
    if($is_translated_before !== false || trim($ret_array[$kk]->translatedText) == "" ) continue;
    
    //save to database
    $efentity = new mosSefentity($database);
    $efentity->hash = md5( $arr_text[$kk] );
    $efentity->entity_text = $translatedText;
    $efentity->lang_from = $lang_from ;
    $efentity->lang_to = $lang_to ;
    $efentity->hits = 1 ;
    $efentity->date = date("Y-m-d H:i:s");
    $efentity->checkin();
    $efentity->store();
  }
  return $ret_value;

}



function text_translate($text,$lang_from='en',$lang_to='ru')
{
  global  $seftranslate_configuration;
  
//echo ":111111111111111:".$text; exit;

  //if empty or only digitals or only X (sings where to we replace strings) - no need traslate
  if(trim($text,"X") == "" || ctype_digit($text) || trim($text)=="") return $text;

  //check translated text in database
  mb_detect_order("UTF-8,ISO-8859-1,windows-1252,iso-8859-15");
  $text = html_entity_decode( mb_convert_encoding(
    preg_replace("/[[:cntrl:]]/i", "",trim($text,"\x00..\x1F \t\n\r\0\x0B") ),
     'HTML-ENTITIES', mb_detect_encoding($text)) , ENT_NOQUOTES, 'UTF-8' );    
   

  $ret_value = get_text_translated($text,$lang_from,$lang_to);
  if($ret_value !== false ) return $ret_value;
  
  
  $translator = $seftranslate_configuration['translator'] ;

  $ret_value = $text;
  switch($translator){
    case 'bing' : 

      $ret_value = bing_text_translate_v3($text,$lang_from,$lang_to);

      break;
    case 'gtranslate' : 
      $ret_value = gtranslate_text_translate($text,$lang_from,$lang_to);
      break;
    default : 
      echo "some error in translator choose, please connect to developers";
      break;
  }
  return $ret_value;
}

function bing_text_translate_v3($text,$lang_from='en',$lang_to='ru'){
  global  $seftranslate_configuration,$seftranslate_error;
  
  //DBQuery
  $database = JFactory::getDBO();
  
  $ret_value = $text;
  $lang_from2 = $lang_from;
  $lang_to2 = $lang_to;
  if( $lang_from == 'zh-CN' )  $lang_from2 = 'zh-CHS';
  if( $lang_to == 'zh-CN' )  $lang_to2 = 'zh-CHS';
  if( $lang_from == 'zh-TW' )  $lang_from2 = 'zh-CHT';
  if( $lang_to == 'zh-TW' )  $lang_to2 = 'zh-CHT';
  
  //$params = array();
  if (!empty($seftranslate_configuration['azure_subscription_key'])  ) {
    //Create the AccessTokenAuthentication object.
    $authObj      = new AccessTokenAuthentication();
    //Get the Access token.
    $accessToken  = $authObj->getTokens();    
    //$params['appId'] = "Bearer" . " " . $accessToken;
  } else { throw new Exception("Please set AZURE subscription key" ); exit;}
  
  //$params['text'] = $text;
  $requestBody = array (
      array (
          'Text' => $text,
      ),
  );
  $content = json_encode($requestBody);
 
  $textType = "plain";
  
  try
  {
    // $bing_client = new SoapClient("http://api.microsofttranslator.com/V2/SOAP.svc");
    // $result = $bing_client->Translate($params);
    // $ret_value =  html_entity_decode($result->TranslateResult, ENT_NOQUOTES, 'UTF-8' );



    $translateUrl = "https://api.cognitive.microsofttranslator.com/translate?api-version=3.0&from=".$lang_from2.
      "&to=".$lang_to2."&textType=".$textType;
        try {
            //Initialize the Curl Session.
            $ch = curl_init();
//echo ":1111111111111:" . $accessToken ; exit ;
                        //Create an Http Query.//
//            $paramArr = http_build_query($params);
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $translateUrl);
            //curl_setopt($ch, CURLOPT_HEADER, 1);            
            //curl_setopt($ch, CURLOPT_HTTPHEADER, array("Ocp-Apim-Subscription-Key: " . $seftranslate_configuration['azure_subscription_key'],"Content-Type: application/json"));
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
              array("Authorization: "."Bearer" . " " . $accessToken,"Content-Type: application/json","Content-length: " . strlen($content)));

            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
            //CURLOPT_RETURNTRANSFER- TRUE to return the transfer as a string of the return value of curl_exec().
            curl_setopt ($ch, CURLOPT_RETURNTRANSFER, TRUE);
            //CURLOPT_SSL_VERIFYPEER- Set FALSE to stop cURL from verifying the peer's certificate.
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            //Execute the  cURL session.
            $strResponse = curl_exec($ch);
            //Get the Error Code returned by Curl.
            $curlErrno = curl_errno($ch);
            if($curlErrno){
                $curlError = curl_error($ch);
                throw new Exception($curlError);
            }
            //Close the Curl Session.
            curl_close($ch);

            //Decode the returned JSON string.
            $objResponse1 = json_decode($strResponse);

            if ( is_object($objResponse1) && property_exists ($objResponse1,"statusCode") ){
                throw new Exception($objResponse1->message);
            } else if( is_object($objResponse1) && property_exists ($objResponse1,"error") ){
                throw new Exception($objResponse1->error->message);
            }


            $ret_value = $objResponse1[0]->translations[0]->text;



        } catch (Exception $e) {
            throw new Exception( "Exception-".$e->getMessage() );
        }


  }
  catch(Exception $e)
  {
    if( isset( $seftranslate_configuration['debug'] ) && !empty($seftranslate_configuration['debug']) ) { throw $e; exit;}
      else {
        $seftranslate_error = true;
        return $ret_value;
      }
  }
  if(trim($ret_value) == "" ) return $ret_value;;
  
  //save to database
  $efentity = new mosSefentity($database);
  $efentity->hash = md5($text);
  $efentity->entity_text = $ret_value;
  $efentity->lang_from = $lang_from ;
  $efentity->lang_to = $lang_to ;
  $efentity->hits = 1 ;
  $efentity->date = date("Y-m-d H:i:s");
  $efentity->checkin();
  $efentity->store();
  
  return $ret_value;
}


function gtranslate_text_translate($text,$lang_from='en',$lang_to='ru'){
  global  $gt, $l_func,$seftranslate_configuration,$seftranslate_error;
  //DBQuery
  $database = JFactory::getDBO();
  
  $ret_value = $text;
  if(isset($GLOBALS['gt']) ){


    try
    {
      $lang_list=parse_ini_file(JPATH_SITE.'/components/com_seftranslate/languages.ini');
      $lang_from2 = array_search($lang_from,$lang_list);
      $lang_to2 = array_search($lang_to,$lang_list);

      $l_func=$lang_from2."_to_".$lang_to2;

      $ret_value = $gt->$l_func($text);
      $ret_value  =  html_entity_decode($ret_value[0]->translatedText, ENT_NOQUOTES, 'UTF-8' );
    }
    catch(Exception $e)
    {
      if( isset($seftranslate_configuration['debug']) && !empty( $seftranslate_configuration['debug'] ) ) { throw $e; exit;}
      else {
        $seftranslate_error = true;
        return $ret_value;
      }
    }
  
  } else {
    $gt = new Gtranslate;
    if (!empty($seftranslate_configuration['api_google_translate_key'])) {
      //$gt->setApiKey('ABQIAAAARBki4JWk0Cwz-v6GlQ90wBRTxlZd-7gfuqQumodWhI9M82S_fRQ5OJFZp4v4oNXri4eZjoah2n4--w');
      $gt->setApiKey($seftranslate_configuration['api_google_translate_key']);
    }
    if($seftranslate_configuration['userip']) $gt->setUserIp( $_SERVER['REMOTE_ADDR'] );
    $gt->setRequestType('curl');
    $gt->setHttpReferer($_SERVER['HTTP_HOST']);
    $gt->setApiVersion(2);
    $gt->setUrl('https://www.googleapis.com/language/translate/v2');
    
    $lang_list=parse_ini_file(JPATH_SITE.'/components/com_seftranslate/languages.ini');
    $lang_from2 = array_search($lang_from,$lang_list);
    $lang_to2 = array_search($lang_to,$lang_list);  
  
    $l_func=$lang_from2."_to_".$lang_to2;
    
    $GLOBALS['gt'] = $gt ;
    $GLOBALS['l_func'] = $l_func ;

    try
    {
      $ret_value = $gt->$l_func($text);
      $ret_value  =  html_entity_decode($ret_value[0]->translatedText, ENT_NOQUOTES, 'UTF-8' );
    }
    catch(Exception $e)
    { 
      if( isset( $seftranslate_configuration['debug'] ) && !empty( $seftranslate_configuration['debug'] ) ) { throw $e; exit;}
      else {
        $seftranslate_error = true;
        return $ret_value;
      }
    }
  }
  if(trim($ret_value) == "" ) return $ret_value;;
  
  //save to database
  $efentity = new mosSefentity($database);
  $efentity->hash = md5($text);
  $efentity->entity_text = $ret_value;
  $efentity->lang_from = $lang_from ;
  $efentity->lang_to = $lang_to ;
  $efentity->hits = 1 ;
  $efentity->date = date("Y-m-d H:i:s");
  $efentity->checkin();
  $efentity->store();
  
  return $ret_value;
}

function get_text_translated(&$text,$lang_from='en',$lang_to='ru'){

  //DBQuery
  $database = JFactory::getDBO();

  //if empty or only digitals or only X (letters where to we replace strings) - no need traslate
  if(trim($text,"X") == "" || ctype_digit($text) || trim($text)=="") return $text;

  if( is_special($text) ) return $text;
  mb_detect_order("UTF-8,ISO-8859-1,windows-1252,iso-8859-15");
  $text = html_entity_decode( mb_convert_encoding(
    preg_replace("/[[:cntrl:]]/i", "",trim($text,"\x00..\x1F \t\n\r\0\x0B") ),
     'HTML-ENTITIES', mb_detect_encoding($text)) , ENT_NOQUOTES, 'UTF-8' );

  //check translated text in database
  $hash = md5($text);
  $efentity = new mosSefentity($database);
  $efentity = $efentity->loadForHash($hash,$lang_from,$lang_to);
  if( $efentity != null ) {
    $ret_value = $efentity->entity_text;
    $efentity->hits = $efentity->hits + 1 ;
    $efentity->date = date("Y-m-d H:i:s");
    $efentity->store();
    return $ret_value;
  }

  return false;
}

function is_special($text){

$HTML_ENTS=array("&quot;", "&amp;", "&apos;", "&lt;", "&gt;", "&nbsp;", "&iexcl;", "&cent;",
"&pound;","curren;", "&yen;", "&brvbar;", "&sect;", "&uml;", "&copy;", "&ordf;", "&laquo;",
"&not;", "&shy;", "&reg;", "&macr;", "&deg;", "&plusmn;", "&sup2;", "&sup3;", "&acute;",
"&micro;", "&para;", "&middot;", "&cedil;", "&sup1;", "&ordm;", "&raquo;", "&frac14;",
"&frac12;", "&frac34;", "&iquest;", "&Agrave;", "&Aacute;", "&Acirc;", "&Atilde;", "&Auml;",
"&Aring;", "&AElig;", "&Ccedil;", "&Egrave;", "&Eacute;", "&Ecirc;", "&Euml;", "&Igrave;",
"&Iacute;", "&Icirc;", "&Iuml;", "&ETH;", "&Ntilde;", "&Ograve;", "&Oacute;", "&Ocirc;",
"&Otilde;", "&Ouml;", "&times;", "&Oslash;", "&Ugrave;", "&Uacute;", "&Ucirc;", "&Uuml;",
"&Yacute;", "&THORN;", "&szlig;", "&agrave;", "&aacute;", "&acirc;", "&atilde;", "&auml;",
"&aring;", "&aelig;", "&ccedil;", "&egrave;", "&eacute;", "&ecirc;", "&euml;", "&igrave;",
"&iacute;", "&icirc;", "&iuml;", "&eth;", "&ntilde;", "&ograve;", "&oacute;", "&ocirc;",
"&otilde;", "&ouml;", "&divide;", "&oslash;", "&ugrave;", "&uacute;", "&ucirc;", "&uuml;",
"&yacute;", "&thorn;", "&yuml;", "&OElig;", "&oelig;", "&Scaron;", "&scaron;", "&Yuml;",
"&fnof;", "&circ;", "&tilde;", "&Alpha;", "&Beta;", "&Gamma;", "&Delta;", "&Epsilon;",
"&Zeta;", "&Eta;", "&Theta;", "&Iota;", "&Kappa;", "&Lambda;", "&Mu;", "&Nu;", "&Xi;",
"&Omicron;", "&Pi;", "&Rho;", "&Sigma;", "&Tau;", "&Upsilon;", "&Phi;", "&Chi;", "&Psi;",
"&Omega;", "&alpha;", "&beta;", "&gamma;", "&delta;", "&epsilon;", "&zeta;", "&eta;",
"&theta;", "&iota;", "&kappa;", "&lambda;", "&mu;", "&nu;", "&xi;", "&omicron;", "&pi;",
"&rho;", "&sigmaf;", "&sigma;", "&tau;", "&upsilon;", "&phi;", "&chi;", "&psi;", "&omega;",
"&thetasym;", "&upsih;", "&piv;", "&ensp;", "&emsp;", "&thinsp;", "&zwnj;", "&zwj;", "&lrm;",
"&rlm;", "&ndash;", "&mdash;", "&lsquo;", "&rsquo;", "&sbquo;", "&ldquo;", "&rdquo;",
"&bdquo;", "&dagger;", "&Dagger;", "&bull;", "&hellip;", "&permil;", "&prime;", "&Prime;",
"&lsaquo;", "&rsaquo;", "&oline;", "&frasl;", "&euro;", "&image;", "&weierp;", "&real;",
"&trade;", "&alefsym;", "&larr;", "&uarr;", "&rarr;", "&darr;", "&harr;", "&crarr;", "&lArr;",
"&uArr;", "&rArr;", "&dArr;", "&hArr;", "&forall;", "&part;", "&exist;", "&empty;", "&nabla;",
"&isin;", "&notin;", "&ni;", "&prod;", "&sum;", "&minus;", "&lowast;", "&radic;", "&prop;",
"&infin;", "&ang;", "&and;", "&or;", "&cap;", "&cup;", "&int;", "&there4;", "&sim;", "&cong;",
"&asymp;", "&ne;", "&equiv;", "&le;", "&ge;", "&sub;", "&sup;", "&nsub;", "&sube;", "&supe;",
"&oplus;", "&otimes;", "&perp;", "&sdot;", "&lceil;", "&rceil;", "&lfloor;",
"&rfloor;", "&lang;", "&rang;", "&loz;", "&spades;", "&clubs;", "&hearts;", "&diams;");

  $text = trim($text);
  if( in_array($text,$HTML_ENTS ) ) return true;

  static $HTML_ENTS2 = array();
  if(count($HTML_ENTS2) < 2) {
    foreach($HTML_ENTS as $ent){
      $HTML_ENTS2[] = html_entity_decode($ent);
    }  
  }  

  $text = html_entity_decode($text);
  
  for($kk = 0; $kk < strlen($text); $kk++ )
    if( !in_array($text[$kk],$HTML_ENTS2 ) ) return false;
  
  return true;
}
