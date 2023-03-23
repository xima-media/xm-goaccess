create table tx_xmgoaccess_domain_model_mapping (
	path        varchar(255) not null default '',
	record_type int(11) unsigned default '0' not null,
	page        int(11) unsigned default '0' not null,
	regex       tinyint(4) unsigned default '0' not null,
	title       varchar(255) not null default '',
);
