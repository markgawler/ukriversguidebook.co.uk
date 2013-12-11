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
//$params = JFactory::getApplication()->getTemplate(true)->params;

$app = JFactory::getApplication();
$doc = JFactory::getDocument();
$this->language = $doc->language;
$sitename = $app->getCfg('sitename');

$itemid   = $app->input->getCmd('Itemid', '');

$phpbbLayout = ($itemid == $this->params->get('forumItemId') ? 'phpbb-layout' : '');

// Add JavaScript Frameworks
JHtml::_('bootstrap.framework');
JHtml::_('jquery.framework');
//$doc->addScript('templates/' .$this->template. '/js/template.js');


//$doc->addStyleSheet($this->baseurl . '/media/jui/css/bootstrap.min.css');
//$doc->addStyleSheet($this->baseurl . '/media/jui/css/bootstrap-responsive.css');

// Add Stylesheets
$doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
$doc->addStyleSheet('templates/'.$this->template.'/css/tweeks.css');

// Add current user information
$user = JFactory::getUser();
$userMessage = ($user->id ? $user->name : 'Sign In');

?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<jdoc:include type="head" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	
	<!--[if lt IE 9]>
		<script src="<?php echo $this->baseurl ?>/media/jui/js/html5.js"></script>
	<![endif]-->
</head>

<body>
	<div id="wrapper" class="container bg">
		<!-- Headder -->
		<div id="wrapper-header">
			<div id="header" class="row">
	          <div id="logo" class="span12">
	          	<img src="http://placehold.it/1170x100">
	            <!--  <jdoc:include type="modules" name="logo" style="xhtml" />
	            -->
	          </div>  <!-- end logo -->
	        </div>  <!-- row headder-->
	        
	        <div id="banner" class="row banner">
	        	<div id="banner-left" class="span6">
	        		<jdoc:include type="modules" name="banner-left" style="none" />
		          	<!--<img src="http://placehold.it/570x80">-->
	        	</div>
	        	<div id="banner-right" class="span6 hidden-phone">
	        		<jdoc:include type="modules" name="banner-right" style="none" />
	        		<!--<img src="http://placehold.it/570x80">-->
	        	</div>
	        </div> <!-- banner -->
		</div>
		<!-- Navigation -->
		<div id="mainmenu" class="row">
          <div id="menu-wrapper" class="span12">
            <div id="menu">
              <div class="navbar">
                <div class="navbar-inner">
                
                  <div class="container">
                    
                    <a class="btn btn-navbar" data-toggle="collapse" data-target=".navbar-responsive-collapse">
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                      <span class="icon-bar"></span>
                    </a>   
                    <a class="brand" href="#">UKRGB</a>
                    
                    <div class="nav-collapse collapse navbar-responsive-collapse"> 
                   	<jdoc:include type="modules" name="menu" style="none" />
                   	   
                   	<ul class="nav pull-right">
						<li class="navbar-form"><jdoc:include type="modules" name="search" style="none" /></li>
						<!-- The drop down menu -->
          				<li class="dropdown">
            			<a class="dropdown-toggle" href="#" data-toggle="dropdown"><?php echo $userMessage;?><strong class="caret"></strong></a>
            			<div class="dropdown-menu" style="padding: 15px; padding-bottom: 0px;">
              				<!-- Login form here -->
							<jdoc:include type="modules" name="login" style="none" />
						</div>
						</li>
					</ul>		                	
                   	                	
                    </div>  <!-- end collapse -->
                    
                  </div>  <!-- end container -->
                </div>  <!-- end navbar-inner  -->
              </div>  <!-- end navbar -->
          </div>  <!-- end menu -->
          </div>  <!-- end menu-wrapper -->
        </div>  <!-- row mainmenu -->
        
        <div id="breadcrumb" class="row">
          <div id="breadcrumb-wrapper" class="span12">
            <jdoc:include type="modules" name="breadcrumb" style="none" />
          </div>  <!-- end breadcrumb-wrapper -->
        </div>  <!-- row breadcrumb-->

        <!-- Content -->
		<div id="main" class="row">

			<main id="content" class="span9 <?php echo $phpbbLayout?> "> 
				<div class="pad-main">
					<jdoc:include type="message" /> 
					<jdoc:include type="component" />
				</div> 
			</main>
			<div id="aside" class="span3 <?php echo $phpbbLayout?> ">
				<div class="pad-aside">
					<jdoc:include type="modules" name="aside" style="well" />
				</div>
			</div>

		</div>
		<!-- main -->

		<!-- Footer -->
		<footer class="footer" role="contentinfo">
			<div class="container">
				<!-- <hr /> -->
				<jdoc:include type="modules" name="footer" style="none" />
				<p class="pull-right"><a href="#top" id="back-top"><?php echo JText::_('TPL_UKRGBCLEAN_BACKTOTOP'); ?></a></p>
				<p>&copy; <?php echo $sitename; ?> <?php echo date('Y');?></p>
			</div>
		</footer>
		<jdoc:include type="modules" name="debug" style="none" />
	</div><!-- wrapper -->
</body>
</html>
