#!/usr/bin/env python
import matplotlib as mpl
mpl.use('Agg')
from pylab import *
import MySQLdb as mdb
import os.path as osp
import sqlite3
import re

keys = [('id', 'INTEGER PRIMARY KEY'), 
        ('hostid', 'INTEGER'), 
        ('userid', 'INTEGER'), 
        ('teamid', 'INTEGER'), 
        ('workunitid', 'INTEGER'), 
        ('name', 'TEXT'), 
        ('mod_time', 'TEXT'), 
        ('received_time', 'TEXT'), 
        ('validate_state', 'INTEGER'), 
        ('outcome', 'INTEGER')]

#get all current lsplitsims results
con = mdb.connect('mysql', 'root', '', 'cosmohome')
cur=con.cursor()
cur.execute("select %s from result where appid=5 and server_state=5"%','.join([k for k,_ in keys]))
results=cur.fetchall()
con.close()

#add any new ones the sqlite db
con = sqlite3.connect('../archives/lsplitsims.db')
con.execute("create table if not exists result (%s)"%','.join(['%s %s'%k for k in keys]))
con.executemany("""insert or ignore into result values (%s)"""%','.join('?'*len(keys)),results)

#compute top teams/users
def calctop(k):
    con.execute("drop table if exists top_{type}_planck".format(type=k))
    con.execute("create table top_{type}_planck (id INTEGER PRIMARY KEY, planck_credit INTEGER)".format(type=k))
    con.execute("""
    insert into top_{type}_planck
    select distinct({type}id), count(*)*50 as planck_credit from result 
    where outcome=1 and {type}id!=0 and datetime(received_time,'unixepoch')>=Datetime('2017-05-05 00:00:00') and datetime(received_time,'unixepoch')<=Datetime('2017-05-19 00:00:00')
    group by {type}id
    """.format(type=k))
for k in ['team','user']: calctop(k)

#finish
con.commit()
con.close()
