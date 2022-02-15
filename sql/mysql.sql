CREATE TABLE mod_common_test (
    id                      int(10) unsigned NOT NULL AUTO_INCREMENT,
    weight                  int(10) unsigned NULL DEFAULT 0,
    category_id             int(10) unsigned,
    created_uid             mediumint(8) unsigned NOT NULL,
    modified_uid            mediumint(8) unsigned NOT NULL,
    created                 DATETIME,
    modified                DATETIME,

    name                    varchar(255) NOT NULL DEFAULT '',

    PRIMARY KEY (id),
    KEY name (name)
) ENGINE=MyISAM;

CREATE TABLE mod_common_testcategory (
    id                      int(10) unsigned NOT NULL AUTO_INCREMENT,
    weight                  int(10) unsigned NULL DEFAULT 0,
    category_id             int(10) unsigned,
    created_uid             mediumint(8) unsigned NOT NULL,
    modified_uid            mediumint(8) unsigned NOT NULL,
    created                 DATETIME,
    modified                DATETIME,

    name                    varchar(255) NOT NULL DEFAULT '',

    PRIMARY KEY (id),
    KEY name (name)
) ENGINE=MyISAM;
