DELETE FROM `{c2r-prefix}_modules` WHERE `folder` = '{c2r-mod-folder}';

DROP TABLE IF EXISTS `{c2r-prefix}_1_email_queue_setting`;
DROP TABLE IF EXISTS `{c2r-prefix}_1_email_queue_settings`;
