# phpMyAdmin MySQL-Dump
# version 2.2.4
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: ludwig
# Generation Time: May 03, 2002 at 01:31 AM
# Server version: 3.23.37
# PHP Version: 4.2.0
# Database : `test`
# --------------------------------------------------------

#
# Table structure for table `nestedTree`
#

CREATE TABLE nestedTree (
  id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  l int(11) NOT NULL default '0',
  r int(11) NOT NULL default '0',
  parent int(11) NOT NULL default '0',
  comment varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table `nestedTree`
#

INSERT INTO nestedTree VALUES (1, 'Root', 1, 18, 0, '');
INSERT INTO nestedTree VALUES (2, 'A1', 2, 5, 1, '');
INSERT INTO nestedTree VALUES (3, 'A2', 6, 13, 1, '');
INSERT INTO nestedTree VALUES (4, 'A3', 14, 17, 1, '');
INSERT INTO nestedTree VALUES (5, 'B1', 3, 4, 2, '');
INSERT INTO nestedTree VALUES (6, 'B2', 7, 8, 3, '');
INSERT INTO nestedTree VALUES (7, 'B3', 9, 12, 3, '');
INSERT INTO nestedTree VALUES (8, 'C1', 10, 11, 7, '');
INSERT INTO nestedTree VALUES (9, 'B4', 15, 16, 4, '');

