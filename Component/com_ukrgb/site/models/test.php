#!/usr/bin/php 
<?php
define ('_JEXEC',1);

require_once 'mappointshelper.php';
print ("\n");
//print ("Hello World\n");

//$content = "PUT-INS/ TAKE-OUTS: Launch at the bridge near Hele Cross (SX 614 609), the highest road access to the Yealm.
//If you want to avoid Blachford Manor (see below), launch near Langham Bridge (SX 608 592), utilising the footpath from the road on river left, just past the bridge.
//You can clamber out at the bridge near Mark’s Bridge (SX 602 572). Parking is limited at these spots, do not block access to farms and houses.
//";

$content = "PUT-INS/ TAKE-OUTS: SX 608 592 Launch at the bridge near Hele Cross ";

UkrgbMapPointsHelper::updateMapPoints($content, 123);


?>