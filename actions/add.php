<?php
$sender = new c1_emailqueue();
$sender->setSetting("server_email", "");
$sender = $sender->returnSettingByName();

if (isset($_POST["save"])) {
	if (!empty($_POST["to"]) && !empty($_POST["subject"])) {
		$queue = new c1_emailqueue();
		$queue->setFrom($sender->value);
		$queue->setTo($_POST["to"]);
		$queue->setSubject($_POST["subject"]);
		$queue->setContent($_POST["content"]);
		$queue->setAttachments([]);
		$queue->setPriority((!empty($_POST["priority"])) ? $_POST["priority"] : "0");
		$queue->setDate();

		if ($queue->insert()) {
			$textToPrint = $mdl_lang["add"]["success"];
		} else {
			$textToPrint = $mdl_lang["add"]["failure"];
		}

		$mdl = bo3::c2r([
			"content" => (isset($textToPrint)) ? $textToPrint : ""
		], bo3::mdl_load("templates/result.tpl"));
	} else {
		$mdl = bo3::c2r([
			"content" => $mdl_lang["add"]["failure"]
		], bo3::mdl_load("templates/result.tpl"));
	}
} else {
	$mdl = bo3::c2r([
		"lg-to" => $mdl_lang["to"],
		"lg-subject" => $mdl_lang["subject"],
		"lg-priority" => $mdl_lang["priority"],
		"lg-content" => $mdl_lang["content"]
	],bo3::mdl_load("templates/add.tpl"));
}

include "pages/module-core.php";
