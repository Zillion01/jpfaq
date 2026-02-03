#
# Table structure for table 'tx_jpfaq_domain_model_question'
#
CREATE TABLE tx_jpfaq_domain_model_question (
	question varchar(255) DEFAULT '' NOT NULL,
	answer text NOT NULL,
	additional_content_answer int(11) unsigned DEFAULT '0' NOT NULL,
	categories int(11) unsigned DEFAULT '0' NOT NULL,
	questioncomment int(11) unsigned DEFAULT '0' NOT NULL,
	helpful int(11) unsigned DEFAULT '0' NOT NULL,
	nothelpful int(11) unsigned DEFAULT '0' NOT NULL
);

# IRRE Records
CREATE TABLE tt_content (
    jpfaq int(11) unsigned DEFAULT '0' NOT NULL,

    KEY jpfaq_content (jpfaq)
);

#
# Table structure for table 'tx_jpfaq_domain_model_category'
#
CREATE TABLE tx_jpfaq_domain_model_category (
	category varchar(255) DEFAULT '' NOT NULL,
	description text NOT NULL,
	categorycomment int(11) unsigned DEFAULT '0' NOT NULL
);

#
# Table structure for table 'tx_jpfaq_question_category_mm'
#
CREATE TABLE tx_jpfaq_question_category_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);

#
# Table structure for table 'tx_jpfaq_domain_model_questioncomment'
#
CREATE TABLE tx_jpfaq_domain_model_questioncomment (
	question int(11) unsigned DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	comment text NOT NULL,
	ip varchar(255) DEFAULT 'local' NOT NULL,
	finfo varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_jpfaq_domain_model_categorycomment'
#
CREATE TABLE tx_jpfaq_domain_model_categorycomment (
	category int(11) unsigned DEFAULT '0' NOT NULL,
	name varchar(255) DEFAULT '' NOT NULL,
	email varchar(255) DEFAULT '' NOT NULL,
	comment text NOT NULL,
	ip varchar(255) DEFAULT 'local' NOT NULL,
	finfo varchar(255) DEFAULT '' NOT NULL
);

#
# Table structure for table 'tx_jpfaq_category_comment_mm'
#
CREATE TABLE tx_jpfaq_category_comment_mm (
	uid_local int(11) unsigned DEFAULT '0' NOT NULL,
	uid_foreign int(11) unsigned DEFAULT '0' NOT NULL,
	sorting int(11) unsigned DEFAULT '0' NOT NULL,
	sorting_foreign int(11) unsigned DEFAULT '0' NOT NULL,

	KEY uid_local (uid_local),
	KEY uid_foreign (uid_foreign)
);
