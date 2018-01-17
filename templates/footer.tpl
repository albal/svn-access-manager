		<div id="footer">
			<?php 
				$datetime = strftime("%c");
				$datetime = str_replace( "�", "&auml;", $datetime ); 
			?>
			<table width="100%" cellspacing="0" border="0" cellpadding="0">
   				<tr>
       				<td nowrap>
						<?php global $CONF; print $CONF['copyright']." - PHP Version: ".PHP_VERSION; ?>
					</td>
					<td nowrap align="right">
						<?php print $datetime; ?>
					</td>
     			</tr>
 			</table>
		</div>