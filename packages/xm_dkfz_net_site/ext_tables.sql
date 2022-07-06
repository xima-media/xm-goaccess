create table pages (
	tx_xmdkfznetsite_color    varchar(64)  not null default '',
	tx_xmdkfznetsite_contacts varchar(255) not null default '',
);

create table tt_content (
	tx_xmdkfznetsite_accordion_title varchar(255) not null default '',
	tx_xmdkfznetsite_accordion_group varchar(255) not null default '',
	tt_content_items                 int(11) unsigned default '0' not null,
	color                            varchar(64)  not null default '',
);

create table tt_content_item (
	uid              int(11) not null auto_increment,
	pid              int(11) default '0' not null,
	deleted          tinyint(4) unsigned default '0' not null,
	hidden           tinyint(4) unsigned default '0' not null,
	foreign_uid      int(11) default '0' not null,
	foreign_table    varchar(255) default '' not null,
	record_type      varchar(255) default '' not null,
	sorting          int(11) unsigned default '0' not null,
	sys_language_uid int(11) default '0' not null,
	l10n_parent      int(11) unsigned default '0' not null,
	tstamp           int(11) unsigned default '0' not null,
	crdate           int(11) unsigned default '0' not null,
	cruser_id        int(11) unsigned default '0' not null,

	title            varchar(255) default '' not null,
	link             varchar(255) default '' not null,
	text             text,
	image            int(11) unsigned default '0' not null,
	color            varchar(64)             not null default '',
	overrides        int(11) unsigned default '0' not null,
	tt_content_items int(11) unsigned default '0' not null,

	primary key (uid),
	KEY              parent (pid),
	KEY language (l10n_parent,sys_language_uid)
);
