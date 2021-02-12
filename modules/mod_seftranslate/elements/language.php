<?php
/**
 * FileName: language.php
 * Date: 24/02/2010
 * License: GNU General Public License
 * Script Version #: 2.1.5
 * JOS Version #: 1.5.x
 * Development Aleksey Pakholkov, Andrey Kvasnevskiy - OrdaSoft(http://ordasoft.com)
 */
defined('_JEXEC') or die('Restricted access');

class JElementLanguage extends JElement
{
	var	$_name = 'Language';

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
				$t=new t_lang();
				$t->key=$lang_list[$key];
				$t->title=ucfirst(strtolower($key));
				$langs[]=$t;
			}
		}
		return JHTML::_('select.genericlist',  $langs, ''.$control_name.'['.$name.'][]', 'class="inputbox" multiple size="8"', 'key', 'title', $value, $control_name.$name );
	}
}
class t_lang
{
	var $key;
	var $title;
}
?>
