<?php
include dirname(__FILE__)."/../config/cfg.php";
include dirname(__FILE__)."/../config/system.php";

include dirname(__FILE__)."/../class/class.bo3.php";

if ($cfg->system->protocol == 'https') {
	$arrContextOptions = array(
		"ssl" => array(
			"verify_peer" => false,
			"verify_peer_name" => false,
		),
	);
	
	$result = file_get_contents("{$cfg->system->protocol}://{$cfg->system->domain}{$cfg->system->path}/en/mod-emailqueue-api/", false, stream_context_create($arrContextOptions));
} else {
	$result = file_get_contents("{$cfg->system->protocol}://{$cfg->system->domain}{$cfg->system->path}/en/mod-emailqueue-api/");
}
