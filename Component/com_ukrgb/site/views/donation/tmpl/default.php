<?php

/**
 * @version		$Id: default.php 15 2009-11-02 18:37:15Z chdemko $
 * @package		Joomla16.Tutorials
 * @subpackage	Components
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @author		Christophe Demko
 * @link		http://joomlacode.org
 * @license		License GNU General Public License version 2 or later
 */
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h1>Thankyou</h1>

<h3>Thank you <?php echo $this->name; ?> for your donation of <?php echo $this->value; ?></h3>
<p>Your transaction has been completed and a receipt for your donation has been sent to you. 
You may log into your account at area51.ukriversguidebook.co.uk to view details of this transaction.</p>
<p>Thank you.</p>
<p>The Management.</p>
<p>Status: <?php echo $this->status; ?></p>



