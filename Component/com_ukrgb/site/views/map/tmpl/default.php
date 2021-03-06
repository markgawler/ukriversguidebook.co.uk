<?php

/**
 * @version		0.1
 * @package		UKRGB - Map
 * @copyright	Copyright (C) 2012 The UK Rivers Guide Book, All rights reserved.
 * @author		Mark Gawler
 * @link		http://www.ukriversguidebook.co.uk
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');

?>
<h1>The UK Rivers Guidebook</h1>
<p><?php echo $this->message; ?></p>
<div id="map" class="ukrgbmap"></div>
<form class="ukrgbmapgr">
<label>OS Grid Ref: </label><input type="text" id="GridRef" size="10" readonly>
<label>   WGS84 Lat: </label><input type="text" id="Lat" size="8" readonly>
<label>Lng: </label><input type="text" id="Lng" size="8" readonly>		
</form>

