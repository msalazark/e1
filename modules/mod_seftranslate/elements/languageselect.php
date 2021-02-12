<?php
/**
 * FileName: languageselect.php
 * Date: 03/08/2011
 * License: GNU General Public License
 * Script Version #: 2.1.5
 * JOS Version #: 1.5.x
 * Development Aleksey Pakholkov, Andrey Kvasnevskiy - OrdaSoft(http://ordasoft.com)
 */
defined('_JEXEC') or die('Restricted access');
if (version_compare(JVERSION, "1.6.0", "lt")){
class JElementLanguageselect extends JElement
{
	var	$_name = 'Languageselect';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$lang_list=parse_ini_file(JPATH_SITE."/components/com_seftranslate/languages.ini");
		$keys = array_keys( $lang_list );
		// iterate through styles
		$langs=Array();
		foreach( $keys as $key )
		{
			if($key!="UNKNOWN")
			{
				$t=new t_lang2();
				$t->key=$lang_list[$key];
				$t->title=ucfirst(strtolower($key));
				$langs[]=$t;
			}
		}
		return JHTML::_('select.genericlist',  $langs, ''.$control_name.'['.$name.'][]', '', 'key', 'title', $value, $control_name.$name );
	}
}
class t_lang2
{
	var $key;
	var $title;
}
}else{
class JElementLanguageselect extends JFormField
{
	var	$_name = 'Languageselect';

	function fetchElement($name, $value, &$node, $control_name)
	{
		$lang_list=parse_ini_file(JPATH_SITE."/components/com_seftranslate/languages.ini");
		$keys = array_keys( $lang_list );
		// iterate through styles
		$langs=Array();
		foreach( $keys as $key )
		{
			if($key!="UNKNOWN")
			{
				$t=new t_lang2();
				$t->key=$lang_list[$key];
				$t->title=ucfirst(strtolower($key));
				$langs[]=$t;
			}
		}
		return JHTML::_('select.genericlist',  $langs, ''.$control_name.'['.$name.'][]', '', 'key', 'title', $value, $control_name.$name );
	}
}
class t_lang2
{
	var $key;
	var $title;
 } 
}
?>
