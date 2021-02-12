<?php

/**
* @package SefTranslate
* Date: 2011/04/01
* Development Aleksey Pakholkov, Andrey Kvasnevskiy - OrdaSoft(http://ordasoft.com)
* @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru);
* Homepage: http://www.ordasoft.com
* @version: 5.0 free
* @license GNU General Public license version 2 or later; see LICENSE.txt
*/
//https://docs.microsoft.com/en-us/azure/cognitive-services/translator/reference/v3-0-reference#authentication

define('_JEXEC') ; 
if (!defined('_VALID_MOS') && !defined('_JEXEC')) die('Direct Access to ' . basename(__FILE__) . ' is not allowed.');

require_once(dirname(__FILE__).'/languages.conf.php');  
$GLOBALS['seftranslate_configuration'] = $seftranslate_configuration;

if (!session_id()) session_start();

if(!isset($_SESSION['expire_last']))
{
  $_SESSION['expire_last']=0;
}

class AccessTokenAuthentication {
    

    /*
     * Get the access token.
     *
     * @param string $grantType    Grant type.
     * @param string $scopeUrl     Application Scope URL.
     * @param string $clientID     Application client ID.
     * @param string $clientSecret Application client ID.
     * @param string $authUrl      Oauth Url.
     *
     * @return string.
     */
     function getTokensCurlRequest_old($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl){
        try {
            //Initialize the Curl Session.
            $ch = curl_init();
            //Create the request Array.
            $paramArr = array (
                 'grant_type'    => $grantType,
                 'scope'         => $scopeUrl,
                 'client_id'     => $clientID,
                 'client_secret' => $clientSecret
            );
            //Create an Http Query.//
            $paramArr = http_build_query($paramArr);
            //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            //Set data to POST in HTTP "POST" Operation.
            curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
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
            $objResponse = json_decode($strResponse);
            if ( property_exists ($objResponse, "error") ){
                throw new Exception($objResponse->error_description);
            }
            return $objResponse->access_token;
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }


    /*
     * Get the access token.
     *
     * @param string $authUrl      auth Url.
     *
     * @return string.
     */
    function getTokensCurlRequest($authUrl,$host,$SubscriptionKey){
        try {
            //Initialize the Curl Session.
            $ch = curl_init();
            // //Create the request Array.
            // $paramArr = array ();
            // //Create an Http Query.//
            // $paramArr = http_build_query($paramArr);
            // //Set the Curl URL.
            curl_setopt($ch, CURLOPT_URL, $authUrl);
            //Set HTTP POST Request.
            curl_setopt($ch, CURLOPT_POST, TRUE);
            // //Set data to POST in HTTP "POST" Operation.
            // curl_setopt($ch, CURLOPT_POSTFIELDS, $paramArr);
            curl_setopt($ch, CURLOPT_HTTPHEADER, 
              array("Ocp-Apim-Subscription-Key: " . $SubscriptionKey,
                "Host: ".$host,
                "Content-type: application/x-www-form-urlencoded",
                "Content-length: 0" ) );

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
            $objResponse = json_decode($strResponse);
            if ( is_object($objResponse) && property_exists ($objResponse,"statusCode") ){
                throw new Exception($objResponse->message);
            }
            return $strResponse;
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }


    function getTokens(){
        global $seftranslate_configuration;
        try {
 
          $expire = time();
         
          //if less 9 minutes, return old token
          if(isset($_SESSION['expire_last']) && $_SESSION['expire_last'] != 0 &&
              $expire - $_SESSION['expire_last'] < 9*60 
              && isset($_SESSION['access_token']) ){
            return ($_SESSION['access_token']) ;
          }

          // //Client ID of the application.
          // $clientID       = $seftranslate_configuration['api_bing_client_id'];
          
          // //Client Secret key of the application.
          // $clientSecret       = $seftranslate_configuration['api_bing_client_secret'];
          // //OAuth Url.
          // $authUrl      = "https://datamarket.accesscontrol.windows.net/v2/OAuth2-13/";
          // //Application Scope Url
          // $scopeUrl     = "http://api.microsofttranslator.com";
          // //Application grant type
          // $grantType    = "client_credentials";
      
          $expire = time();

          $_SESSION['expire_last'] = $expire;
          
          //Get the Access token.
          //$accessToken  = $this->getTokensCurlRequest_old($grantType, $scopeUrl, $clientID, $clientSecret, $authUrl);


//          $authUrl      = "https://api.cognitive.microsoft.com/sts/v1.0/issueToken?Subscription-Key=98ccb73cbd134075a1b7c65ffba3a7b9";
          $SubscriptionKey = $seftranslate_configuration['azure_subscription_key'];

          $azure_region = "" ;
          if( isset( $seftranslate_configuration['azure_region'] ) ) $azure_region = $seftranslate_configuration['azure_region'];
            
          if(trim($azure_region) == "" ) {
            $authUrl = "https://api.cognitive.microsoft.com/sts/v1.0/issueToken";
            $host = "api.cognitive.microsoft.com";
          }
          else { 
            $authUrl = "https://".$azure_region.".api.cognitive.microsoft.com/sts/v1.0/issueToken";
            $host = $azure_region.".api.cognitive.microsoft.com";
          }          
//          $authUrl      = "https://api.cognitive.microsoft.com/sts/v1.0/issueToken?Subscription-Key=".$SubscriptionKey;
//          $authUrl      = "https://api.cognitive.microsoft.com/sts/v1.0/issueToken";
          $accessToken  = $this->getTokensCurlRequest($authUrl,$host,$SubscriptionKey);

          return ($accessToken);
        
        } catch (Exception $e) {
            echo "Exception-".$e->getMessage();
        }
    }

}