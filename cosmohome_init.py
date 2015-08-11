#!/usr/bin/env python

import os
from os import system as sh
import os.path as osp
import sys
import _mysql_exceptions

sys.path.append('/root/boinc/py')
import boinc_path_config
from Boinc import database, configxml



print "Copying project files to data volume..."
sh('cp -r /root/projects.build/cosmohome /root/projects')
for x in ['html', 'html/cache', 'upload']: 
    sh('chmod -R g+w /root/projects/cosmohome/'+x)


print "Linking httpd.conf..."
conf_file = '/root/projects/cosmohome/cosmohome.httpd.conf'
sym_target = osp.join("/etc/apache2/sites-enabled/",osp.basename(conf_file))
if not osp.exists(sym_target): os.symlink(os.path.abspath(conf_file),sym_target)

if not '--copy-only' in sys.argv:
    
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
    else:
        sh('cd /root/projects/cosmohome/html/ops; ./db_schemaversion.php > /root/projects/cosmohome/db_revision')



    print "Running BOINC update scripts..."
    os.chdir('/root/projects/cosmohome')
    sh('bin/xadd')
    sh('(echo y; echo y; echo y; echo y) | bin/update_versions')
