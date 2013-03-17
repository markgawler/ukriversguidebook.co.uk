<?php
/**
 * @version    $Id: index.php,v 1.14 2012/02/21 17:57:44 mrfg Exp $
 * @package    Joomla.Site
 * @subpackage  Templates.ukrgb
 * @copyright  Copyright (C) 2005 - 2011 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access.
defined('_JEXEC') or die;

// check modules
$showRightColumn  = ($this->countModules('position-3') or $this->countModules('position-6') or $this->countModules('position-8'));
$showbottom      = ($this->countModules('position-9') or $this->countModules('position-10') or $this->countModules('position-11'));
$showleft      = ($this->countModules('position-4') or $this->countModules('position-7') or $this->countModules('position-5'));

if ($showRightColumn==0 and $showleft==0) {
  $showno = 0;
}

JHtml::_('behavior.framework', true);

// get params
$color      = $this->params->get('templatecolor');
$logo      = $this->params->get('logo');
$navposition  = $this->params->get('navposition');
$app      = JFactory::getApplication();
$doc      = JFactory::getDocument();
$templateparams  = $app->getTemplate(true)->params;

//$doc->addScript($this->baseurl.'/templates/ukrgb/javascript/md_stylechanger.js', 'text/javascript', true);
?>
<?php if(!$templateparams->get('html5', 0)): ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php else: ?>
  <?php echo '<!DOCTYPE html>'; ?>
<?php endif; ?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>" >
  <head>
    <meta http-equiv="X-UA-Compatible" content="chrome=1">
    <jdoc:include type="head" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ukrgb/css/position.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ukrgb/css/layout.css" type="text/css" media="screen,projection" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ukrgb/css/print.css" type="text/css" media="Print" />
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ukrgb/css/ukrgb.css" type="text/css" />
<?php
  $files = JHtml::_('stylesheet','templates/ukrgb/css/general.css',null,false,true);
  if ($files):
    if (!is_array($files)):
      $files = array($files);
    endif;
    foreach($files as $file):
?>
    <link rel="stylesheet" href="<?php echo $file;?>" type="text/css" />
<?php
     endforeach;
  endif;
?>
    <?php if ($this->direction == 'rtl') : ?>
    <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/ukrgb/css/template_rtl.css" type="text/css" />
    <?php endif; ?>
    <!--[if lte IE 6]>
      <link href="<?php echo $this->baseurl ?>/templates/ukrgb/css/ieonly.css" rel="stylesheet" type="text/css" />
    <![endif]-->
    <!--[if IE 7]>
      <link href="<?php echo $this->baseurl ?>/templates/ukrgb/css/ie7only.css" rel="stylesheet" type="text/css" />
    <![endif]-->
<?php if($templateparams->get('html5', 0)) { ?>
    <!--[if lt IE 9]>
      <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/ukrgb/javascript/html5.js"></script>
    <![endif]-->
<?php } ?>
    <script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/ukrgb/javascript/hide.js"></script>

    <script type="text/javascript">
      var big ='<?php echo (int)$this->params->get('wrapperLarge');?>%';
      var small='<?php echo (int)$this->params->get('wrapperSmall'); ?>%';
      var altopen='<?php echo JText::_('TPL_UKRGB_ALTOPEN',true); ?>';
      var altclose='<?php echo JText::_('TPL_UKRGB_ALTCLOSE',true); ?>';
      var bildauf='<?php echo $this->baseurl ?>/templates/ukrgb/images/plus.png';
      var bildzu='<?php echo $this->baseurl ?>/templates/ukrgb/images/minus.png';
      var rightopen='<?php echo JText::_('TPL_UKRGB_TEXTRIGHTOPEN',true); ?>';
      var rightclose='<?php echo JText::_('TPL_UKRGB_TEXTRIGHTCLOSE'); ?>';
      var fontSizeTitle='<?php echo JText::_('TPL_UKRGB_FONTSIZE'); ?>';
      var bigger='<?php echo JText::_('TPL_UKRGB_BIGGER'); ?>';
      var reset='<?php echo JText::_('TPL_UKRGB_RESET'); ?>';
      var smaller='<?php echo JText::_('TPL_UKRGB_SMALLER'); ?>';
      var biggerTitle='<?php echo JText::_('TPL_UKRGB_INCREASE_SIZE'); ?>';
      var resetTitle='<?php echo JText::_('TPL_UKRGB_REVERT_STYLES_TO_DEFAULT'); ?>';
      var smallerTitle='<?php echo JText::_('TPL_UKRGB_DECREASE_SIZE'); ?>';
    </script>
    
<!-- Google --> 
    <script type="text/javascript">
      var _gaq = _gaq || [];
      _gaq.push(['_setAccount', 'UA-26908210-1']);
      _gaq.push(['_setDomainName', 'ukriversguidebook.co.uk']);
      _gaq.push(['_trackPageview']);
      
      (function() {     
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);   
      })();
      
    </script>

  </head>

  <body>

<div id="all">
  <div id="back">
  <?php if(!$templateparams->get('html5', 0)): ?>
    <div id="header">
      <?php else: ?>
    <header id="header">
      <?php endif; ?>
      
        <div class="logoheader">
          <div class= "ukrgb-search">
             <jdoc:include type="modules" name="position-search" />
          </div>
          <h1 id="logo">
          <?php if ($logo != null ): ?>
          <img src="<?php echo $this->baseurl ?>/<?php echo htmlspecialchars($logo); ?>" alt="<?php echo htmlspecialchars($templateparams->get('sitetitle'));?>" />
          <?php else: ?>
          <?php echo htmlspecialchars($templateparams->get('sitetitle'));?>
          <?php endif; ?>
          <span class="header1">
          <?php echo htmlspecialchars($templateparams->get('sitedescription'));?>
          </span></h1>
        </div><!-- end logoheader -->

          <ul class="skiplinks">
            <li><a href="#main" class="u2"><?php echo JText::_('TPL_UKRGB_SKIP_TO_CONTENT'); ?></a></li>
            <li><a href="#nav" class="u2"><?php echo JText::_('TPL_UKRGB_JUMP_TO_NAV'); ?></a></li>
            <?php if($showRightColumn ):?>
            <li><a href="#additional" class="u2"><?php echo JText::_('TPL_UKRGB_JUMP_TO_INFO'); ?></a></li>
            <?php endif; ?>
          </ul>
          <h2 class="unseen"><?php echo JText::_('TPL_UKRGB_NAV_VIEW_SEARCH'); ?></h2>
          <h3 class="unseen"><?php echo JText::_('TPL_UKRGB_NAVIGATION'); ?></h3>
          <div id="ukrgb-banner">
             <jdoc:include type="modules" name="position-1-left" />
             <jdoc:include type="modules" name="position-1-right" />
             <br class="clr" />
          </div>

          <div id="line">
          <!-- <div id="fontsize"></div> -->
          <h3 class="unseen"><?php echo JText::_('TPL_UKRGB_SEARCH'); ?></h3>
          <jdoc:include type="modules" name="position-0" />
          </div> <!-- end line -->
    <div id="header-image">
      <jdoc:include type="modules" name="position-15" />
      <!-- <?php if ($this->countModules('position-15')==0): ?>
          <img src="<?php echo $this->baseurl ?>/templates/ukrgb/images/ukrgb_logo.jpg"  alt="<?php echo JText::_('TPL_UKRGB_LOGO'); ?>" />  
      <?php endif; ?> -->
        
    </div>
    <?php if (!$templateparams->get('html5', 0)): ?>
      </div><!-- end header -->
    <?php else: ?>
      </header><!-- end header -->
    <?php endif; ?>
    <div id="<?php echo $showRightColumn ? 'contentarea2' : 'contentarea'; ?>">
          <div id="breadcrumbs">

              <jdoc:include type="modules" name="position-2" />

          </div>

          <?php if ($navposition=='left' AND $showleft) : ?>

            <?php if(!$this->params->get('html5', 0)): ?>
              <div class="left1 <?php if ($showRightColumn==NULL){ echo 'leftbigger';} ?>" id="nav">
            <?php else: ?>
              <nav class="left1 <?php if ($showRightColumn==NULL){ echo 'leftbigger';} ?>" id="nav">
            <?php endif; ?>

                <jdoc:include type="modules" name="position-7" style="ukrgbDivision" headerLevel="3" />
                <jdoc:include type="modules" name="position-4" style="ukrgbHide" headerLevel="3" state="0 " />
                <jdoc:include type="modules" name="position-5" style="ukrgbTabs" headerLevel="2"  id="3" />

            <?php if(!$this->params->get('html5', 0)): ?>
              </div><!-- end navi -->
            <?php else: ?>
              </nav>
            <?php endif; ?>

          <?php endif; ?>

          <div id="<?php echo $showRightColumn ? 'wrapper' : 'wrapper2'; ?>" <?php if (isset($showno)){echo 'class="shownocolumns"';}?>>

            <div id="main">

            <?php if ($this->countModules('position-12')): ?>
              <div id="top"><jdoc:include type="modules" name="position-12"   />
              </div>
            <?php endif; ?>

              <jdoc:include type="message" />
              <jdoc:include type="component" />

            </div><!-- end main -->

          </div><!-- end wrapper -->

        <?php if ($showRightColumn) : ?>
          <h2 class="unseen">
            <?php echo JText::_('TPL_UKRGB_ADDITIONAL_INFORMATION'); ?>
          </h2>
          <div id="close">
            <a href="#" onclick="auf('right')">
              <span id="bild">
                <?php echo JText::_('TPL_UKRGB_TEXTRIGHTCLOSE'); ?></span></a>
          </div>

        <?php if (!$templateparams->get('html5', 0)): ?>
          <div id="right">
        <?php else: ?>
          <aside id="right">
        <?php endif; ?>

            <a id="additional"></a>
            <jdoc:include type="modules" name="position-6" style="ukrgbDivision" headerLevel="3"/>
            <jdoc:include type="modules" name="position-8" style="ukrgbDivision" headerLevel="3"  />
            <jdoc:include type="modules" name="position-3" style="ukrgbDivision" headerLevel="3"  />

        <?php if(!$templateparams->get('html5', 0)): ?>
          </div><!-- end right -->
        <?php else: ?>
          </aside>
        <?php endif; ?>
      <?php endif; ?>

      <?php if ($navposition=='center' AND $showleft) : ?>

        <?php if (!$this->params->get('html5', 0)): ?>
          <div class="left <?php if ($showRightColumn==NULL){ echo 'leftbigger';} ?>" id="nav" >
        <?php else: ?>
          <nav class="left <?php if ($showRightColumn==NULL){ echo 'leftbigger';} ?>" id="nav">
        <?php endif; ?>

            <jdoc:include type="modules" name="position-7"  style="ukrgbDivision" headerLevel="3" />
            <jdoc:include type="modules" name="position-4" style="ukrgbHide" headerLevel="3" state="0 " />
            <jdoc:include type="modules" name="position-5" style="ukrgbTabs" headerLevel="2"  id="3" />

        <?php if (!$templateparams->get('html5', 0)): ?>
          </div><!-- end navi -->
        <?php else: ?>
          </nav>
        <?php endif; ?>
      <?php endif; ?>

          <div class="wrap"></div>

        </div> <!-- end contentarea -->

      </div><!-- back -->

    </div><!-- all -->

    <div id="footer-outer">

    <?php if ($showbottom) : ?>
      <div id="footer-inner">

        <div id="bottom">
          <?php if ($this->countModules('position-9')): ?>
          <div class="box box1"> <jdoc:include type="modules" name="position-9" style="ukrgbDivision" headerlevel="3" /></div>
          <?php endif; ?>
             <?php if ($this->countModules('position-10')): ?>
          <div class="box box2"> <jdoc:include type="modules" name="position-10" style="ukrgbDivision" headerlevel="3" /></div>
          <?php endif; ?>
          <?php if ($this->countModules('position-11')): ?>
          <div class="box box3"> <jdoc:include type="modules" name="position-11" style="ukrgbDivision" headerlevel="3" /></div>
          <?php endif ; ?>
        </div>
      </div>
    <?php endif ; ?>

      <div id="footer-sub">

      <?php if (!$templateparams->get('html5', 0)): ?>
        <div id="footer">
      <?php else: ?>
        <footer id="footer">
      <?php endif; ?>

          <jdoc:include type="modules" name="position-14" />
          <p>
            <?php echo JText::_('TPL_UKRGB_POWERED_BY');?> <a href="http://www.joomla.org/">Joomla!&#174;</a>
          </p>

      <?php if (!$templateparams->get('html5', 0)): ?>
        </div><!-- end footer -->
      <?php else: ?>
        </footer>
      <?php endif; ?>

      </div>

    </div>
    <jdoc:include type="modules" name="debug" />
  </body>
</html>
