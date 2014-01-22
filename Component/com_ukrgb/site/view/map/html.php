<?php defined( '_JEXEC' ) or die( 'Restricted access' );

class UkrgbViewMapHtml extends JViewHtml
{
	function render()
	{
		$app = JFactory::getApplication();
		$mapId = $this->map->id;
		//$this->params = JComponentHelper::getParams('com_ukrgb');

		//retrieve task list from model
		$model = new UkrgbModelMap();

		$mapParameters = $model->getMapParameters($mapId);

		JHtml::_('behavior.framework');
		JHtml::_('script', 'http://openlayers.org/api/OpenLayers.js');
		JHtml::_('script', 'libraries/ukrgb/proj4js/proj4js-compressed.js');
		//JHtml::_('script', 'components/com_ukrgb/view/map/js/OpenSpace.js');
		JHtml::_('script', 'components/com_ukrgb/view/map/js/map-openlayers.js');

		$params = json_encode(array(
				'url' => JURI::base() . 'index.php?option=com_ukrgb&tmpl=raw&format=json',
				'mapdata' => $mapParameters));

		$document = &JFactory::getDocument();
		$document->addScriptDeclaration('var params = ' .$params.';');

		//display
		return parent::render();
	}
}