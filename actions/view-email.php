<?php

$email_view = new c1_emailqueue();
$email_view->setId($id);
$email_view = $email_view->returnOneEntry();

$tpl = bo3::c2r([
	"content" => $email_view->content
], bo3::mdl_load("templates/view-email.tpl"));
