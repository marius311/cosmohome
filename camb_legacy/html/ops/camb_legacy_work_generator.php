#!/usr/bin/php5

<?php

require_once("../inc/util.inc");
require_once("../inc/db.inc");

db_init();

$cushion = 1000;

$old_time = time() - (3600 * 24 * 14);

$result = _mysql_query("select count(*) as count from result r inner join app a on r.appid=a.id where r.server_state=2 and a.name='camb'");
$count = _mysql_fetch_object($result);
_mysql_free_result($result);

$unsent = $count->count;

echo "Unsent: $unsent\n";

if ($unsent < $cushion){
    echo "MAKING\n";
    $tomake = $cushion - $unsent;
    system("/root/projects/cosmohome/bin/camb_legacy_make_params $tomake");
}
else{
    echo "NOT MAKING\n";
}

?>
