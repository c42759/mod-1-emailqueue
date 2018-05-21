<?php

$obj = new c1_emailqueue();
$obj->setId($id);

if (isset($_POST["input-submit"])) {
	if ($obj->deleteSetting()) {
		header("Location: {$cfg->system->path_bo}/{$lg_s}/1-emailqueue/settings/");
	}
} else {
	$returned_entry = $obj->returnOneSetting();
}

$mdl = bo3::c2r([
	"lg-del" => $mdl_lang["entry-del"]["button-del"],
	"lg-cancel" => $mdl_lang["entry-del"]["button-cancel"],

	"id" => $id,
	"phrase" => $mdl_lang["entry-del"]["phrase"],
	"title" => $returned_entry->name
], bo3::mdl_load("templates/settings-del.tpl"));

include "pages/module-core.php";
