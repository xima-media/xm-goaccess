create table tx_ximatwitterclient_domain_model_account (
	username      varchar(255) not null default '',
	image         int(11) unsigned default '0' not null,
	fetch_type    varchar(255) not null default '',
	fetch_options varchar(255) not null default '',
	max_results   int(11) unsigned default '0' not null,
);

create table tx_ximatwitterclient_domain_model_tweet (
	text          text,
	id            varchar(255) not null default '',
	author_id     varchar(255) not null default '',
	username      varchar(255) not null default '',
	name          varchar(255) not null default '',
	profile_image int(11) unsigned default '0' not null,
	attachments   int(11) unsigned default '0' not null,
);
