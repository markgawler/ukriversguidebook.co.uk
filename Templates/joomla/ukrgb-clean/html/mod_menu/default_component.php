<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_menu
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

// Note. It is important to remove spaces between elements.
$class = $item->anchor_css ? 'class="'.$item->anchor_css.'" ' : '';
$title = $item->anchor_title ? 'title="'.$item->anchor_title.'" ' : '';
if ($item->menu_image)
	{
		$item->params->get('menu_text', 1) ?
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" /><span class="image-title">'.$item->title.'</span> ' :
		$linktype = '<img src="'.$item->menu_image.'" alt="'.$item->title.'" />';
}
else { $linktype = $item->title;
}

$link = $item->flink;
$dt = '';
$caret = '';
if ($item->deeper)
{
	$dt = 'class="dropdown-toggle" data-toggle="dropdown" ';
	if ($item->level == 1)
	{
		$link = '#';
		$caret = ' <span class="caret"></span>';
	}
}


switch ($item->browserNav) :
	default:
	case 0:
?><a <?php echo $dt.$class; ?>href="<?php echo $link; ?>" <?php echo $title; ?>><?php echo $linktype.$caret; ?></a><?php
		break;
	case 1:
		// _blank
?><a <?php echo $dt.$class; ?>href="<?php echo $link; ?>" target="_blank" <?php echo $title; ?>><?php echo $linktype.$caret; ?></a><?php
		break;
	case 2:
	// window.open
?><a <?php echo $dt.$class; ?>href="<?php echo $ink; ?>" onclick="window.open(this.href,'targetWindow','toolbar=no,location=no,status=no,menubar=no,scrollbars=yes,resizable=yes');return false;" <?php echo $title; ?>><?php echo $linktype.$caret; ?></a>
<?php
		break;
endswitch;
