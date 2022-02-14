CREATE TABLE mod_common_default (
    id                      int(10) unsigned NOT NULL AUTO_INCREMENT,
    category_id             int(10) unsigned,
    nome                    varchar(255) NOT NULL DEFAULT '',

    created_uid             mediumint(8) unsigned NOT NULL,
    modified_uid            mediumint(8) unsigned NOT NULL,
    created                 DATETIME,
    modified                DATETIME,
    PRIMARY KEY (id),
    KEY nome (nome)
) ENGINE=MyISAM;
