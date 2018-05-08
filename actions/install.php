<?php

if (isset($_POST["submitInstall"]) && user::isOwner($authData)) {
	$query = bo3::c2r([
		'mod-name' => $cfg->mdl->name,
		'mod-folder' => $cfg->mdl->folder,
		'prefix' => $cfg->db->prefix
	], bo3::mdl_load("db/install.sql"));

	if ($db->multi_query($query) != FALSE) {
		while ($db->more_results() && $db->next_result()) {;} // flush multi_queries

		copy("modules/{$cfg->mdl->folder}/install/class.1-emailqueue.php", "class/class.1-emailqueue.php");
		copy("modules/{$cfg->mdl->folder}/install/mod-emailqueue-cron.php", "cron/mod-emailqueue-cron.php");
		copy("modules/{$cfg->mdl->folder}/install/mod-emailqueue-api.php", "../pages/mod-emailqueue-api.php");

		$mdl = bo3::c2r([
			'lg-message' => $lang["install"]["success"]
		], bo3::mdl_load("templates-e/install/message.tpl"));
	} else {
		$mdl = bo3::c2r([
			'lg-message' => $lang["install"]["failure"]." : ".$db->error,
		], bo3::mdl_load("templates-e/install/message.tpl"));
	}
} else {
	$mdl = bo3::c2r([
		'lg-install' =>$lang["install"]["question"],
		'lg-yes' => $lang["common"]["a-yes"],
		'lg-no' => $lang["common"]["a-no"]
	], bo3::mdl_load("templates-e/install/form.tpl"));
}

include "pages/module-core.php";
