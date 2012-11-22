<?php
/**
 * @version		$Id: ukrgb.php,v 1.1 2012/05/18 20:02:05 mrfg Exp $
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

class plgContentUkrgb extends JPlugin {
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
		
		$pattern = '/{map\s*.*?}/i';
		/*
	 	* Insert a map if there is a map tag
	 	* Dont incluse all the JS and CSS unless its needed.*/
		
		if (preg_match($pattern, $article->text) === 1 )
		{
			JHtml::_('behavior.framework');
			JHtml::_('script', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.js');
			JHtml::_('script', 'components/com_ukrgb/views/map/js/map.js');
			JHtml::_('stylesheet', 'http://cdn.leafletjs.com/leaflet-0.4.5/leaflet.css');
			JHtml::_('stylesheet','components/com_ukrgb/views/map/CSS/map.css');
				
			$params = json_encode(array(
					'url' => JURI::base() . 'index.php?option=com_ukrgb&task=map&tmpl=raw&format=json',
					'mapid' => '123',));
				
			$document = &JFactory::getDocument();
			$document->addScriptDeclaration('var params = ' .$params.';');
				
			$mapDiv = '<div id="map"></div>';				
			$article->text = preg_replace($pattern, $mapDiv, $article->text);
		}
		
	}
	
	public function onContentBeforeDisplay($context, &$article, &$params, $page = 0)
	{
		/*
		 * Is this a river guide for which we have data to  display?
		*/
		
		// is this actually an article
		if (isset($article->text))
		{
			//$article->text = "<h1>My Headder</h1><br>".$article->text;
			
			$db =& JFactory::getDBO();
			$query = "SELECT ".$db->nameQuote('article_id').", ".$db->nameQuote('map_id').", ".$db->nameQuote('grade')." FROM ".$db->nameQuote('#__ukrgb_riverguides').
				" WHERE ".$db->nameQuote('article_id')."=".$article->id.";";
			
			//error_log($query);
			
			$db->setQuery($query);
			$result = $db->loadRow();
			if (isset($result))
			{
				$article->text = "<ul><li>Guide: ".$result[0]."</li>".
				 "<li>Map:   ".$result[1]."</li>".
				 "<li>Grade: ".$result[2]."</ul>".
				 $article->text;
			}
	
			
		}
	}
}
?>