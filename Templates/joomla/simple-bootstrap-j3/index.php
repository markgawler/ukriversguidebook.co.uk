<?php
/**
 * @package     Joomla.Site
* @subpackage  Templates.ukrgb-bsj3
*
* @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

defined('_JEXEC') or die;

// Getting params from template
$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;

// Detecting Active Variables
$option   = $app->input->getCmd('option', '');
$view     = $app->input->getCmd('view', '');
$layout   = $app->input->getCmd('layout', '');
$task     = $app->input->getCmd('task', '');
$itemid   = $app->input->getCmd('Itemid', '');
$sitename = $app->getCfg('sitename');

if($task == "edit" || $layout == "form" )
{
	$fullWidth = 1;
}
else
{
	$fullWidth = 0;
}

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
//$doc->addScript('templates/' .$this->template. '/js/template.js');


$doc->addStyleSheet($this->baseurl . '/media/jui/css/bootstrap.min.css');

// Add Stylesheets
//$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');

// Load optional RTL Bootstrap CSS
//JHtml::_('bootstrap.loadCss', false, $this->direction);

// Add current user information
$user = JFactory::getUser();

// Adjusting content width
if ($this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span6";
}
elseif ($this->countModules('position-7') && !$this->countModules('position-8'))
{
	$span = "span9";
}
elseif (!$this->countModules('position-7') && $this->countModules('position-8'))
{
	$span = "span9";
}
else
{
	$span = "span12";
}



// Logo file or site title param
if ($this->params->get('logoFile'))
{
	$logo = '<img src="'. JUri::root() . $this->params->get('logoFile') .'" alt="'. $sitename .'" />';
}
elseif ($this->params->get('sitetitle'))
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. htmlspecialchars($this->params->get('sitetitle')) .'</span>';
}
else
{
	$logo = '<span class="site-title" title="'. $sitename .'">'. $sitename .'</span>';
}
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	 
	
	<jdoc:include type="head" />
	<!--  Use of Google Font -->
	<link href='http://fonts.googleapis.com/css?family=<?php echo $this->params->get('googleFontName');?>' rel='stylesheet' type='text/css' />
	<style type="text/css">
		h1,h2,h3,h4,h5,h6,.site-title {
			font-family: '<?php echo str_replace(' + ', ' ', $this->params->get(' googleFontName
				'));?>', sans-serif;
		}
		
		/* Layout overides (mostly of bootstram responsive)*/
		body {
			padding-top: 41px;
			padding-bottom: 40px;
		}
		/* remove exsesive margin below search box in nav bar */
		.navbar-form {
			margin-bottom: -15px;
		}
		
		@media ( max-width : 980px) {
			/* Enable use of floated navbar text */
			.navbar-text.pull-right {
				float: none;
				padding-left: 5px;
				padding-right: 5px;
			}
			/* Remove padding above logo when nav menu switches below banner */
			body {
				padding-top: 0px;
			}
		}
		
		/* remove 20px white margin round logo on small screens */
		@media ( max-width : 767px) {
			header {
				margin-right: -20px;
				margin-left: -20px;
			}
		}
	</style>
	<?php $doc->addStyleSheet($this->baseurl . '/media/jui/css/bootstrap-responsive.css');?>
	
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body class="site <?php echo $option
	. ' view-' . $view
	. ($layout ? ' layout-' . $layout : ' no-layout')
	. ($task ? ' task-' . $task : ' no-task')
	. ($itemid ? ' itemid-' . $itemid : '')
	. ($params->get('fluidContainer') ? ' fluid' : '');
?>">

	<!-- Body -->
	<div class="body">
		<!-- Header -->
		<header class="header" role="banner">
			<div class="header-inner clearfix">
				<a class="pull-left" href="<?php echo $this->baseurl; ?>">
					<?php echo $logo;?> <?php if ($this->params->get('sitedescription')) { echo '<div class="site-description">'. htmlspecialchars($this->params->get('sitedescription')) .'</div>'; } ?>
				</a>
			</div>
		</header>
		<!-- Nav -->
		<?php if ($this->countModules('top-menu')) : ?>
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container-fluid">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span> 
						<span class="icon-bar"></span> 
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="#">UKRGB</a>
					<div class="nav-collapse collapse">
						<div class="nav">
							<jdoc:include type="modules" name="top-menu" style="none" />
						</div>
						<?php if ($this->countModules('search')) : ?>
								<div  class="navbar-form pull-right">
									<jdoc:include type="modules" name="search" style="none" />
								</div>
						<?php endif; ?>
						
					</div>
					<!--/.nav-collapse -->
				</div>
			</div>
		</nav>
		<?php endif; ?>
		
		<jdoc:include type="modules" name="banner" style="xhtml" />
		
		
		<main id="content" role="main" class="span12">
			<!-- Begin Content -->
			<jdoc:include type="message" />
			<jdoc:include type="component" />
			<!-- End Content -->
		</main>
			
	</div>
	<!-- Footer -->
	<footer class="footer" role="contentinfo">
		<div class="container-fluid">
			<hr />
			<jdoc:include type="modules" name="footer" style="none" />
			<p class="pull-right"><a href="#top" id="back-top"><?php echo JText::_('TPL_UKRGBBSJ3_BACKTOTOP'); ?></a></p>
			<p>&copy; <?php echo $sitename; ?> <?php echo date('Y');?></p>
		</div>
	</footer>
	<jdoc:include type="modules" name="debug" style="none" />
</body>
</html>
