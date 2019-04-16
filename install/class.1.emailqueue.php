<?php

class c1_emailqueue {
	protected $id;
	protected $from;
	protected $to;
	protected $cc = "";
	protected $bcc = "";
	protected $subject;
	protected $content;
	protected $attachments = [];
	protected $priority = 0;
	protected $failure = 0;
	protected $status = false;
	protected $date;

	protected $name;
	protected $value;

	public function __construct() {}

	public function setId($i) {
		$this->id = (int)$i;
	}

	public function setFrom($f) {
		$this->from = $f;
	}

	public function setTo($t) {
		$this->to = $t;
	}

	public function setCc($c) {
		$this->cc = $c;
	}

	public function setBcc($b) {
		$this->bcc = $c;
	}

	public function setSubject($s) {
		$this->subject = $s;
	}

	public function setContent($d) {
		$this->content = $d;
	}

	public function setAttachments($a = []) {
		if (is_array($a) && count($a) > 0) {
			$this->attachments = json_encode($a);
			return true;
		}

		return false;
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

	public function setSetting ($n, $v) {
		$this->name = $n;
		$this->value = $v;
	}

	public function insert() {
		global $cfg, $db;

		$query = sprintf(
			"INSERT INTO %s_1_email_queue (`from`, `to`, `cc`, `bcc`, `subject`, `content`, `attachments`, `priority`, `status`) VALUES ('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')",
			$cfg->db->prefix,
			$db->real_escape_string($this->from),
			$db->real_escape_string($this->to),
			$db->real_escape_string($this->cc),
			$db->real_escape_string($this->bcc),
			$db->real_escape_string($this->subject),
			$db->real_escape_string($this->content),
			$db->real_escape_string($this->attachments),
			$this->priority,
			$this->status
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

		$email = new c1_emailqueue();
		$email->setId($this->id);
		$email_obj = $email->returnOneEntry();

		$trash = new trash();
		$trash->setCode(json_encode($email_obj));
		$trash->setDate();
		$trash->setModule($cfg->mdl->folder);
		$trash->setUser($authData["id"]);
		$trash->insert();

		$query = sprintf(
			"DELETE FROM %s_1_email_queue WHERE id = %s",
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
			"SELECT * FROM %s_1_email_queue WHERE id = %s LIMIT 1",
			$cfg->db->prefix,
			$this->id
		);
		$source = $db->query($query);

		if ($source->num_rows > 0) {
			return $source->fetch_object();
		}

		return false;
	}

	public function returnAllEntries() {
		global $cfg, $db;

		$query = sprintf(
			"SELECT * FROM %s_1_email_queue WHERE true",
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

	public function addFailure () {
		global $cfg, $db;

		$query = sprintf(
			"UPDATE %s_1_email_queue SET failure = failure + 1 WHERE id = '%s'",
			$cfg->db->prefix,
			$this->id
		);

		return $db->query($query);
	}

	public function insertSetting () {
		global $cfg, $db;

		$query = sprintf("INSERT INTO %s_1_email_queue_settings (`name`, `value`) VALUES ('%s', '%s')",
			$cfg->db->prefix,
			$db->real_escape_string($this->name),
			$db->real_escape_string($this->value)
		);

		if ($db->query($query)){
			return true;
		}
		return false;
	}

	public function updateSetting() {
		global $cfg, $db;

		$query = sprintf(
			"UPDATE %s_1_email_queue_settings SET  name = '%s', value = '%s' WHERE id = %s",
			$cfg->db->prefix,
			$db->real_escape_string($this->name),
			$db->real_escape_string($this->value),
			$this->id
		);
		return $db->query($query);
	}

	public function deleteSetting () {
		global $cfg, $db, $authData;

		$gp = new c1_emailqueue();
		$gp->setId($this->id);
		$gp = $gp->returnOneSetting();

		$trash = new trash();
		$trash->setCode(json_encode($gp));
		$trash->setDate();
		$trash->setModule($cfg->mdl->folder);
		$trash->setUser($authData["id"]);
		$trash->insert();

		unset($gp);

		$query = sprintf("DELETE FROM %s_1_email_queue_settings WHERE id = %s", $cfg->db->prefix, $this->id);

		return $db->query($query);
	}

	public static function getSettings () {
		global $cfg, $db;

		$query = sprintf("SELECT * FROM %s_1_email_queue_settings WHERE true",
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

	public static function returnAllSettings () {
		global $cfg, $db;

		$query = sprintf("SELECT * FROM %s_1_email_queue_settings WHERE true", $cfg->db->prefix);
		$source = $db->query($query);

		while ($data = $source->fetch_object()) {
			if (!isset($toReturn)) {
				$toReturn = [];
			}

			array_push($toReturn, $data);
		}

		return (isset($toReturn)) ? $toReturn : false;
	}

	public static function sendEmail ($settings = [], $to, $cc, $bcc, $replyTo = FALSE, $subject, $message, $attach = []) {
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

		$mail->SMTPOptions = array(
			'ssl' => array(
				'verify_peer' => false,
				'verify_peer_name' => false,
				'allow_self_signed' => true
			)
		);

		$mail->SetFrom($settings["server_username"], $settings["server_email_name"]); // ADD SENDER
		$mail->Subject = $subject; // ADD SUBJECT
		$mail->AddAddress($to); // ADD DESTINATARY

		// ADD CC EMAIL LIST
		foreach($cc as $index => $email) {
			$mail->AddCC($email);
		}

		// ADD BCC EMAIL LIST
		foreach($bcc as $index => $email) {
			$mail->AddBCC($email);
		}

		if ($replyTo != FALSE) {
			$mail->AddReplyTo($replyTo);
		} else {
			$mail->AddReplyTo($settings["server_email"]);
		}

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

	public function returnOneSetting () {
		global $cfg, $db;

		$toReturn = [];

		$query = sprintf(
			"SELECT * FROM %s_1_email_queue_settings WHERE id = %s LIMIT %s",
			$cfg->db->prefix, $this->id, 1
		);
		$source = $db->query($query);

		if ($source->num_rows > 0) {
			return $source->fetch_object();
		}

		return false;
	}

	public function returnSettingByName () {
		global $cfg, $db;

		$query = sprintf(
			"SELECT * FROM %s_1_email_queue_settings WHERE name = '%s' LIMIT %s",
			$cfg->db->prefix, $this->name, 1
		);
		$source = $db->query($query);

		if ($source->num_rows > 0) {
			return $source->fetch_object();
		}

		return false;
	}
}
