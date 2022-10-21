#
# Table structure for table 'tx_kesearchpremium_synonym'
#
CREATE TABLE tx_kesearchpremium_synonym (
    uid int(11) NOT NULL auto_increment,
    pid int(11) DEFAULT '0' NOT NULL,
    tstamp int(11) DEFAULT '0' NOT NULL,
    crdate int(11) DEFAULT '0' NOT NULL,
    cruser_id int(11) DEFAULT '0' NOT NULL,
    deleted tinyint(4) DEFAULT '0' NOT NULL,
    hidden tinyint(4) DEFAULT '0' NOT NULL,
    searchphrase tinytext,
    synonyms text,

    PRIMARY KEY (uid),
    KEY parent (pid),
    FULLTEXT INDEX synonyms (synonyms)
);

#
# Add fields for distance search to index table
#
CREATE TABLE tx_kesearch_index (
    lat tinytext,
    lon tinytext,
    externalurl tinytext,
    lastremotetransfer int(11) DEFAULT '0' NOT NULL,
    boostkeywords text,
    customranking tinytext,

	FULLTEXT INDEX boostkeywords (boostkeywords),
	FULLTEXT INDEX titlecontentboostkeywords (title,content,boostkeywords),
    FULLTEXT INDEX titlecontenhiddencontentboostkeywords (title,content,hidden_content,boostkeywords),
);

#
# Add fields for distance search to indexerconfig table
#
CREATE TABLE tx_kesearch_indexerconfig (
    remotesite tinytext,
    remoteuid tinytext,
    remoteuser tinytext,
    remotepass tinytext,
    remoteamount int(11) DEFAULT '0' NOT NULL,
    customranking tinyint(4) DEFAULT '0' NOT NULL,
);

#
# Table structure for table 'pages'
#
CREATE TABLE pages (
    tx_kesearchpremium_customranking tinyint(4) DEFAULT '0' NOT NULL,
);
