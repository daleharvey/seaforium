#!/usr/bin/python
# -*- coding: utf-8 -*-

from subprocess import call

import glob
import MySQLdb as mdb
import sys
import os


def reset_database(host, database, username, password):

    con = mdb.connect(host, username, password, database);

    cur = con.cursor()
    cur.execute('DROP DATABASE %s' % database)
    cur.execute('CREATE DATABASE %s;' % database)

    schema_dir = os.environ['yay_root'] + "/db_schema"
    cmd = "/usr/local/mysql/bin/mysql -u'%s' -p'%s' %s < %s/%s"

    schemas = os.listdir(schema_dir)
    for fname in schemas:
        if fname.endswith('.sql'):
            call(cmd % (username, password, database, schema_dir, fname), shell=True)

    print "Import Complete!"
    con.close()
