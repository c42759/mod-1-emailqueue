<?php

$settings = c1_emailqueue::returnAllSettings();
foreach ($settings as $index => $item) {
	if (!isset($list)) {
		$list = "";
		$item_tpl = bo3::mdl_load("templates-e/settings/item.tpl");
	}

	$list .= bo3::c2r([
		'id' => $item->id,
		'title' => $item->name,
		'value' => $item->value,
		'date' => $item->date,
		'date-update' => $item->date_update
	], $item_tpl);
}

$mdl = bo3::c2r([
	'lg-title' => $mdl_lang['label']['title'],
	'lg-value' => $mdl_lang['label']['value'],
	'lg-date' => $mdl_lang['label']['date'],

	'table-body' => isset($list) ? $list : bo3::mdl_load('templates-e/home/no-results.tpl'),

	'lg-no-results' => $mdl_lang['label']['no-results']
], bo3::mdl_load("templates/settings.tpl"));

include "pages/module-core.php";
