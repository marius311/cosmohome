#!/usr/bin/env python

from glob import glob
from subprocess import check_output


template="""<?php
require_once('../inc/util.inc');
require_once('../inc/translation.inc');

page_head(tra('{title}'));?>

{html}

<?php
page_tail();
?>"""


for f in glob('*.md'):
    html=check_output(['kramdown',f])
    open(f.replace('.md','.php'),'w').write(
        template.format(html=html,
                        title=open(f).readline().replace('#','').strip())
    )
