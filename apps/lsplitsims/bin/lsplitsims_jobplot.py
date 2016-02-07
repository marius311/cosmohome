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
    return array([[(1. if (lslice[0],lslice[1],seed) in jobs else 0) for lslice in lslices] for seed in range(1000)]).T


con = mdb.connect('mysql', 'root', '', 'cosmohome')
cur=con.cursor()

success_jobs, inprogress_jobs, error_jobs = (getjobs(i) for i in ['=1','=0','>1'])

lslices=[]        
for lsplit in range(100,2500,50)+[2509]:
    if lsplit<1700: lslices.append((lsplit,2509))
    for lmin in (2,30):
        if lsplit>=650: lslices.append((lmin,lsplit))


xkcd()
clrs=['w',
      'lightgray',
      (0.8352941176470589, 0.3686274509803922, 0.0),
      (0.0, 0.4470588235294118, 0.6980392156862745)]
matshow(maskmat(success_jobs)*3+maskmat(error_jobs)*2+maskmat(inprogress_jobs)*1,vmin=0,vmax=4,
        cmap=cm.jet.from_list(None,clrs,N=4))

gca().set_yticks([0,50,100])
gcf().set_size_inches(15,15)
savefig(osp.join(osp.dirname(__file__),'../html/user/img/lsplitsims_jobplot.png'),bbox_inches='tight',dpi=74)
