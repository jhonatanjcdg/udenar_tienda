<?php
$HOSTDB = "localhost";
$NAMEDB = "tienda_db";
$USERDB = "jhonatan";
$PWDB = 'XkgKPVpcwy2[b(/I';

$hostPDO = "mysql:host=$HOSTDB; dbname=$NAMEDB";
$myPDO = new PDO($hostPDO, $USERDB, $PWDB);
?>