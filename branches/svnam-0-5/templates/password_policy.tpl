
			<?php
				if( ($_SESSION[SVNSESSID]['admin'] == "y") or ($_SESSION[SVNSESSID]['admin'] == "p") ) {
					
					$len			= $CONF['minPasswordlength'];
					
				} else {
					
					$len			= $CONF['minPasswordlengthUser'];
					
				}
				
				if( isset($CONF['minPasswordGroups']) ) {
					$minGroups		= $CONF['minPasswordGroups'];
				} else {
					$minGroups		= 4;
				}
				if( isset($CONF['minPasswordGroupsUser']) ) {
					$minGroupsUser	= $CONF['minPasswordGroupsUser'];
				} else {
					$minGroupsUser	= 3;
				}
				
				$msg = sprintf( _("<h3>Password policy</h3>
				<p>&nbsp;</p>
				<p>A password must consist of %s characters at least. It must include one character 
				of the %s groups digits, lower case characters. upper case characters and special 
				characters for adminitrator passwords. User passwords must include %s of the four 
				groups mentioned above.</p>
				<p>&nbsp;</p>
				<p>The following special characters are allowed: %s</p>"), $len, $minGroups, $minGroupsUser, htmlspecialchars($CONF['passwordSpecialCharsTxt']));
				print $msg;
			?>
