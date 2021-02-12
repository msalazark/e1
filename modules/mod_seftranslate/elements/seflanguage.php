<?php
/**
 * FileName: language.php
 * Date: 03/08/2011
 * License: GNU General Public License
 * Script Version #: 2.1.5
 * JOS Version #: 1.5.x
 * Development Aleksey Pakholkov, Andrey Kvasnevskiy - OrdaSoft(http://ordasoft.com)
 */
defined('_JEXEC') or die('Restricted access');
if (version_compare(JVERSION, "1.6.0", "lt")){
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
 }else{
class JFormFieldSeflanguage extends JFormField
{

 protected $type     = 'Seflanguage';
  
  protected function getInput() {
	{
    // Initialize variables.
    $html = array();
    $attr = '';

    // Initialize some field attributes.
    $attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';

    // To avoid user's confusion, readonly="true" should imply disabled="true".
    if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
      $attr .= ' disabled="disabled"';
    }

    $attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
    $attr .= $this->multiple ? ' multiple="multiple"' : '';

    // Initialize JavaScript field attributes.
    $attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
    
    $lang_list=parse_ini_file(JPATH_SITE."/components/com_seftranslate/languages.ini");
    $keys = array_keys( $lang_list );
    // iterate through styles
    $langs=Array();
    foreach( $keys as $key )
    {
      if($key!="UNKNOWN")
      {
        $t=new t_lang();
        $t->value=$lang_list[$key];
        $t->text=ucfirst(strtolower($key));
        $langs[]=$t;
      }
    }
    
    // Get the field options.
    $options = $langs;

    // Create a read-only list (no name) with a hidden input to store the value.
    if ((string) $this->element['readonly'] == 'true') {
      $html[] = JHtml::_('select.genericlist', $options, '', trim($attr), 'value', 'text', $this->value, $this->id);
      $html[] = '<input type="hidden" name="'.$this->name.'" value="'.$this->value.'"/>';
    }
    // Create a regular list.
    else {
      $html[] = JHtml::_('select.genericlist', $options, $this->name, trim($attr), 'value', 'text', $this->value, $this->id);
    }

    return implode($html);
  }
 } 
 
}

class t_lang
{
	var $value;
	var $text;
 }
}
?>
