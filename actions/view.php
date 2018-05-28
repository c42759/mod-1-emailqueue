<?php

$email_view = new c1_emailqueue();
$email_view->setId($id);
$email_view = $email_view->returnOneEntry();

$mdl = bo3::c2r([
	"lg-from" => $mdl_lang["from"],
	"lg-to" => $mdl_lang["to"],
	"lg-subject" => $mdl_lang["subject"],
	"lg-content" => $mdl_lang["content"],
	"lg-priority" => $mdl_lang["priority"],
	"lg-date" => $mdl_lang["date"],
	"lg-date-update" => $mdl_lang["date-update"],
	"lg-status" => $mdl_lang["status"],
	"lg-go-back" => $mdl_lang["back"],

	"from" => $email_view->from,
	"to" => $email_view->to,
	"subject" => $email_view->subject,
	"id" => $email_view->id,
	"priority" => $email_view->priority,
	"status" => $email_view->status,
	"date" => $email_view->date,
	"date-update" => $email_view->date_update,
],bo3::mdl_load("templates/view.tpl"));

include "pages/module-core.php";
