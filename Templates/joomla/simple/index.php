<?php

/**
 * @package     Joomla.Tutorials
 * @subpackage  Template
 * @copyright   Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license     License GNU General Public License version 2 or later; see LICENSE.txt
 */

// No direct access to this file
defined('_JEXEC') or die;

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
        <head>
                <jdoc:include type="head" />
                <link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/<?php echo $this->template ?>/css/styles.css" type="text/css" />
        </head>
        <body>
                 <div id="container">
                        <div id="header">
                                <jdoc:include type="modules" name="header" />
                        </div>
                        <div id="left">
                                <jdoc:include type="modules" name="left" />
                        </div>
                        <div id="main">
                                <jdoc:include type="message" />
                                <jdoc:include type="component" />                       
                        </div>
                        <div class="clr"></div>
                        <div id="footer">
                                <jdoc:include type="modules" name="footer" />
                        </div>
                </div>
        </body>
</html>