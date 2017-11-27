<?php

class emailqueue {
	protected $id;
	protected $from;
	protected $to;
	protected $cc;
	protected $bcc;
	protected $subject;
	protected $content;
	protected $attachments = [];
	protected $priority = 0;
	protected $status = false;
	protected $date;
	protected $date_update;

	public function __construct() {}

	public function setId($i) {
		$this->id = (int)$i;
	}

	public function setSubject($s) {
		$this->subject = $s;
	}

	public function setContent($c) {
		$this->content = $c;
	}

	public function setAttachments($a) {
		$this->attachments = json_encode($a);
	}

	public function setPriority($p) {
		$this->priority = (int)$p;
	}

	public function setStatus($s) {
		$this->status = (bool)$s;
	}

	public function setDate($d = null) {
		$this->date = ($d !== null) ? $d : date("Y-m-d H:i:s", time());
	}

	public function setDateUpdate($d = null) {
		$this->date_update = ($d !== null) ? $d : date("Y-m-d H:i:s", time());
	}

	public function insert() {
		global $cfg, $db;

		$query = sprintf(
			"INSERT INTO %s_email_queue (`from`, `to`, `cc`, `bcc`, `subject`, `content`, `attachments`, `status`, `date`, `date_update`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$cfg->db->prefix,
			$db->real_escape_string($this->from),
			$db->real_escape_string($this->to),
			$db->real_escape_string($this->cc),
			$db->real_escape_string($this->bcc),
			$db->real_escape_string($this->subject),
			$db->real_escape_string($this->content),
			$db->real_escape_string($this->attachments),
			$this->status,
			$this->date,
			$this->date_update
		);

		if ($db->query($query)) {
			$this->id = $db->insert_id;
			return true;
		}
		return false;
	}

	public function update() {
		global $cfg, $db;

		$query = sprintf();
	}

	public function delete() {
		global $cfg, $db, $authData;

		$email = new emailqueue();
		$email->setId($this->id);
		$email_obj = $email->returnOneEntry();

		$trash = new trash();
		$trash->setCode(json_encode($email_obj));
		$trash->setDate();
		$trash->setModule($cfg->mdl->folder);
		$trash->setUser($authData["id"]);
		$trash->insert();

		$query = sprintf(
			"DELETE FROM %s_email_queue WHERE id = %s",
			$cfg->db->prefix,
			$cfg->db->prefix,
			$this->id
		);

		$db->query($query);

		return ($email->returnOneEntry() == FALSE) ? TRUE : FALSE;
	}


	public function returnObject() {
		return get_object_vars($this);
	}

	public function returnOneEntry() {
		global $cfg, $db;

		$query = sprintf(
			"SELECT * FROM %s_email_queue WHERE id = %s LIMIT 1",
			$cfg->db->prefix,
			$this->id
		);
		$source = $db->query($query);

		if ($source->num_rows > 0) {
			return $source->fecth_objec();
		}

		return false;
	}

	public function returnAllEntries() {
		global $cfg, $db;

		$query = sprintf(
			"SELECT * FROM %s_email_queue WHERE true",
			$cfg->db->prefix
		);
		$source = $db->query($query);

		if ($source->num_rows > 0) {
			while ($data = $source->fetch_object()) {
				if (!isset($toReturn)) {
					$toReturn = [];
				}

				array_push($toReturn, $data);
			}

			if (count($toReturn) > 0) {
				return $toReturn;
			}
		}
		return false;
	}

	public static function getSettings () {
		global $cfg, $db;

		$query = sprintf("SELECT * FROM %s_email_queue_settings WHERE true",
			$cfg->db->prefix
		);
		$source = $db->query($query);

		while ($data = $source->fetch_object()) {
			if (!isset($list)) {
				$list = [];
			}

			array_push($list, $data);
		}

		foreach ($list as $index => $value) {
			if (!isset($toReturn)) {
				$toReturn = [];
			}

			$toReturn[$value->name] = $value->value;
		}

		return isset($toReturn) ? $toReturn : false;
	}

	public static function sendEmail ($settings = [], $to, $cc, $bcc, $replyTo, $subject, $message, $attach = []) {
		$mail = new PHPMailer();

		$mail->IsSMTP();
		$mail->CharSet = "UTF-8";
		$mail->Host = $settings["server_smtp"];
		$mail->SMTPDebug = $settings["server_debug"];
		$mail->SMTPAuth = TRUE;
		$mail->Port = $settings["server_port"];
		$mail->SMTPSecure = $settings["server_secure"];
		$mail->Username =  $settings["server_username"];
		$mail->Password = $settings["server_password"];

		$mail->SetFrom($settings["server_username"], $settings["server_email_name"]); // ADD SENDER
		$mail->Subject = $subject; // ADD SUBJECT
		$mail->AddAddress($to, "--"); // ADD DESTINATARY

		// ADD CC EMAIL LIST
		foreach($cc as $email => $name) {
			$mail->AddCC($email, $name);
		}

		// ADD BCC EMAIL LIST
		foreach($bcc as $email => $name) {
			$mail->AddBCC($email, $name);
		}

		$mail->AddReplyTo($settings["server_email"]);
		$mail->MsgHTML($message);

		// ADD ATTACH LIST
		if (count($attach) > 0) {
			foreach ($attach as $file) {
				if (file_exists($file)) {
					$mail->addAttachment($file, basename($file));
				}
			}
		}

		if (!$mail->Send()) {
			return FALSE;
		}
		return TRUE;
	}
}
