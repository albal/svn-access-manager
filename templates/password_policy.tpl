
			<?php
				if( ($_SESSION['svn_sessid']['admin'] == "y") or ($_SESSION['svn_sessid']['admin'] == "p") ) {
					
					$len			= $CONF['minPasswordlength'];
					
				} else {
					
					$len			= $CONF['minPasswordlengthUser'];
					
				}
				
				$msg = sprintf( _("<h3>Password policy</h3>
				<p>&nbsp;</p>
				<p>A password must consist of %s characters at least. It must include one character 
				of the four groups digits, lower case characters. upper case characters and special 
				characters.</p>
				<p>&nbsp;</p>
				<p>The following special characters are allowed: %s</p>"), $len, htmlspecialchars($CONF['passwordSpecialCharsTxt']));
				print $msg;
			?>
