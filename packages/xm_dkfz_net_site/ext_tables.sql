create table pages (
	tx_xmdkfznetsite_color    varchar(64)  not null default '',
	tx_xmdkfznetsite_contacts varchar(255) not null default '',
);

create table tt_content (
	tx_xmdkfznetsite_accordion_title varchar(255) not null default '',
	tx_xmdkfznetsite_accordion_group varchar(255) not null default '',
	tt_content_items                 int(11) unsigned default '0' not null,
	color                            varchar(64)  not null default '',
	tx_xmdkfznetsite_tabs_tab1       varchar(255) not null default '',
	tx_xmdkfznetsite_tabs_tab2       varchar(255) not null default '',
	tx_xmdkfznetsite_tabs_tab3       varchar(255) not null default '',
	tx_xmdkfznetsite_author					 varchar(255) not null default '',
	tx_xmdkfznetsite_function				 varchar(255) not null default '',
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

create table tx_news_domain_model_news (
	tx_xmdkfznetsite_color varchar(64) not null default '',
);

create table fe_users (
	location        varchar(255) default '' not null,
	member_since    int(11) unsigned default '0' not null,
	birthday        int(11) unsigned default '0' not null,
	dkfz_id         int(11) unsigned default '0' not null,
	ad_account_name varchar(255) default '' not null,
	contacts        int(11) unsigned default '0' not null,
	dkfz_hash       varchar(255) default '' not null,
	gender          int(11) unsigned default '0' not null,
);

create table fe_groups (
	dkfz_number varchar(255) default '' not null,
	title       varchar(255) default '' not null,
);

create table be_groups (
	dkfz_number varchar(255) default '' not null,
	title       varchar(255) default '' not null,
);

create table tx_xmdkfznetsite_domain_model_contact (
	uid            int(11) not null auto_increment,
	pid            int(11) default '0' not null,
	sorting        int(11) unsigned default '0' not null,
	tstamp         int(11) unsigned default '0' not null,
	crdate         int(11) unsigned default '0' not null,
	foreign_uid    int(11) default '0' not null,
	foreign_table  varchar(255) default '' not null,

	record_type    varchar(255) default '' not null,
	fe_group       int(11) default '0' not null,
	function       varchar(255) default '' not null,
	primary_number tinyint(4) unsigned default '0' not null,
	room           varchar(255)            not null default '',
	number         varchar(255) default '' not null,

	primary key (uid),
	KEY            parent (pid),
);

create table tx_xmdkfznetsite_domain_model_place (
	uid              int(11) not null auto_increment,
	pid              int(11) default '0' not null,
	tstamp           int(11) unsigned default 0 not null,
	crdate           int(11) unsigned default 0 not null,
	deleted          tinyint(4) unsigned default 0 not null,
	hidden           tinyint(4) unsigned default 0 not null,
	sys_language_uid int(11) default 0 not null,
	l10n_parent      int(11) unsigned default '0' not null,

	dkfz_id          int(11) unsigned default '0' not null,
	dkfz_hash        varchar(255) default '' not null,
	name             varchar(255) default '' not null,
	function         varchar(255) default '' not null,
	room             varchar(255) default '' not null,
	mail             varchar(255) default '' not null,
	fe_group         int(11) default 0 not null,
	contacts         int(11) default 0 not null,

	primary key (uid),
	KEY              parent (pid),
);
