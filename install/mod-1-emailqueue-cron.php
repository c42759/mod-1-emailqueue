<?php

include dirname(__FILE__)."/../config/cfg.php";
include dirname(__FILE__)."/../config/system.php";

include dirname(__FILE__)."/../class/class.bo3.php";

$options = ["ssl" => [ "verify_peer" => false, "verify_peer_name" => false ]];

$result = file_get_contents("{$cfg->system->protocol}://{$cfg->system->domain}{$cfg->system->path}/en/mod-emailqueue-api/", false, stream_context_create($options));
