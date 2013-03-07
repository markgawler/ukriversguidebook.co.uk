<?php
/**
* @package     UKRGB
* @subpackage  Map
* @copyright   Copyright (C) Mark Gawler, 2012. All rights reserved.
* @license     GNU GPL v2.0
*/
/**
 * Example Plug-in
 * http://tushev.org/articles/programming/18-how-to-create-an-editor-button-editors-xtd-plugin-for-joomla
 * http://www.bullraider.com/joomla/joomla-tutorials/extension-tutorials/plugin-tutorials/editors-xtd-tutorials/hello-world-editor-button-plugin?start=1
 * http://stackoverflow.com/questions/13743561/add-custom-button-to-joomlas-article-editor-tinymce
**/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

class plgButtonUkrgbMapEditorButton extends JPlugin {
	/**
	 * Constructor
	 *
	 * @param     object $subject The object to observe
	 * @param     array  $config  An array that holds the plugin configuration
	 * @since 1.5
	 */
	public function __construct(& $subject, $config)
	{
		parent::__construct($subject, $config);
		//$this->loadLanguage();
		
	}
	function onDisplay($name)
	{
		error_log("-- button plugin");
		//error_log($article->riverguide);
		//if (!isset($article->riverguide))
		//	return;
		
		//error_log("-- button plugin - is river guide");
		
		JHtml::_('behavior.framework');
		JHtml::_('script', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js');
		JHtml::_('script', 'components/com_ukrgb/views/map/js/map.js');
		JHtml::_('stylesheet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css');
		JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
		
		/*$mapData = json_encode(array(
				'url' => JURI::base() . 'index.php?option=com_ukrgb&tmpl=raw&format=json',
				'mapid' => $article->riverguide->mapid,
				'guideid' => $article->riverguide->guideid));
		
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration('var params = ' .$mapData.';');
		*/
		//TODO
		
		$js =  "
         function buttonTestClick(editor) {
                             txt = '';
                             //if(!txt) return;
                               jInsertEditorText('{map '+txt+'}', editor);
        }";
		$css = ".button2-left .testButton {
                    background: transparent url(/plugins/editors-xtd/test.png) no-repeat 100% 0px;
                }";
		$doc = & JFactory::getDocument();
		$doc->addScriptDeclaration($js);
		$doc->addStyleDeclaration($css);
		$button = new JObject();
		$button->set('modal', false);
		$button->set('onclick', 'buttonTestClick(\''.$name.'\');return false;');
		$button->set('text', JText::_('Test'));
		$button->set('name', 'testButton');
		$button->set('link', '#');
		return $button;
	}
}
?>