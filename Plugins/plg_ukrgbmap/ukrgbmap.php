<?php
/**
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2012 Mark Gawler. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 */

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_SITE . DS . 'components' . DS . 'com_ukrgb' . DS . 'models' . DS . 'maphelper.php';
require_once JPATH_SITE . DS . 'components' . DS . 'com_ukrgb' . DS . 'models' . DS . 'mappointshelper.php';

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
		if (isset($article->id))
		{
			$mapid = UkrgbMapHelper::getMapIdforArticle($article->id);
		
			if (isset($mapid)){
					
				JHtml::_('behavior.framework');
				JHtml::_('script', 'http://openlayers.org/api/OpenLayers.js');
				JHtml::_('script', 'components/com_ukrgb/proj4js/lib/proj4js-compressed.js');
				JHtml::_('script', 'components/com_ukrgb/views/map/js/OpenSpace.js');
				JHtml::_('script', 'components/com_ukrgb/views/map/js/map-openlayers.js');
				JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
				
				
				$mapDiv = '<div id="map" class="ukrgbmap"></div>
				<form class="ukrgbmapgr">
				<label>OS Grid Ref: </label><input type="text" id="GridRef" size="12" readonly>
				<label>   WGS84 Lat: </label><input type="text" id="Lat" size="8" readonly>
				<label>Lng: </label><input type="text" id="Lng" size="8" readonly>		
				</form>';
				
				/*$user = JFactory::getUser();
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
				}*/
	
				$mapData = json_encode(array(
						'url' => JURI::base() . 'index.php?option=com_ukrgb&tmpl=raw&format=json',
						'mapdata' => UkrgbMapHelper::getBasicMapData($mapid)));
				
				$document = &JFactory::getDocument();
				$document->addScriptDeclaration('var params = ' .$mapData.';');
				
				$pattern = '/{map}/i'; 
				$article->text = preg_replace($pattern, $mapDiv, $article->text);
			}
		}
	}
	
	public function onContentAfterSave($context, &$article, $isNew)
	{
		UkrgbMapPointsHelper::updateMapPoints($article->introtext, $article->id, $article->title);
				
	}
}
?>