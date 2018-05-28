<?php

// $mdl = "{c2r-plg-example}";

// bo3::importPlg("example");

bo3::dump(json_encode(['teste', 'mais', 'testes']));

$email = new c1_emailqueue();
$db_list = $email->returnAllEntries();

if ($db_list !== false) {
	foreach ($db_list as $index => $db_item) {
		if (!isset($list)) {
			$list = "";
			$item_tpl = bo3::mdl_load("templates-e/home/row.tpl");
		}

		$db_item->attachments = json_decode($db_item->attachments);

		$list .= bo3::c2r([
			'id' => $db_item->id,
			'to' => $db_item->to,
			'from' => $db_item->from,
			'cc' => !empty($db_item->cc) ? $db_item->cc : '--',
			'bcc' => !empty($db_item->bcc) ? $db_item->bcc : '--',
			'subject' => $db_item->subject,
			'attachments' => count($db_item->attachments),
			'status' => ($db_item->status) ? "fa-toggle-on" : "fa-toggle-off",
			'date' => date('y-m-d', strtotime($db_item->date)),
			'date-updated' => $db_item->date_update,
		], $item_tpl);
	}
}

$mdl = bo3::c2r([
	'list' => isset($list) ? $list : '',
	'lg-from' => $mdl_lang['from'],
	'lg-cc' => $mdl_lang['cc'],
	'lg-bcc' => $mdl_lang['bcc'],
	'lg-date-updated' => $mdl_lang['date-update'],
	'lg-btn-add' => $mdl_lang['btn-add'],
	'lg-btn-settings' => $mdl_lang['btn-settings'],
	'lg-btn-edit' => $mdl_lang['btn-edit'],
	'lg-btn-view' => $mdl_lang['btn-view']
],bo3::mdl_load("templates/home.tpl"));

include "pages/module-core.php";
