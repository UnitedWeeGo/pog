<?php
include "configuration.php";
include "objects/class.database.php";
include "objects/class.plugin.php";

if ($_GET['id'] != ''){
	$plugin = new plugin();
	$plugin->Get($_GET['id']);

	if ($plugin->pluginId){
		header ("Content-Type: application/force-download");
		header('Content-Disposition: attachment; filename="class.'.strtolower($plugin->name).'.php"');
		echo base64_decode($plugin->code);

	}


}
?>