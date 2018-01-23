<?php

$settings = new emailqueue();
$settings->setId($id);

if (isset($_POST["save"])) {
	if (!empty($_POST["input-name"])) {
		$settings->setContent($_POST["input-name"], $_POST["input-value"]);
		$settings->setDateUpdate();

		if ($settings->updateSetting()) {
			header("Location: {$cfg->system->path_bo}/{$lg_s}/0-emailqueue/settings/");
		}
	}
} else {
	$returned_entry = $settings->returnOneSetting();
}

$mdl = bo3::c2r([
	"id" => $id,

	"title" => $mdl_lang["settings-add"]["title"],
	"value" => $mdl_lang["settings-add"]["value"],

	"post-name" => isset($_POST["name"]) ? $_POST["name"] : $returned_entry->name,
	"post-value" => isset($_POST["value"]) ? $_POST["value"] : $returned_entry->value
], bo3::mdl_load("templates/settings-edit.tpl"));

include "pages/module-core.php";
