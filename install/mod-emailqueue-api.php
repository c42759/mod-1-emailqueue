<?php

$settings = emailqueue::getSettings();

$query = sprintf(
	"SELECT * FROM %s_email_queue WHERE status = %s AND priority > %s ORDER BY %s LIMIT %s",
	$cfg->db->prefix, 0, 0, "RAND()", 1
);

$source = $db->query($query);

if ($source->num_rows == 0) {
	// GET USER FROM QUEUE IF DOESNT EXIST ANY EMAIL WITH PRIORITY
	$query = sprintf(
		"SELECT * FROM %s_email_queue WHERE status = %s AND date <= '%s' ORDER BY %s LIMIT %s",
		$cfg->db->prefix, 0, date('Y-m-d H:i:s', time() + ($settings["delay"] * 60)), "RAND()", 1
	);

	$source = $db->query($query);
}

while ($data = $source->fetch_object()) {
	$data->cc = explode(",", $data->cc);
	$data->bcc = explode(",", $data->bcc);
	$data->attachments = !empty($data->attachments) ? json_decode($data->attachments) : [];

	$send = emailqueue::sendEmail($settings, $data->to, $data->cc, $data->bcc, $data->from, $data->subject, $data->content, $data->attachments);

	if ($send) {
		// UPDATE QUEUE STATUS TO TRUE
		$query = sprintf(
			"UPDATE %s_email_queue SET status = %s, date_update = '%s' WHERE id = %s",
			$cfg->db->prefix, 1, date("Y-m-d H:i:s", time()), $data->id
		);

		$db->query($query);
	}
}

$tpl = "";
