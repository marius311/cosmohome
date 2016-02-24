#!/usr/bin/env python
import matplotlib as mpl
mpl.use('Agg')
from pylab import *
import MySQLdb as mdb
import os.path as osp


def getjobs(i): 
    cur.execute(
"""select w.name from workunit w 
inner join result r on r.workunitid=w.id 
where w.appid=5 and r.outcome%s"""%i)
    return set([tuple(map(int,x[0].split('_')[-3:])) for x in cur.fetchall()])

def maskmat(jobs):
    return array([[(1. if (lslice[0],lslice[1],seed) in jobs else 0) for lslice in lslices] for seed in range(10000)]).T


con = mdb.connect('mysql', 'root', '', 'cosmohome')
cur=con.cursor()

success_jobs, inprogress_jobs, error_jobs = (getjobs(i) for i in ['=1','=0','>1'])

lslices=[]        
for lsplit in range(100,2500,50)+[2509]:
    if lsplit<1700: lslices.append((lsplit,2509))
    for lmin in (2,30):
        if lsplit>=650: lslices.append((lmin,lsplit))


xkcd()
matshow([maskmat(success_jobs).sum(axis=0)],aspect=1000,cmap='Blues')

gca().set_yticks([])
gca().xaxis.set_ticks_position('bottom')
xlabel('Number of Simulations Completed',size=16)
gcf().set_size_inches(15,15)
savefig(osp.join(osp.dirname(__file__),'../html/user/img/lsplitsims_jobplot.png'),bbox_inches='tight',dpi=74)
