<?php

if (isset($_POST["save"])) {
	if (!empty($_POST["name"])) {
		$settings = new emailqueue();
		$settings->setContent($_POST["name"], $_POST["value"]);
		$settings->setDate();
		$settings->setDateUpdate();

		if ($settings->insertSetting()) {
			header("Location: {$cfg->system->path_bo}/{$lg_s}/0-emailqueue/settings/");
		}
	}
}

$mdl = bo3::c2r([
	"title" => $mdl_lang["settings-add"]["title"],
	"value" => $mdl_lang["settings-add"]["value"],
	"post-name" => isset($_POST["name"]) ? $_POST["name"] : "",
	"post-value" => isset($_POST["value"]) ? $_POST["value"] : ""
], bo3::mdl_load("templates/settings-add.tpl"));
include "pages/module-core.php";
