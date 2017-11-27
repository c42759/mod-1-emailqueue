<?php

if (isset($_POST["submitUninstall"]) && user::isOwner($authData)) {
	$query = bo3::c2r(
		[
			'mod-folder' => $cfg->mdl->folder,
			'prefix' => $cfg->db->prefix
		],
		bo3::mdl_load("db/uninstall.sql")
	);

	if ($db->multi_query($query) != FALSE) {
		while ($db->more_results() && $db->next_result()) {;} // flush multi_queries

		unlink("class/class.emailqueue.php");
		unlink("cron/mod-emailqueue-cron.php");
		unlink("../pages/mod-emailqueue-api.php");

		$mdl = bo3::c2r(
			[
				'lg-message' => $lang["uninstall"]["success"]
			],
			bo3::mdl_load("templates-e/uninstall/message.tpl")
		);
	} else {
		$mdl = bo3::c2r(
			[
				'lg-message' => $lang["uninstall"]["failure"]." : ".$db->error
			],
			bo3::mdl_load("templates-e/uninstall/message.tpl")
		);
	}
} else {
	$mdl = bo3::c2r(
		[
			'lg-message' => $lang["uninstall"]["failure"]
		],
		bo3::mdl_load("templates-e/uninstall/message.htpl")
	);
}

include "pages/module-core.php";
