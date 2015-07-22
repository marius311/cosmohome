#!/usr/bin/env python

from distutils.dir_util import copy_tree
import os
import os.path as osp
import sys

sys.path.append('/root/boinc/py')
import boinc_path_config
from Boinc import database, configxml




print "Copying project files to data volume..."
os.system('cp -r /root/projects.build/cosmohome /root/projects')

#not clear why this is needed in light of the same line in
#the Dockerfile, maybe a BOINC bug?
os.system('chmod -R g+w /root/projects/cosmohome/html/cache') 



print "Linking httpd.conf..."
conf_file = '/root/projects/cosmohome/cosmohome.httpd.conf'
sym_target = osp.join("/etc/apache2/sites-enabled/",osp.basename(conf_file))
if not osp.exists(sym_target): 
    try:
        os.symlink(os.path.abspath(conf_file),sym_target)
    except:
        print "Failed to link '%s' to apache directory. You will need to add this file manually."%conf_file



print "Creating database..."
database.create_database(
    srcdir = '/root/boinc',
    config = configxml.ConfigFile(filename='/root/projects/cosmohome/config.xml').read().config,
    drop_first = True
)
