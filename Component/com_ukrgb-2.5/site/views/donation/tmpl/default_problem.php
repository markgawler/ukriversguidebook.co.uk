<?php

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
?>
<h1>Ooops!</h1>

<h3>Somthing hasn't quite worked</h3>
<p></p>
<p><?php echo $this->errorMsg; ?></p>
<p>Responce: <?php echo $this->responceCode; ?></p>
<p>Your transaction may not have completed, you should log into your PayPal account to review details of this transaction.</p>
<p>Thank you.</p>
<p></p>




