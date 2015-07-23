#!/usr/bin/env python

import os
import os.path as osp
import sys
import _mysql_exceptions

sys.path.append('/root/boinc/py')
import boinc_path_config
from Boinc import database, configxml



print "Copying project files to data volume..."
os.system('cp -r /root/projects.build/cosmohome /root/projects')
os.system('chmod -R g+w /root/projects/cosmohome/html/cache') 
os.system('chmod -R g+w /root/projects/cosmohome/log_cosmohome') 


print "Linking httpd.conf..."
conf_file = '/root/projects/cosmohome/cosmohome.httpd.conf'
sym_target = osp.join("/etc/apache2/sites-enabled/",osp.basename(conf_file))
if not osp.exists(sym_target): os.symlink(os.path.abspath(conf_file),sym_target)


print "Creating database..."
try:
    database.create_database(
        srcdir = '/root/boinc',
        config = configxml.ConfigFile(filename='/root/projects/cosmohome/config.xml').read().config,
        drop_first = False
    )
except _mysql_exceptions.ProgrammingError as e:
    if e[0]==1007: print "Database exists, not overwriting."
    else: raise


print "Running BOINC update scripts..."
os.chdir('/root/projects/cosmohome')
os.system('bin/xadd')
os.system('(echo y; echo y; echo y; echo y) | bin/update_versions')

