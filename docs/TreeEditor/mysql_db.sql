# phpMyAdmin MySQL-Dump
# version 2.3.0
# http://phpwizard.net/phpMyAdmin/
# http://www.phpmyadmin.net/ (download page)
#
# Host: localhost
# Generation Time: Aug 23, 2002 at 05:46 PM
# Server version: 3.23.48
# PHP Version: 4.1.0
# Database : `test`
# --------------------------------------------------------

#
# Table structure for table `TreeEditor_MemoryNested`
#

CREATE TABLE TreeEditor_MemoryNested (
  id int(11) NOT NULL default '0',
  name varchar(255) NOT NULL default '',
  l int(11) NOT NULL default '0',
  r int(11) NOT NULL default '0',
  parent int(11) NOT NULL default '0',
  comment varchar(255) NOT NULL default '',
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table `TreeEditor_MemoryNested`
#

INSERT INTO TreeEditor_MemoryNested VALUES (8, 'Root', 1, 18, 0, '');
INSERT INTO TreeEditor_MemoryNested VALUES (9, 'PEAR', 8, 17, 8, '');
INSERT INTO TreeEditor_MemoryNested VALUES (10, 'Tree', 15, 16, 9, '');
INSERT INTO TreeEditor_MemoryNested VALUES (11, 'HTML', 13, 14, 9, '');
INSERT INTO TreeEditor_MemoryNested VALUES (12, 'Auth', 11, 12, 9, '');
INSERT INTO TreeEditor_MemoryNested VALUES (13, 'PEAR compatible', 2, 7, 8, '');
INSERT INTO TreeEditor_MemoryNested VALUES (14, 'SimpleTemplate', 5, 6, 13, '');
INSERT INTO TreeEditor_MemoryNested VALUES (15, 'Auth', 3, 4, 13, '');
INSERT INTO TreeEditor_MemoryNested VALUES (16, 'XML', 9, 10, 9, '');
# --------------------------------------------------------

#
# Table structure for table `TreeEditor_MemoryNested_seq`
#

CREATE TABLE TreeEditor_MemoryNested_seq (
  id int(10) unsigned NOT NULL auto_increment,
  PRIMARY KEY  (id)
) TYPE=MyISAM;

#
# Dumping data for table `TreeEditor_MemoryNested_seq`
#

INSERT INTO TreeEditor_MemoryNested_seq VALUES (16);

