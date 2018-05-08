INSERT INTO `{c2r-prefix}_modules` (`name`, `folder`, `code`, `sort`) VALUES ("{c2r-mod-name}", "{c2r-mod-folder}", "{c2r-mod-code}", 0);

CREATE TABLE `{c2r-prefix}_email_queue_settings` (
	`id` int(11) NOT NULL,
	`name` varchar(255) NOT NULL,
	`value` text NOT NULL,
	`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{c2r-prefix}_email_queue_settings`
	ADD PRIMARY KEY (`id`);

ALTER TABLE `{c2r-prefix}_email_queue_settings`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

INSERT INTO `{c2r-prefix}_email_queue_settings` (`name`, `value`) VALUES
('delay', '300'),
('error_times_limit', '5'),
('server_email_name', 'One:Shift'),
('server_email', 'hello@one-shift.com'),
('server_smtp', 'mail.yourdomain.here'),
('server_username', 'your@email.here'),
('server_password', 'yourpassword'),
('server_port', 'connection-port eg.: 25'),
('server_secure', 'connection-type eg.: plain'),
('server_debug', '1');

CREATE TABLE `{c2r-prefix}_email_queue` (
	`id` int(11) NOT NULL,
	`from` varchar(255) NOT NULL,
	`to` varchar(255) NOT NULL,
	`cc` varchar(255) NOT NULL,
	`bcc` varchar(255) NOT NULL,
	`subject` varchar(255) NOT NULL,
	`content` text NOT NULL,
	`attachments` text NOT NULL,
	`status` tinyint(1) NOT NULL DEFAULT '0',
	`priority` int(11) NOT NULL,
	`date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`date_update` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `{c2r-prefix}_email_queue`
	ADD PRIMARY KEY (`id`);

ALTER TABLE `{c2r-prefix}_email_queue`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
