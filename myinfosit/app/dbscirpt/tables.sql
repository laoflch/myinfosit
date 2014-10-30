--用户表 

drop table `users`;

CREATE TABLE `users` (
  `uid` int(8) unsigned NOT NULL auto_increment,  
  `username` varchar(32) NOT NULL,  
  `password` char(34) NOT NULL default'',
  `email` varchar(125) NOT NULL,
  `source` varchar(8) NOT NULL default'local',
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `users_info` (
  `uid` int(8) unsigned NOT NULL auto_increment,
 
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `users_relation` (
  
  `uid` int(8) unsigned NOT NULL,
  `password` char(34) NOT NULL,
  `email` varchar(125) NOT NULL,
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

drop table `sina_users_keys`;

CREATE TABLE `sina_users_keys` (
  
  `uid` int(8) unsigned NOT NULL,
  `source_uid` int(8) unsigned NOT NULL,
  `access_token` varchar(32) NOT NULL,
  `expires_in` varchar(32) NOT NULL,
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `qq_users_key` (
  
  `uid` int(8) unsigned NOT NULL,
  `access_toke` varchar(32) NOT NULL,
  `open_id` varchar(32) NOT NULL,
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

CREATE TABLE `douban_users_key` (
  
  `uid` int(8) unsigned NOT NULL,
  `access_toke` varchar(32) NOT NULL,
  `open_id` varchar(32) NOT NULL,
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

drop table `authkeys`;

CREATE TABLE `authkeys` (
  `source_id` smallint(6) unsigned NOT NULL auto_increment,  
  `source_name` varchar(16) NOT NULL, 
  `akey` char(32) NOT NULL,
  `skey` varchar(32) NOT NULL,
  
  PRIMARY KEY  (`source_id`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_friends`;
CREATE TABLE `weixin_friends` (
  `fake_id` varchar(16) NOT NULL,
  `customer_id` varchar(16) NOT NULL,
  `nick_name` varchar(64) NOT NULL default '',
  `remark_name` varchar(64) NOT NULL default '',
  `group_id` char(1) NOT NULL default '',
  PRIMARY KEY  (`fake_id`,`customer_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_friend_infos`;
CREATE TABLE `weixin_friend_infos` (
  `fake_id` varchar(16) NOT NULL,
  `customer_id` varchar(16) NOT NULL,
  `nick_name` varchar(64) NOT NULL default '',
  `re_mark_name` varchar(64) NOT NULL default '',
  `user_name` varchar(64) NOT NULL default '',
  `singnature` varchar(128) NOT NULL default '',
  `country` varchar(16) NOT NULL default '',
  `province` varchar(8) NOT NULL default '',
  `city` varchar(32) NOT NULL default '',
  `sex` char(1) NOT NULL default '',
  `group_id` char(1) NOT NULL default '',
  PRIMARY KEY  (`fake_id`,`customer_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_friend_maps`;
CREATE TABLE `weixin_friend_maps` (
  `uuid` varchar(32) NOT NULL,
  `fake_id` varchar(16) NOT NULL,
  `customer_id` int(8) NOT NULL,
  `original_user_id` varchar(64) NOT NULL,
  PRIMARY KEY  (`uuid`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_open_accounts`;
CREATE TABLE `weixin_open_accounts` (
  `customer_id`int(8) NOT NULL auto_increment,
  `customer_name` varchar(32) NOT NULL DEFAULT '',
  `sit_login_name` varchar(32) NOT NULL DEFAULT '',
  `uid` int(8) NOT NULL,
  `password` varchar(64) NOT NULL DEFAULT '',
  `open_account_id` varchar(64) NOT NULL DEFAULT '',  
  `original_user_id` varchar(32) NOT NULL DEFAULT '',
  `original_user_name` varchar(32) NOT NULL DEFAULT '',
  `rule_group_id` int(8) NOT NULL,
  PRIMARY KEY  (`customer_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_original_users`;
CREATE TABLE `weixin_original_users` (
  
  `open_account_id` varchar(64) NOT NULL,
  `rule_group_id` int(8) NOT NULL,
  PRIMARY KEY  (`original_user_name`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_rule_group`;
CREATE TABLE `weixin_rule_group` (
  `rule_group_id` int(8) NOT NULL auto_increment,
  
  PRIMARY KEY  (`rule_group_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_rules`;
CREATE TABLE `weixin_rules` (
  `rule_id` int(8) NOT NULL auto_increment,
  `customer_id` int(8) NOT NULL ,
  `rule_name` varchar(32) NOT NULL,
  `rule_type` char(2) NOT NULL,
  `receive_msg_type` char(2) NOT NULL,
  `rule_adapter_id` char(2) NOT NULL DEFAULT "",
  `rule_group_id` int(8) NOT NULL DEFAULT 0,
  `rule_order_no` int(8) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`rule_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;



drop table `weixin_rule_adapters`;
CREATE TABLE `weixin_rule_adapters` (
  `rule_adapter_id` char(2) NOT NULL,
  `rule_adapter_name` varchar(32) NOT NULL DEFAULT "",
  `rule_name` varchar(32) NOT NULL DEFAULT "",
  PRIMARY KEY  (`rule_adapter_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 ;


drop table `weixin_rule_keys`;
CREATE TABLE `weixin_rule_keys` (
  `rule_id` int(8) NOT NULL,
  `rule_key` varchar(255) NOT NULL default"",
  `content_id` int(8) NOT NULL default 0,
  `content_type_code` char(2) NOT NULL,
  PRIMARY KEY  (`rule_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_rule_events`;
CREATE TABLE `weixin_rule_events` (
  `rule_id` int(8) NOT NULL,
  `event` varchar(16) NOT NULL default"",
  `event_key` varchar(16) NOT NULL default"",
  `content_id` int(8) NOT NULL default 0,
  `content_type_code` char(2) NOT NULL,
  PRIMARY KEY  (`rule_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_content_types`;
CREATE TABLE `weixin_content_types` (  
  `content_type_code` char(2) NOT NULL,
  `content_type_name` varchar(16) NOT NULL DEFAULT '',
  `content_type_title` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY  (`content_type_code`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_receive_msg_types`;
CREATE TABLE `weixin_receive_msg_types` (  
  `msg_type_code` char(2) NOT NULL,
  `msg_type_name` varchar(16) NOT NULL DEFAULT '',
  `msg_type_title` varchar(16) NOT NULL DEFAULT '',
  PRIMARY KEY  (`msg_type_code`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_rule_content_texts`;
CREATE TABLE `weixin_rule_content_texts` (
  `content_id` int(8) NOT NULL auto_increment,
  `content_name` varchar(32) NOT NULL DEFAULT '',
  `text_message` varchar(1024),
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_rule_content_mixes`;
CREATE TABLE `weixin_rule_content_mixes` (
  `content_id` int(8) NOT NULL auto_increment,
  `content_name` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(128) NOT NULL DEFAULT '',
  `abstract` varchar(256) NOT NULL DEFAULT '',
  `front_picture` varchar(128) NOT NULL DEFAULT '',
  `body` varchar(2048) NOT NULL DEFAULT '',
  `orginal_url` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

CREATE TABLE `weixin_rule_content_reply_mixes` (
  `content_id` int(8) NOT NULL auto_increment,
  `content_name` varchar(32) NOT NULL DEFAULT '',
  `article_count` int(8) NOT NULL DEFAULT 0,
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_rule_content_reply_mix_items`;
CREATE TABLE `weixin_rule_content_reply_mix_items` (
  `content_id` int(8) NOT NULL,
  `item_no` int(8)  NOT NULL DEFAULT 0,
  `title` varchar(128) NOT NULL DEFAULT '',
  `description` varchar(256) NOT NULL DEFAULT '',
  `pic_url` varchar(128) NOT NULL DEFAULT '',
  `url` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY  (`content_id`,`item_no`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_rule_content_orders`;
CREATE TABLE `weixin_rule_content_orders` (
  `content_id` int(8) NOT NULL auto_increment,
  `plugin` varchar(32) NOT NULL DEFAULT '',
  `adapter_name` varchar(32) NOT NULL DEFAULT '',  
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_rule_content_pictures`;
CREATE TABLE `weixin_rule_content_pictures` (
  `content_id` int(8) NOT NULL auto_increment,
  `content_name` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(128) NOT NULL DEFAULT '',
  `abstract` varchar(256) NOT NULL DEFAULT '',
  `front_picture` varchar(128) NOT NULL DEFAULT '',
  `body` varchar(2048) NOT NULL DEFAULT '',
  `orginal_url` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_rule_content_videos`;
CREATE TABLE `weixin_rule_content_videos` (
  `content_id` int(8) NOT NULL auto_increment,
  `content_name` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(128) NOT NULL DEFAULT '',
  `abstract` varchar(256) NOT NULL DEFAULT '',
  `front_picture` varchar(128) NOT NULL DEFAULT '',
  `body` varchar(2048) NOT NULL DEFAULT '',
  `orginal_url` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_rule_content_audios`;
CREATE TABLE `weixin_rule_content_audios` (
  `content_id` int(8) NOT NULL auto_increment,
  `content_name` varchar(32) NOT NULL DEFAULT '',
  `title` varchar(128) NOT NULL DEFAULT '',
  `abstract` varchar(256) NOT NULL DEFAULT '',
  `front_picture` varchar(128) NOT NULL DEFAULT '',
  `body` varchar(2048) NOT NULL DEFAULT '',
  `orginal_url` varchar(128) NOT NULL DEFAULT '',
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_messages`;
CREATE TABLE `weixin_messages` (
   `message_id` int(8) NOT NULL auto_increment,
   `msg_type` varchar(8) NOT NULL DEFAULT '',
   `to_user_name` varchar(32) NOT NULL DEFAULT '',
   `from_user_name` varchar(32) NOT NULL DEFAULT '',
   `create_time` int(8) NOT NULL default 0,
   `from_msg_id` bigint(32) NOT NULL default -1,
   PRIMARY KEY  (`message_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `weixin_message_texts`;
CREATE TABLE `weixin_message_texts` (
   `message_id` int(8) NOT NULL ,
   `content` varchar(2048) NOT NULL DEFAULT '',
   PRIMARY KEY  (`message_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_message_images`;
CREATE TABLE `weixin_message_images` (
   `message_id` int(8) NOT NULL ,
   `pic_url` varchar(2048) NOT NULL DEFAULT '',
   PRIMARY KEY  (`message_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_message_locations`;
CREATE TABLE `weixin_message_locations` (
   `message_id` int(8) NOT NULL ,
   `location_x` decimal(12,6)NOT NULL DEFAULT 0.000000,
   `location_y` decimal(12,6)NOT NULL DEFAULT 0.000000,
   `scale` int(4)NOT NULL DEFAULT 0,
   `Label` varchar(128) NOT NULL DEFAULT '',
   PRIMARY KEY  (`message_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_message_links`;
CREATE TABLE `weixin_message_links` (
   `message_id` int(8) NOT NULL ,
   `title` varchar(32) NOT NULL DEFAULT '',   
   `description` varchar(255) NOT NULL DEFAULT '', 
   `url` varchar(255) NOT NULL DEFAULT '',
   PRIMARY KEY  (`message_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_message_events`;
CREATE TABLE `weixin_message_events` (
   `message_id` int(8) NOT NULL ,
   `event` varchar(16) NOT NULL DEFAULT '',   
   `event_key` varchar(8) NOT NULL DEFAULT '',
   PRIMARY KEY  (`message_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;


insert into `authkeys` (source_name,akey,skey) values ('sina_weiboo','1799167609','50944cd217d67de330696790dfa037f8');
insert into `authkeys` (source_name,akey,skey) values ('qq','100440612','079efe2717e4ec4c8cc3052a13587907');
insert into `authkeys` (source_name,akey,skey) values ('Douban','08da1c08e1b415471a8e541aa51c3fd5','cca610d1c502c074');
insert into `weixin_original_users` (original_user_name,open_account_id) values ('gh_b1cef7dbee50','laoflch@163.com');
insert into `weixin_rules` (`customer_id`,`rule_name`,`rule_type`,`rule_adapter_id`) values (1,'测试1','01','01');
insert into `weixin_rule_adapters` (`rule_adapter_id`,`rule_adapter_name`,`rule_name`) values ('01','RuleKeyAdapter','关键字匹配');
insert into `weixin_rule_adapters` (`rule_adapter_id`,`rule_adapter_name`,`rule_name`) values ('02','RuleKeyAdapter','关键字匹配2');
insert into `weixin_rule_keys` (`rule_id`,`rule_key`,`content_id`,`content_type_code`) values (1,'hello!','1','01');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试1','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试2','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试3','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试4','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试5','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试6','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试7','hello! world');
insert into `weixin_rule_content_texts` (`content_name`,`text_message`) values ('测试8','hello! world');
insert into `weixin_rule_content_mixes` (`content_name`,`title`,`abstract`,`front_picture`,`body`,`orginal_url`) values ('测试1','测试1','测试1','http://werwdfd.com','<adfsdf>wrwer<p>','http://w2werdc');
insert into `weixin_rule_content_reply_mixes` (`content_name`,`article_count`) values ('测试1',2);
insert into `weixin_rule_content_reply_mix_items` (`content_id`,`item_no`,`title`,`description`,`pic_url`,`url`) values (1,1,'测试1','测试1','http://res.wx.qq.com/mpres/htmledition/images/new/logo.png','http://mp.weixin.qq.com/');
insert into `weixin_rule_content_reply_mix_items` (`content_id`,`item_no`,`title`,`description`,`pic_url`,`url`) values (1,2,'测试2','测试2','http://res.wx.qq.com/mpres/htmledition/images/new/logo.png','http://mp.weixin.qq.com/');
insert into `weixin_rule_content_pictures` (`content_name`,`title`,`abstract`,`front_picture`,`body`,`orginal_url`) values ('测试1','测试1','测试1','http://werwdfd.com','<adfsdf>wrwer<p>','http://w2werdc');
insert into `weixin_rule_content_videos` (`content_name`,`title`,`abstract`,`front_picture`,`body`,`orginal_url`) values ('测试1-video','测试1-video','测试1','http://werwdfd.com','<adfsdf>wrwer<p>','http://w2werdc');
insert into `weixin_rule_content_audios` (`content_name`,`title`,`abstract`,`front_picture`,`body`,`orginal_url`) values ('测试1-audio','测试1-audio','测试1','http://werwdfd.com','<adfsdf>wrwer<p>','http://w2werdc');

insert into `weixin_content_types` (`content_type_code`,`content_type_name`,`content_type_title`) values ('01','text','文本');
insert into `weixin_content_types` (`content_type_code`,`content_type_name`,`content_type_title`) values ('02','ReplyMix','回复图文');
insert into `weixin_content_types` (`content_type_code`,`content_type_name`,`content_type_title`) values ('03','Order','预约处理');

insert into `weixin_receive_msg_types` (`msg_type_code`,`msg_type_name`,`msg_type_title`) values ('01','text','文本');
insert into `weixin_receive_msg_types` (`msg_type_code`,`msg_type_name`,`msg_type_title`) values ('02','ReplyMix','事件');

insert into `weixin_rule_events` (`rule_id`,`event`,`event_key`,`content_id`,`content_type_code`) values (1,'subscribe','','1','01');


insert into `weixin_open_accounts` (
`customer_name` ,
`sit_login_name` ,
`password` ,
`open_account_id` ,  
`original_user_id` ,
`original_user_name`,
`rule_group_id`)
values ('微数据媒体','laoflch@163.com','arsenal','678038100','gh_b1cef7dbee50','微数据媒体',0

)

insert into `weixin_messages`(
`msg_type` ,
   `to_user_name` ,
   `from_user_name` ,
   `create_time` ,
   `from_msg_id` 
) values (
'text',
'gh_b1cef7dbee50',
'gh_b1cef7dbee50',
1234567,
1299455
);

insert into `weixin_message_texts`(
`message_id` ,
   `content` 
) values (
1,
'gh_b1cef7dbee50'

);

drop table `minsheng_orders`;
CREATE TABLE `minsheng_orders` (
  `order_id` int(8) NOT NULL auto_increment,
  `from_fake_id` varchar(32)  NOT NULL DEFAULT '',
  `activity_id` int(8) NOT NULL DEFAULT 0,
  `order_code` char(6) NOT NULL DEFAULT '',
  PRIMARY KEY  (`order_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `minsheng_activities`;
CREATE TABLE `minsheng_activities` (
  `activity_id` int(8) NOT NULL auto_increment,
  `activity_name` varchar(32) default '',
  PRIMARY KEY  (`activity_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `minsheng_rule_content_orders`;
CREATE TABLE `minsheng_rule_content_orders` (
  `content_id` int(8) NOT NULL,
  `key_word` varchar(255) NOT NULL default '',
  `activity_id` int(8) NOT NULL,
  `order_no` int(8) NOT NULL default 0,
  PRIMARY KEY  (`content_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `weixin_user_infos`;
CREATE TABLE `weixin_user_infos` (
  `uid` int(8) unsigned NOT NULL,
  `default_customer` int(8) NOT NULL,  
 
  PRIMARY KEY  (`uid`)
  
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;

drop table `mahua_cookie_key_source_codes`;
CREATE TABLE `mahua_cookie_key_source_codes` (
  `cookie_key` varchar(64) NOT NULL,
  `source_code` varchar(64) NOT NULL default '',
  PRIMARY KEY  (`cookie_key`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

drop table `mahua_orders`;
CREATE TABLE `mahua_orders` (
  `order_id` int(8) NOT NULL auto_increment,
  `phone_no` varchar(16) NOT NULL default '',
  `single_price` decimal(18,2) NOT NULL default 0.00,
  `count` int(8) NOT NULL default 0,
  `total_price` decimal(18,2) NOT NULL default 0.00,
  `showtime` DATETIME, 
  PRIMARY KEY  (`order_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;

drop table `mahua_order_notifies`;
CREATE TABLE `mahua_order_notifies` (
  `order_id` int(8) NOT NULL,
  `trade_no` varchar(32) NOT NULL default '',
  `result` varchar(8) NOT NULL default '',
  PRIMARY KEY  (`order_id`)
)ENGINE=InnoDB DEFAULT CHARSET=utf8

INSERT INTO `myinfosit_db`.`mahua_cookie_key_source_codes` (`cookie_key`, `source_code`) VALUES ('DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi', '123456');
