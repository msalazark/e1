<?php
  defined('_JEXEC') or die('Restricted access');

/**
*
* @package  seftranslate
* @copyright 2011 Andrey Kvasnevskiy-OrdaSoft(akbet@mail.ru); Rob de Cleen(rob@decleen.com);
* Homepage: http://www.ordasoft.com
* @version: 5.0 free
* @license GNU General Public license version 2 or later; see LICENSE.txt
*
**/

  if(!is_file(JPATH_SITE."/components/com_seftranslate/languages.conf.php"))
  {
    echo "<div>Please install SEF translate component first!</div>";
    return;
  }

  
  $my=JFactory::getUser();
  if( ($my->id!=0)and($hide_module_sef=="y"))  return;

  $lang_list=parse_ini_file(JPATH_SITE."/components/com_seftranslate/languages.ini");
  $keys = array_keys( $lang_list );

  $document = JFactory::getDocument();
  $seftranslate_configuration=Array();

  
  require(JPATH_SITE."/components/com_seftranslate/languages.conf.php");
?>
<script>  
    function get_url(name) {
      name=name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
      var regexS="[\\?&]"+name+"=([^&#]*)";
      var regex=new RegExp(regexS);

      var results=regex.exec(location.href);

      if(results==null) return '';
      return results[1];
    }  
</script>
<noscript>Javascript is required to use Sef Translate, <a title="Sef Translate - joomla software for automatically website translation" 
href="https//ordasoft.com/Joomla-Extensions/sef-translate-joomla-software-for-automatically-website-translation.html"
>Sef Translate - joomla software for automatically website translation
</a>, <a title="Sef Translate - joomla module help translation website" href="https//ordasoft.com/Joomla-Extensions/sef-translate-joomla-software-for-automatically-website-translation.html"
>Sef Translate - joomla module help translation website</a></noscript>

<?php    

// load jQuer
JHtml::_('script',JURI::base()."modules/mod_seftranslate/js/jquer.js");

?>
<script type="text/javascript"> 
   var $jqST = jQuer.noConflict(); 
</script>

<script  type="text/javascript" src="<?php
 echo JURI::base(); ?>modules/mod_seftranslate/js/cookiesef.js"> </script>

<script type="text/javascript"> 

<?php
  //crean cookie
  if($remeber_language == 0 ) {
    setcookie("seflang", "", time()-3600); 
    setcookie("googtrans", "", time()-3600); 
    unset($_COOKIE["seflang"]);
    unset($_COOKIE["googtrans"]);
?>
    $jqST.removeCookie('seflang', { path: '/' });
    $jqST.removeCookie('googtrans', { path: '/' });
    $jqST.removeCookie('googtrans', { path: '/' });

<?php
  }
?>   

</script>

