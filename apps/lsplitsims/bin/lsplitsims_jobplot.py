#!/usr/bin/env python
import matplotlib as mpl
mpl.use('Agg')
from pylab import *
import MySQLdb as mdb
import os.path as osp
import sqlite3
import re

#get all current lsplitsims results
con = mdb.connect('mysql', 'root', '', 'cosmohome')
cur=con.cursor()
cur.execute("""
select id, hostid, userid, teamid, name, mod_time, validate_state, outcome from result
where appid=5 and server_state=5;
""")
results=cur.fetchall()
con.close()

#add any new ones the sqlite db
con = sqlite3.connect('../archives/lsplitsims.db')
con.execute("""
create table if not exists result
(id INT PRIMARY KEY, hostid INT, userid INT, teamid INT, name TEXT, mod_time INT, validate_state INT, outcome INT)
""")
con.executemany("""insert or ignore into result values (%s)"""%','.join('?'*8),results)
con.commit()
names = [n for n, in con.execute('select name from result')]
con.close()

#scan job names to get seeds/lslices of completed jobs
rc=re.compile('planck_param_sims_([0-9]+)_([0-9]+)_([0-9]+)_.*')
dat=[map(int,rc.match(n).groups()) for n in names]
jobs={}
for d in dat:
    j=jobs[d[2]]=jobs.get(d[2],set())
    j.add(tuple(d[:2]))

#make plot
xkcd()
matshow([sorted([len(v) for k,v in jobs.items() if k>20000])[::-1]],aspect=1000,cmap='Blues')
xlim(0,10000)
gca().set_yticks([])
gca().xaxis.set_ticks_position('bottom')
xlabel('Number of Simulations Completed',size=16)
gcf().set_size_inches(15,15)
savefig(osp.join(osp.dirname(__file__),'../html/user/img/lsplitsims_jobplot_new.png'),bbox_inches='tight',dpi=74)
