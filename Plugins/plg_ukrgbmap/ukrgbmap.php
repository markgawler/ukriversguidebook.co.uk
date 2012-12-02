<?php
/**
 * @version		$Id: ukrgbmaps.php,v 1.1 2012/05/18 20:02:05 mrfg Exp $
 * @package		Joomla
 * @subpackage	Content
 * @copyright	Copyright (C) 2005 - 2008 Open Source Matters. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

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
			// || !isset($article->riverguide->mapid))
			return;
				
		
		JHtml::_('behavior.framework');
		JHtml::_('script', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js');
		JHtml::_('script', 'components/com_ukrgb/views/map/js/map.js');
		JHtml::_('stylesheet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css');
		JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
		
		$mapData = json_encode(array(
				'url' => JURI::base() . 'index.php?option=com_ukrgb&tmpl=raw&format=json',
				'mapid' => $article->riverguide->mapid));
		
		$document = &JFactory::getDocument();
		$document->addScriptDeclaration('var params = ' .$mapData.';');
		
		$mapDiv = '<div id="map"></div>';
		$pattern = '/{map\s*.*?}/i'; 
		$article->text = preg_replace($pattern, $mapDiv, $article->text);
			
	}
	
}
?>