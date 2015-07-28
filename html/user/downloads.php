<?php

require_once("../inc/util.inc");
require_once("../project/project.inc");

//init_session();

page_head("Download ".PROJECT." Desktops");

echo("<table class='thumbpage'>
				
			<tr> 
			<td align=center><img src=\"img/desktop/nebula1_thumb.jpg\" alt=\"nebula1\" /><br/><br/>
			        <a href=\"img/desktop/nebula1/1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/nebula1/1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/nebula1/1600x1200.jpg\" target=_blank>1600 x 1200</a><br/>
			<td align=center><img src=\"img/desktop/nebula2_thumb.jpg\" alt=\"nebula1\" /><br/><br/>
			        <a href=\"img/desktop/nebula2/1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/nebula2/1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/nebula2/1600x1200.jpg\" target=_blank>1600 x 1200</a><br/>
					
			<td align=center><img src=\"img/desktop/logo_thumb.jpg\" alt=\"nebula1\" /><br/><br/>
			        <a href=\"img/desktop/logo/1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/logo/1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/logo/1600x1200.jpg\" target=_blank>1600 x 1200</a><br/>
			</tr>
			
			<tr>
			<td align=center><img src=\"img/desktop/rockethouse.jpg\" alt=\"rockethouse\" /><br/><br/>
			        <a href=\"img/desktop/rockethouse/1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/rockethouse/1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/rockethouse/1600x1200.jpg\" target=_blank>1600 x 1200</a><br/>
			
			<td align=center><img src=\"img/desktop/early_u_bg.jpg\" alt=\"earlyuniverse\" /><br/><br/>
			        <a href=\"img/desktop/early_u/1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/early_u/1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/early_u/1600x1200.jpg\" target=_blank>1600 x 1200</a><br/>
					
			<td align=center><img src=\"img/desktop/chalk_thumb.jpg\" alt=\"chalkboard\" /><br/><br/>
			        <a href=\"img/desktop/chalkboard/1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/chalkboard/1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/chalkboard/1600x1200.jpg\" target=_blank>1600 x 1200</a><br/>
				
				</tr>
				
				<tr>
				<td align=center><img src=\"img/desktop/01_thumbnail.jpg\" alt=\"desktop 1\" width=200 height=150 border=0/><br/><br/>
					<a href=\"img/desktop/design01/01_1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/design01/01_1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/design01/01_1600x1200.jpg\" target=_blank>1600 x 1200</a><br/></td>
				<td align=center><img src=\"img/desktop/02_thumbnail.jpg\" alt=\"desktop 2\" width=200 height=150 border=0/><br/><br/>
					<a href=\"img/desktop/design02/02_1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/design02/02_1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/design02/02_1600x1200.jpg\" target=_blank>1600 x 1200</a><br/></td>
				<td align=center><img src=\"img/desktop/02lt_thumbnail.jpg\" alt=\"desktop 3\" width=200 height=150 border=0/><br/>			 				<br/>
					<a href=\"img/desktop/design02lt/02lt_1024x768.jpg\" target=_blank>1024 x 768</a><br/>
					<a href=\"img/desktop/design02lt/02lt_1280x800.jpg\" target=_blank>1280 x 800</a><br/>
					<a href=\"img/desktop/design02lt/02lt_1600x1200.jpg\" target=_blank>1600 x 1200</a><br/></td>
			   </tr>
			
			
		
			
			
			</td>
		</table>");

page_tail();
