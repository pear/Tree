# phpMyAdmin MySQL-Dump
# version 2.2.4
# http://phpwizard.net/phpMyAdmin/
# http://phpmyadmin.sourceforge.net/ (download page)
#
# Host: ludwig
# Generation Time: Apr 14, 2002 at 06:57 PM
# Server version: 3.23.37
# PHP Version: 4.1.0
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
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table `nestedTree`
#

INSERT INTO nestedTree VALUES (2, 'a1', 2, 5, 1);
INSERT INTO nestedTree VALUES (1, 'root', 1, 18, 0);
INSERT INTO nestedTree VALUES (3, 'b1', 3, 4, 2);
INSERT INTO nestedTree VALUES (4, 'a2', 6, 13, 1);
INSERT INTO nestedTree VALUES (5, 'b2', 7, 8, 4);
INSERT INTO nestedTree VALUES (6, 'b3', 9, 12, 4);
INSERT INTO nestedTree VALUES (7, 'c1', 10, 11, 6);
INSERT INTO nestedTree VALUES (8, 'a3', 14, 17, 1);
INSERT INTO nestedTree VALUES (9, 'b4', 15, 16, 8);

