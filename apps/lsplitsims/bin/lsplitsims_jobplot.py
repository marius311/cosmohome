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
    where outcome=1 and {type}id!=0 group by {type}id
    """.format(type=k))
for k in ['team','user']: calctop(k)

#get completed job names
names = [n for n, in con.execute('select name from result')]

#finish
con.commit()
con.close()


#scan job names to get seeds/lslices of completed jobs
rc=re.compile('planck_param_sims_([0-9]+)_([0-9]+)_([0-9]+)_.*')
dat=[map(int,rc.match(n).groups()) for n in names]
jobs={}
for d in dat:
    j=jobs[d[2]]=jobs.get(d[2],set())
    j.add(tuple(d[:2]))
jdat=array(sorted([len(v) for k,v in jobs.items() if k>20000])[::-1])

#make plot
xkcd()
matshow([jdat],aspect=1000,cmap='Blues')
# plot([sum(jdat==jdat.max())]*2,[-0.5,0.5],'k:',lw=2)
# ylim(-0.5,0.5)
xlim(0,10000)
gca().set_yticks([])
gca().xaxis.set_ticks_position('bottom')
xlabel('Number of Simulations Completed',size=16)
gcf().set_size_inches(15,15)
savefig(osp.join(osp.dirname(__file__),'../html/user/img/lsplitsims_jobplot.png'),bbox_inches='tight',dpi=74)
