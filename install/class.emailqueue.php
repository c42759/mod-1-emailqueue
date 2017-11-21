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
	protected $status = false;
	protected $date;
	protected $date_update;

	function __construct() {}

	function setId($i) {
		$this->id = $i;
	}

	function setSubject($s) {
		$this->subject = $s;
	}

	function setContent($c) {
		$this->content = $c;
	}

	function setAttachments($a) {
		$this->attachments = json_encode($a);
	}

	function setStatus($s) {
		$this->status = $s;
	}

	public function setDate($d = null) {
		$this->date = ($d !== null) ? $d : date("Y-m-d H:i:s", time());
	}

	public function setDateUpdate($d = null) {
		$this->date_update = ($d !== null) ? $d : date("Y-m-d H:i:s", time());
	}

	function insert() {
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

	function update() {
		global $cfg, $db;

		$query = sprintf();
	}

	function delete() {
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

	function returnOneEntry() {
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

	function returnAllEntries() {
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
}