<?php    
  if($trans_metod=='qq'){
?>
    
<div id="google_translate_element"></div>
<script type="text/javascript">
/* <![CDATA[ */

function sefTranslateFireEvent(element) {

    if ("createEvent" in document) {
        var evt = document.createEvent("HTMLEvents");
        evt.initEvent("change", false, true);
        element.dispatchEvent(evt);
    }
    else{
        element.fireEvent("onchange");              
    }
}
  
function googleTranslateElementInit() {
  new google.translate.TranslateElement({
    pageLanguage: '<?php echo $seftranslate_configuration['site_language']; ?>',
  }, 'google_translate_element');
}
/* ]]> */
</script>
<script type="text/javascript"
 src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

<script type="text/javascript" >
  
    $jqST(window).on('load',  function() {   


      //add labels for WGAG2.0 Section 508
      var translateForm = null;
      var tmpForms=document.getElementsByTagName('select');
      for(var i=0;i<tmpForms.length;i++){
        if(tmpForms[i].className=='goog-te-combo'){
          translateForm=tmpForms[i];
          translateForm.setAttribute('id',"GoogleTranslateForm");
          var newlabel = document.createElement("label");
          newlabel.setAttribute("for","GoogleTranslateForm");
          newlabel.innerHTML = "Google Site Translate Form";
          translateForm.parentElement.appendChild(newlabel);    

          // //need for return to default language
          // var newEl = document.createElement("option");
          // newEl.value = "<?php echo $seftranslate_configuration['site_language']; ?>";
          // newEl.innerHTML = "<?php echo $seftranslate_configuration['site_language']; ?>";
          // translateForm.appendChild(newEl); 

          //document.getElementsByTagName('body')[0].appendChild(newlabel) ;
          break;
        }
      }

//return ;
<?php
  //crean cookie
  if($remeber_language == 0 ) {
    setcookie("seflang", "", time()-3600); 
    setcookie("googtrans", "", time()-3600); 
?>
    $jqST.removeCookie('seflang', { path: '/' });
    $jqST.removeCookie('googtrans', { path: '/' });
    $jqST.removeCookie('googtrans', { path: '/' });
    setTimeout(function(){go_transl('<?php echo $seftranslate_configuration['site_language']; ?>')},1500);

<?php
  }
?>   


        if( <?php echo $remeber_language ?> && $jqST.cookiesef('seflang') && 
          $jqST.cookiesef('seflang') != '<?php echo $seftranslate_configuration['site_language']; ?>') {

          lang = $jqST.cookiesef('seflang')  ;

          var translateForm = null;
          var tmpForms=document.getElementsByTagName('select');
          for(var i=0;i<tmpForms.length;i++){
            if(tmpForms[i].className=='goog-te-combo'){
              translateForm=tmpForms[i];
              translateForm.setAttribute('id',"GoogleTranslateForm");

              //add labels for WGAG2.0 Section 508
              var newlabel = document.createElement("label");
              newlabel.setAttribute("for","GoogleTranslateForm");
              newlabel.innerHTML = "Google Site Translate Form";
              translateForm.parentElement.appendChild(newlabel);    

              // //need for return to default language
              // var newEl = document.createElement("option");
              // newEl.value = "<?php echo $seftranslate_configuration['site_language']; ?>";
              // newEl.innerHTML = "<?php echo $seftranslate_configuration['site_language']; ?>";
              // translateForm.appendChild(newEl); 


              //document.getElementsByTagName('body')[0].appendChild(newlabel)

              break;
            }
          }
          if(translateForm != null){
              //clear old lang
              //translateForm.value="";
              //sefTranslateFireEvent(translateForm);
              //set new lang
              translateForm.value=lang;
              setTimeout(function(){sefTranslateFireEvent(translateForm)},1300);
              // if('<?php echo $seftranslate_configuration['site_language']; ?>' == lang ) {
              //   //need for reset to default lang
              //   setTimeout(function(){sefTranslateFireEvent(translateForm)},1500);
              // }              

          } else{
              setTimeout(function(){go_transl(lang)},1500);
          }  
          
        } else {
          //clean google translate cookies
          $jqST.removeCookie('googtrans', { path: '/' });      
          $jqST.removeCookie('googtrans', { path: '/' });      
        }

        $jqST.placeholdersTranslate();

    });  
    </script>          

<?php    
  }else if($trans_metod=='q'){
    
?>


<script  type="text/javascript" src="<?php
 echo JURI::base(); ?>modules/mod_seftranslate/js/jquer.translate.js"> </script>

<script type="text/javascript">

<?php
  //crean cookie
  if($remeber_language == 0 ) {
    setcookie("seflang", "", time()-3600); 
    setcookie("googtrans", "", time()-3600); 
?>
    $jqST.removeCookie('seflang', { path: '/' });
    $jqST.removeCookie('googtrans', { path: '/' });
    $jqST.removeCookie('googtrans', { path: '/' });
    setTimeout(function(){go_transl('<?php echo $seftranslate_configuration['site_language']; ?>')},1500);

<?php
  }
?>  

    if( <?php echo $remeber_language ?> &&  $jqST.cookiesef('seflang') && $jqST.cookiesef('seflang') != '<?php
     echo $seftranslate_configuration['site_language']; ?>') 
    {
<?php
      $site_sub_url=substr($_SERVER['SCRIPT_NAME'],0,stripos($_SERVER['SCRIPT_NAME'] , 'index.php' ));
      $site_quest=substr($_SERVER['REQUEST_URI'],strlen($site_sub_url)-1);
      if($site_sub_url=="/")
      {
        $site_sub_url=$site_url="//".$_SERVER['HTTP_HOST'].$site_sub_url;
      } else $site_url="//".$_SERVER['HTTP_HOST'].$site_sub_url;

      $translator =  $seftranslate_configuration['translator'] ;
      switch($translator){
        case 'bing' :
          if (!empty($seftranslate_configuration['azure_subscription_key']) ) {
            $appIds = "11111111111111111111111111111111111111111111111111111111111111:".$site_url ;
          ?>
            $jqST.translate.load('<?php echo trim($appIds); ?>' );
            //$jqST(function($){
            $jqST('body').translate('<?php
             echo $seftranslate_configuration['site_language']; ?>',
              $jqST.cookiesef('seflang'), {toggle:true});
            //});
          <?php
          }
          break;
        case 'gtranslate' :
          if (!empty($seftranslate_configuration['api_google_translate_key'])) {
          ?>
            $jqST.translate.load('<?php
             echo trim($seftranslate_configuration['api_google_translate_key']); ?>' );
            //$jqST(function($){
            $jqST('body').translate('<?php
             echo $seftranslate_configuration['site_language']; ?>',
              $jqST.cookiesef('seflang'), {toggle:true});
            //});
          <?php
          }

          break;

        default :
          echo "alert('Please set translate api key')";
          break;
      }
?>
    }
</script>



<script >
    if(top.location!=self.location) top.location=self.location;

    window['_tipoff']=function(){};
    window['_tipon']=function(a){};


      if( <?php echo $remeber_language ?> &&  $jqST.cookiesef('seflang') &&
        $jqST.cookiesef('seflang') != '<?php echo $seftranslate_configuration['site_language']; ?>')
      {

    <?php  
        $site_sub_url=substr($_SERVER['SCRIPT_NAME'],0,stripos($_SERVER['SCRIPT_NAME'] , 'index.php' ));
        $site_quest=substr($_SERVER['REQUEST_URI'],strlen($site_sub_url)-1);
        
        if($site_sub_url=="/")
        {
          $site_sub_url=$site_url="//".$_SERVER['HTTP_HOST'].$site_sub_url;
        } else $site_url="//".$_SERVER['HTTP_HOST'].$site_sub_url;
          
        $translator =  $seftranslate_configuration['translator'] ;
        switch($translator){
          case 'bing' : 
            if (!empty($seftranslate_configuration['azure_subscription_key']) ) {
              $appIds = "11111111111111111111111111111111111111111111111111111111111111:".$site_url ;
            ?>
              $jqST.translate.load('<?php echo trim($appIds); ?>' );
              //$jqST(function($){
              $jqST('body').translate('<?php
               echo $seftranslate_configuration['site_language']; ?>',
                $jqST.cookiesef('seflang'), {toggle:true});
              //});
            <?php 
            }
            break;
          case 'gtranslate' : 
            if (!empty($seftranslate_configuration['api_google_translate_key'])) {
            ?>
              $jqST.translate.load('<?php
               echo trim($seftranslate_configuration['api_google_translate_key']); ?>' );
              //$jqST(function($){
              $jqST('body').translate('<?php
               echo $seftranslate_configuration['site_language']; ?>',
                $jqST.cookiesef('seflang'), {toggle:true});
              //});
            <?php 
            }
            break;

          default :
            echo "alert('Please set translate api key')";
            break;
        }      
        ?>
      }

      $jqST.placeholdersTranslate();
</script>      
<?php
    }
  ?>
  
<script>

    function go_transl(lang)
    {

<?php
      if($trans_metod=='qq'){
?>

        if('<?php echo $seftranslate_configuration['site_language']; ?>' == lang ){
          $jqST.removeCookie('googtrans', { path: '/' });
          $jqST.removeCookie('googtrans', { path: '/' });
          $jqST.cookiesef('googtrans', ''); //clean site cookies google
        //$jqST.cookiesef('googtrans', '/<?php echo $seftranslate_configuration['site_language']; ?>/'+lang);
        }
          
        var translateForm = null;
        var tmpForms=document.getElementsByTagName('select');
        for(var i=0;i<tmpForms.length;i++){
          if(tmpForms[i].className=='goog-te-combo'){
            translateForm=tmpForms[i];
              translateForm.setAttribute('id',"GoogleTranslateForm");

              //add labels for WGAG2.0 Section 508
              var newlabel = document.createElement("label");
              newlabel.setAttribute("for","GoogleTranslateForm");
              newlabel.innerHTML = "Google Site Translate Form";
              translateForm.parentElement.appendChild(newlabel); 
              
              //need for return to default language or translate to old language, some time this language not exist in options
               var newEl = document.createElement("option");
               newEl.value = lang;
               newEl.innerHTML = lang;
               translateForm.appendChild(newEl); 
              
              //document.getElementsByTagName('body')[0].appendChild(newlabel)

            break;
          }
        }
        if(translateForm != null){
            //clear old lang
            //translateForm.value="";
            //sefTranslateFireEvent(translateForm);
            //set new lang
            translateForm.value=lang;
            setTimeout(function(){sefTranslateFireEvent(translateForm)},1000);
             //need for reset old lang, which we have before
             setTimeout(function(){sefTranslateFireEvent(translateForm)},1400);
            return ;
        } else{
            new google.translate.TranslateElement({
              pageLanguage: '<?php echo $seftranslate_configuration['site_language']; ?>',  autoDisplay: false
              }, 'google_translate_element');
            setTimeout(function(){go_transl(lang)},1400);
            return;
        }
<?php
        
      }else if($trans_metod=='q'){
?>
        
        if(lang=='zh-CHS')lang='zh-CN';
        if(lang=='zh-CHT')lang='zh-TW'; 

<?php
  if($remeber_language == 1 ) {
?>
        
          $jqST.cookiesef('googtrans', '/<?php
                echo $seftranslate_configuration['site_language']; ?>/'+lang);
          $jqST.cookiesef('seflang', lang);

<?php
  }
?>   
       
        if(location.hostname!='<?php echo $_SERVER['HTTP_HOST']; ?>')
        {
          location.href=unescape(get_url('u'));
          return;
        }
  <?php 
        $site_sub_url=substr($_SERVER['SCRIPT_NAME'],0,stripos($_SERVER['SCRIPT_NAME'] , 'index.php' ));
        $site_quest=substr($_SERVER['REQUEST_URI'],strlen($site_sub_url)-1);
          if($site_sub_url=="/")
          {
            $site_sub_url=$site_url="//".$_SERVER['HTTP_HOST'].$site_sub_url;
          } else $site_url="//".$_SERVER['HTTP_HOST'].$site_sub_url;
          
        $translator =  $seftranslate_configuration['translator'] ;
        switch($translator){

          case 'bing' : 
              if (!empty($seftranslate_configuration['azure_subscription_key']) ) {
                $appIds = "11111111111111111111111111111111111111111111111111111111111111:".$site_url ;
            ?>
              //******************
              if(lang=='zh-CN')lang='zh-CHS';
              if(lang=='zh-TW')lang='zh-CHT'; 

<?php
  if($remeber_language == 1 ) {
?>
              $jqST.cookiesef('seflang', lang);      
<?php
  }
?>   

              //******************

              $jqST.translate.load('<?php echo trim($appIds); ?>' );

              //$jqST(function($){
                $jqST('body').translate('<?php
                 echo $seftranslate_configuration['site_language']; ?>', lang, {toggle:true});
              //});
           <?php
            } else { 
            ?>
              alert( "Please set AZURE subscription key"); 
            <?php 
            };
            break;
          case 'gtranslate' : 
            if (!empty($seftranslate_configuration['api_google_translate_key'])) {
            ?>
              $jqST.translate.load('<?php
                 echo trim($seftranslate_configuration['api_google_translate_key']); ?>' );
              //$jqST(function($){
                $jqST('body').translate('<?php
                 echo $seftranslate_configuration['site_language']; ?>', lang, {toggle:true});
              //});
            <?php 
            } else { 
            ?>
              alert( "Please set Google API key"); 
            <?php 
            };
            break;

          default :
            echo "alert('Please set translate api key')";
            break;
        }      
        ?>
<?php
      }else{
?>
        
        if(lang=='<?php echo $seftranslate_configuration['site_language']; ?>' 
          && location.hostname=='<?php echo $_SERVER['HTTP_HOST']; ?>')
            return;
        if(lang=='<?php echo $seftranslate_configuration['site_language']; ?>' 
          && location.hostname!='<?php echo $_SERVER['HTTP_HOST']; ?>')
        {
          
          location.href=unescape(get_url('u'));
          return;
        }
        if(lang!='<?php echo $seftranslate_configuration['site_language']; ?>' 
          && location.hostname=='<?php echo $_SERVER['HTTP_HOST']; ?>' )
        {
          location.href='//translate.google.com/translate?client=tmpg&hl=<?php 
            echo $seftranslate_configuration['site_language']; ?>&langpair=<?php 
            echo $seftranslate_configuration['site_language']; ?>|'+lang+'&u='+escape(location.href);
          return;
        }
        location.href='//translate.google.com/translate?client=tmpg&hl=<?php 
          echo $seftranslate_configuration['site_language']; ?>&langpair=<?php 
          echo $seftranslate_configuration['site_language']; ?>|'+lang+'&u='+escape(unescape(get_url('u')));
<?php
      }
?>
    }
  
</script>
  <form id="<?php echo (empty($show_lang))?$mod_position:'mod_position_default';?>"
     action="index.php" method="post" name="translateForm" id="translateForm" >

  <?php 
  
  $show_flag_lang=($show_flag_lang=="")?Array():(
    is_array($show_flag_lang)?$show_flag_lang:Array($show_flag_lang));

  //show sef translate languages flags
  foreach( $show_flag_lang as $flag )
  {
    $alt=ucfirst(strtolower(array_search($flag,$lang_list)));
    $alt = str_replace("_", " ", $alt) ;
    echo '<a href="'.'javascript:go_transl(\''.$flag.'\');'.'" ><img width="'.
      $flag_size.'" src="'.JURI::base().'/modules/mod_seftranslate/tmpl/flags/'.$flag_type.'/64/'.
      strtoupper($flag).'.png" alt="'.$alt.'" title="'.$alt.'"></a> ';
  }
  if((count($show_flag_lang)>1)and($show_lang!=""))
  {
    echo "<br>";
  
  }
?>
  
  <?php
   if(empty($show_lang))
  {

  }
  else if ($show_list_or_text_or_text_with_flag == "dropdown_list")
  {
    ?>
    <select onchange="go_transl(this.value);">
      <option value="">Select language</option>
    <?php
    if(!is_array($show_lang)){
      $show_lang=Array($show_lang);
    }
    //show sef translate languages options
    foreach( $keys as $key )
    {
      if(array_search($lang_list[$key],$show_lang)!==false)
      {
        echo '<option value="'.$lang_list[$key].'">'.ucfirst(strtolower($key)).'</option>';
      }

    }
    ?>
    </select>
<?php
  }
  else if ($show_list_or_text_or_text_with_flag == "plain_text_list")
  {

    echo "<ul class='". $show_langs_direction."' >" ;

    if(!is_array($show_lang)){
      $show_lang=Array($show_lang);
    }
    //show sef translate languages options
    foreach( $keys as $key )
    {
      if(array_search($lang_list[$key],$show_lang)!==false)
      {
        
        if( $show_langs_direction == "vertical" ) $display_how = "list-style-type:none;display:block" ;
        else $display_how = "list-style-type:none;display:inline-block;padding-left:7px;" ;

        echo '<li style="'.$display_how.'"><a href="javascript:go_transl(\''.$lang_list[$key].'\'); ">'.ucfirst(strtolower($key)).'</a></li>'; ;

      }
    }
    
    echo "</ul>" ;

    ?>
<?php
  }
  else if ($show_list_or_text_or_text_with_flag == "text_list_with_flag")
  {
    
    echo "<ul class='". $show_langs_direction."' >" ;

    if(!is_array($show_lang)){
      $show_lang=Array($show_lang);
    }
    //show sef translate languages options
    foreach( $keys as $key )
    {
      if(array_search($lang_list[$key],$show_lang)!==false)
      {
  
        $alt=ucfirst(strtolower($key));
        $alt = str_replace("_", " ", $alt) ;
        $flag_t = '<a class="sefTranslate_flag" href="'.'javascript:go_transl(\''.$lang_list[$key].'\');'.'" ><img width="'.
          $flag_size.'" src="'.JURI::base().'/modules/mod_seftranslate/tmpl/flags/'.$flag_type.'/64/'.
          strtoupper($lang_list[$key]).'.png" alt="'.$alt.'" title="'.$alt.'"></a> ';

        if( $show_langs_direction == "vertical" ) $display_how = "list-style-type:none;display:block;" ;
        else $display_how = "list-style-type:none;display:inline-block;padding-left:7px;" ;

  
        echo '<li style="'.$display_how.'">'.$flag_t.'<a href="javascript:go_transl(\''.$lang_list[$key].'\'); ">'.ucfirst(strtolower($key)).'</a></li>';
      }
    }

    echo "</ul>" ;

    ?>
<?php
  }
?>
  </form>
<?php

if($trans_metod=='qq'){
?>

<style type="text/css">

#google_translate_element{
  display:none !important;
}
.goog-te-banner-frame {
  display:none !important;
}
body{
  top:0 !important;
}
#goog-gt-tt{
  display:none !important;
  visibility:hidden !important;
}
.goog-text-highlight
{
  background:transparent !important;
  box-shadow: none !important;
}

#mod_position_default ul li{
  position: unset;
  padding: 0 !important;
}
#mod_position_default .vertical li a{
  padding: 5px 10px 5px 0px !important; 
}
#mod_position_default ul li a{
  padding: 5px 10px 5px 0px !important;
  display: inline-block;
}
#mod_position_default ul li .sefTranslate_flag{
  padding: 5px 0 5px 5px !important;
}
#mod_position_default ul li:hover a{
  color: #13769a;
  text-decoration: none;
}
</style>
<?php
}
?>

      <style type="text/css">
      div[id^="placeholders"] div {
        height: 0;
        font-size:0; 
      }

      form[id^="mod_position"] {
        margin: 0;
      }
      #mod_position_top {
        position: fixed;
        top: 0;
        z-index: 999;
        display: inline-table;
      }
      #mod_position_top a, #mod_position_bottom a{
        display: inline-block;
        height: <?php echo ($flag_size*0.73) ?>px;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
      }
      #mod_position_top a {
        vertical-align: top;
      }
      #mod_position_top a:hover {
        padding-top: <?php echo ($flag_size*0.36) ?>px;
      }
      #mod_position_bottom a {
        vertical-align: bottom;
      }
      #mod_position_bottom a:hover {
        padding-bottom: <?php echo ($flag_size*0.36) ?>px;
      }
      #mod_position_top a img, #mod_position_bottom a img{
        vertical-align: top !important;
      }
      #mod_position_right {
        position: fixed;
        right: 0;
        z-index: 999;
      }
      #mod_position_bottom{
        position: fixed;
        bottom: 0;
        z-index: 999;
        display: inline-table;
      }
      #mod_position_left {
        position: fixed;
        left: 0;
        z-index: 999;
      }
      #mod_position_right a, #mod_position_left a {
        height: <?php echo ($flag_size*0.73) ?>px;
        display: block;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        -o-transition: all 0.3s;
        transition: all 0.3s;
      }
      #mod_position_right a:hover {
        padding-right: <?php echo ($flag_size*0.36) ?>px;
      }
      #mod_position_left a:hover {
        padding-left: <?php echo ($flag_size*0.36) ?>px;
      }
      #mod_position_right a img, #mod_position_left a img{
        height: 100%;
      }
      #mod_position_right a {
        float: right;
        clear: both;
      }
      </style>

      <script text="text/javascript">


          document.addEventListener("DOMContentLoaded", function(event) {
            var sef_icon_size = <?php echo $flag_size?>;
            <?php
            if($mod_position =='mod_position_bottom' || $mod_position =='mod_position_top'){ ?>
              var sef_form_width = ((document.body.clientWidth)-(<?php
               echo count($show_flag_lang) ?>*sef_icon_size))/2+'px';
                if(parseInt(sef_form_width) < 0){
                  sef_form_width = 0;
                }
                document.getElementById("<?php echo $mod_position?>").style.left = sef_form_width;
            <?php
            }

            if($mod_position =='mod_position_left' || $mod_position =='mod_position_right'){ ?>
              var sef_form_height = ((window.innerHeight)-(<?php
               echo count($show_flag_lang) ?>*(sef_icon_size*0.73)))/2+'px';
              if(parseInt(sef_form_height) < 0){
                sef_form_height = 0;
              }
              document.getElementById("<?php echo $mod_position?>").style.top = sef_form_height;
            <?php
            }?>
        });

   window.onresize = function(event) {
            var sef_icon_size = <?php echo $flag_size?>;
            <?php
            if($mod_position =='mod_position_bottom' || $mod_position =='mod_position_top'){ ?>
              var sef_form_width = ((document.body.clientWidth)-(<?php
               echo count($show_flag_lang) ?>*sef_icon_size))/2+'px';
                if(parseInt(sef_form_width) < 0){
                  sef_form_width = 0;
                }
                document.getElementById("<?php echo $mod_position?>").style.left = sef_form_width;
            <?php
            }

            if($mod_position =='mod_position_left' || $mod_position =='mod_position_right'){ ?>
              var sef_form_height = ((window.innerHeight)-(<?php
               echo count($show_flag_lang) ?>*(sef_icon_size* 0.65)))/2+'px';
              if(parseInt(sef_form_height) < 0){
                sef_form_height = 0;
              }
              document.getElementById("<?php echo $mod_position?>").style.top = sef_form_height;
            <?php
            }?>
    };
</script>
