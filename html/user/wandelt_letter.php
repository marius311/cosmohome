<?php

require_once("../inc/util.inc");
require_once("../project/project.inc");

//init_session();

page_head("Letter to ".PROJECT." users");

echo("<div id='bodytext'>
<div class='titlep'> Dear friends, supporters and visitors,</div><br/>

<p>Cosmology@Home is being developed by my group at the University of
Illinois to enable participants to contribute actively to front-line
research in precision cosmology by donating their CPU time.</p>

<p>The goal of Cosmology@Home is to search for the model that best
describes our Universe and to find the range of models that agree with
the available astronomical and particle physics data. In order to
achieve this goal, participants in Cosmology@Home (i.e. you!) will
compute the observable predictions of millions of theoretical models
with different parameter combinations. We will use the results of your
computations to compare all the available data with these models. In
addition, the results from Cosmology@Home can help design future
cosmological observations and experiments, and prepare for the
analysis of future data sets, e.g. from the 
<a href=\"http://sci.esa.int/science-e/www/area/index.cfm?fareaid=17\" target=\"new\">Planck spacecraft.</a></p>

<p>Each work package simulates a Universe with a particular geometry,
particle content, and \"physics of the beginning.\" It produces
predictions of the observable properties of the Universe which we can
then compare to:<br/><br/>
1) the fluctuations in the <a href=\"http://en.wikipedia.org/wiki/CMB\" 
target=\"new\">cosmic microwave background</a>
(observed from space by the <a href=\"http://map.gsfc.nasa.gov\" target=\"_new\">WMAP</a>
and soon the Planck spacecraft, as well
as from ground based and balloon based experiments),<br/>
2) the large scale distribution of galaxies and clusters of galaxies,<br/>
3) measurements of the current expansion speed of the Universe by the 
<a href=\"http://hubblesite.org\" 
target=\"_new\">Hubble space telescope</a>,<br/>
4) the acceleration of the Universe as measured by observations of supernova explosions,<br/>
5) observations of primordial element abundances in distant gas clumps, and<br/>
6) gravitational lensing data, when it becomes available.</p>

<p>Our research group is involved in several areas of theoretical and
phenomenological cosmology: the earliest instants of time, when the
Universe formed, the cosmic microwave background, the cosmic dark ages,
structure formation, <a href=\"http://en.wikipedia.org/wiki/Dark_matter\" 
target=\"_new\">dark matter</a> and <a href=\"http://en.wikipedia.org/wiki/Dark_energy\" 
target=\"_new\">dark energy</a> as well as the
development and adaptation of mathematics, statistics and computation to
advance the state of cosmology.  We expect that we will eventually be
offering several kinds of computations to participate in. All of these
computations will contribute directly to forefront research projects in
cosmology the <a href=\"http://cosmos.astro.illinois.edu\" target=\"_new\">
Wandelt group</a> in the <a href=\"http://www.physics.illinois.edu\" 
target=\"_new\">Physics</a> and <a href=\"http://www.astro.illinois.edu\" 
target=\"_new\">Astronomy</a> departments of
the University of Illinois at Urbana-Champaign.</p>

<p>I would like to take this opportunity to say that my group and I have
been floored by the level of community enthusiasm we have received as a
result of Cosmology@Home. This made us realize the potential of Cosmology@Home as
a way to connect our research group with people who are enthusiastic (or
at least curious!) about cosmology, astrophysics and computing in the
world at large. So I think it is appropriate to set ourselves an
additional goal for Cosmology@Home: beyond being an opportunity for
active public participation in our research program C@H should also
provide the opportunity for everyone to help understand the exciting
research they are contributing to.</p>

<p>As a further incentive for people to participate we are considering
offering the Cosmology@Home Prize for the owner of the computer that
calculated the model that best fits the data as of the 31st of December
2009. We will acknowledge you by your real name in one of our research
publications (of course, only if you grant us permission to use your
name - if you will not, we will pass the prize on to the contributor of
the second best model and so on). Please let us know if this sounds like
an attractive idea to you.</p>

<p>We are looking forward to your feedback on this and all other aspects of
the project. Do not hesitate to contact us, either by e-mail or using
the <a href=\"forum_index.php\">message boards</a> related to Cosmology@Home.</p>

<p>All the best,</p>

<div class='titlep'> Ben Wandelt<br/>
Professor of Physics and Astronomy<br/>
<a href=\"http://www.illinois.edu\" target=\"_new\">University of Illinois at Urbana-Champaign</a><br/>
<br/>
  </div>
  </div>");

page_tail();
