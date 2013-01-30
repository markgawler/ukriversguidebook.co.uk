<?php
/**
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2012 Mark Gawler. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_SITE . DS . 'components' . DS . 'com_ukrgb' . DS . 'models' . DS . 'maphelper.php';

class plgContentUkrgbMap extends JPlugin {
	/**
	 * Plugin that loads module positions within content
	 *
	 * @param	string	The context of the content being passed to the plugin.
	 * @param	object	The article object.  Note $article->text is also available
	 * @param	object	The article params
	 * @param	int		The 'page' number
	 */
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{		
		
		if (!isset($article->riverguide))
			return;
		
		JHtml::_('behavior.framework');
		JHtml::_('script', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js');
		JHtml::_('stylesheet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css');
		JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
		
		
		$mapDiv = '<div id="map"></div>';
		$user = JFactory::getUser();
		$canEdit = $user->authorise( "core.edit", "com_content.article." . $article->id );
		if ($canEdit){
			$form = '<fieldset>
				<legend>Map Edit:</legend>
				<form action="">
					<input type="button" onclick="submitMapAction()" value="Submit" />
				</form>
				</fieldset>';
			
			$mapDiv = $mapDiv .$form;
			JHtml::_('script', 'components/com_ukrgb/views/map/js/map-edit.js');
		}
		else 
		{
			JHtml::_('script', 'components/com_ukrgb/views/map/js/map.js');
		}
		$mapid = $article->riverguide->mapid;
		$mapData = json_encode(array(
				'url' => JURI::base() . 'index.php?option=com_ukrgb&tmpl=raw&format=json',
				'mapid' => $mapid,
				'guideid' => $article->riverguide->guideid,
				'mapdata' => UkrgbMapHelper::getBasicMapData($mapid)));
		
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration('var params = ' .$mapData.';');
		
		$pattern = '/{map}/i'; 
		$article->text = preg_replace($pattern, $mapDiv, $article->text);
			
	}
	
}
?>