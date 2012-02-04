<?php

/*
    SVN Access Manager - a subversion access rights management tool
    Copyright (C) 2008 Thomas Krieger <tom@svn-access-manager.org>

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

 
//error_reporting  (E_ERROR | E_WARNING | E_PARSE);

error_reporting  (E_NOTICE | E_ERROR | E_WARNING | E_PARSE);
ini_set( 'display_errors', 'On' );
ini_set( 'display_startup_errors', 'On' );
ini_set( 'log_errors', 'On' );
ini_set( 'html_errors', 'Off' );
 
if (preg_match("/functions.inc.php/", $_SERVER['PHP_SELF'])) {
   
   header ("Location: login.php");
   exit;
   
}



//
// initalize_i18n
// Action: inialize gettext
// Call: initialize_i18n()
//
function initialize_i18n() {
	
	$locale 					= get_locale();
	#error_log( "got locale: $locale" );
	
	if( ! preg_match( '/_/', $locale ) ) {
		
		$locale					= $locale."_".strtoupper($locale);
		#error_log( "set locale to: $locale" );
	}
	
	putenv("LANG=$locale");
	putenv("LC_ALL=$locale");
	setlocale(LC_ALL, 0);
	setlocale(LC_ALL, $locale);
	
	if ( file_exists ( realpath ( "./locale/de/LC_MESSAGES/messages.mo" ) ) ) {
		
		$localepath				= "./locale";
		
	} elseif( file_exists( realpath( "../locale/de/LC_MESSAGES/messages.mo" ) ) ) {
		
		$localepath				= "../locale";
		
	} else {
		
		$localepath				= "./locale";
		
	}
	
	$dom						= bindtextdomain("messages", $localepath);
	$msgdom 					= textdomain("messages");
	
	bind_textdomain_codeset("messages", 'UTF-8');

}




//
// check_session
// Action: Check if a session already exists, if not redirect to login.php
// Call: check_session ()
//
function check_session() {
   
   	#error_log( "check session" );
   	$s 						= new Session;
	session_start ();
   
   	if (!session_is_registered ("svn_sessid"))  {
      	
      	header ("Location: login.php");
      	exit;
      
   	}
   
   	$SESSID_USERNAME 		= $_SESSION['svn_sessid']['username'];
   
   	return $SESSID_USERNAME;
}



//
// check_session_lpw
// Action: Check if a session already exists, if not redirect to login.php
// Call: check_session ()
//
function check_session_lpw( $redirect="y" ) {
   
   
   	$s 						= new Session;
	session_start ();
   
   	if (!session_is_registered ("svn_lpw"))  {
     
     	 $SESSID_USERNAME 	= "";
     	 
      	if( $redirect == "y" ) {
      		header ("Location: lostpassword.php");
      		exit;
      	}
      
   	} else {
   
   		if( isset( $_SESSION['svn_lpw']['username'] ) ) {
   			$SESSID_USERNAME 		= $_SESSION['svn_lpw']['username'];
   		} else {
   			$SESSID_USERNAME 		= "";
   		}
   	}
   
   	return $SESSID_USERNAME;
}




//
// create_verify_string
// Action: create a verify string for email verification
// Call: create_verify_string
//
function create_verify_string ()
{
	$validchars   = "ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890abcdefghijklmnopqrstuvwxyz";
	$verifyString = "";
	for( $i = 0; $i < 32; $i++ ) {
		$char = chr( mt_rand( 0, 255 ) );
		while( strpos( $validchars, $char ) == 0 ) {
			$char = chr( mt_rand( 0, 255 ) );
		}
		$verifyString .= $char;
	}
	
	return $verifyString;
}




//
// check_password_expired
// Action: checks if a password is expired and akes the user to the password change mask
// Call: check_password_expired
//
function check_password_expired() {
	
	if( isset( $_SESSION['svn_sessid']['password_expired'] ) ) {
	
		if( $_SESSION['svn_sessid']['password_expired'] == 1 ) {

			header( "Location: password.php" );
			exit;			
		}
	}
}

//
// check_language
// Action: checks what language the browser uses
// Call: check_language
//
function check_language () {
   
   global $CONF;
   $supported_languages 			= array ('de', 'en');
   $lang_array 						= preg_split ('/(\s*,\s*)/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
   
   if (is_array ($lang_array))  {
      
      $lang_first 					= strtolower ((trim (strval ($lang_array[0]))));
      $lang_first 					= substr ($lang_first, 0, 2);
      
      if (in_array ($lang_first, $supported_languages)) {
         
         $lang 						= $lang_first;
         
      } else {
         
         $lang 						= $CONF['default_language'];
         
      }
   } else {
      
      $lang							 = $CONF['default_language'];
      
   }
   
   return $lang;
}



//
// check_language
// Action: checks what language the browser uses
// Call: check_language
//
function get_locale() {
   
   global $CONF;
   
   if( isset( $CONF ) ) {
   		$supported_languages		= $CONF['supported_languages'];
   } else {
   		$supported_languages		= array ('de', 'de_DE', 'en', 'en_US');	
   }
   
   $lang_array 						= preg_split ('/(\s*,\s*)/', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
   
   if (is_array ($lang_array))  {
      
      $lang_first 					= strtolower ((trim (strval ($lang_array[0]))));
      $lang_parts					= explode( '-', $lang_array[0] );
      $count						= count( $lang_parts );
      if( $count == 2 ) {
      	
      		$lang_parts[1]			= strtoupper( $lang_parts[1] );
      		$lang_first				= implode( "_", $lang_parts );
      }
      
      if (in_array ($lang_first, $supported_languages)) {
         
         $lang 						= $lang_first;
         
      } else {
         
         $lang 						= $CONF['default_locale'];
         
      }
      
   } else {
      
      $lang							 = $CONF['default_locale'];
      
   }
   
   return $lang;
}



//
// check_string
// Action: checks if a string is valid and returns TRUE is this is the case.
// Call: check_string (string var)
//
function check_string ($var) {
   
   if (preg_match ('/^([A-Za-z0-9 ]+)+$/', $var)) {
      return true;
   } else {
      return false;
   }
} 



//
// check_email
// Action: Checks if email is valid and returns TRUE if this is the case.
// Call: check_email (string email)
//
function check_email ($email) {
	
   if (preg_match ('/^[-!#$%&\'*+\\.\/0-9=?A-Z^_{|}~]+' . '@' . '([-0-9A-Z]+\.)+' . '([0-9A-Z]){2,4}$/i', trim ($email))) {
      
      return true;
      
   }  else {
      
      return false;
      
   }
}



//
// getDateJhjjmmtt
// Action: retrieve date in format YYYYMMTT
// Call getDateJhjjmmtt
//
function getDateJhjjmmtt() {
	
	$date     	= getdate();
	$year     	= $date['year'];
	$mon      	= $date['mon'];
    $day		= $date['mday'];
    
	if( $mon < "10" ) 		$mon = "0".$mon;
	if( $day < "10" )		$day = "0".$day;
	
	$moddate  	= $year.$mon.$day;

	return $moddate;
}



//
// splitDate
// Action: convert date from jhjjmmtt to t.mm.jhjj
// Call: splitdate( string date )
//
function splitdate( $date ) {
	
	$year = substr( $date, 0, 4 );
	$mon  = substr( $date, 4, 2 );
	$day  = substr( $date, 6, 2 );
	$datum= $day.".".$mon.".".$year;

	return $datum;
}



//
// check_date
// Action: check a date string if it is a valid date
// Call: check_date (string day, string month, string year)
//
function check_date ($day, $month, $year) {
	
	if (preg_match ('/[0-9]{2}/',$day)) {
		
		if (preg_match ('/[0-9]{2}/',$month)) {
			
			if (preg_match ('/[0-9]{4}/',$year)) {
				
				if (checkdate ($month, $day, $year)) {
					
					return true;
					
				} else {
					
					return false;
					
				}
			} else {
				
				return false;
				
			}
			
		} else {
			
			return false;
			
		}
	} else {
		
		return false;
		
	}
}



function no_magic_quotes($query) {
        $data = explode("\\\\",$query);
        $cleaned = implode("\\",$data);
        return $cleaned;
}



//
// escape_string
// Action: Escape a string
// Call: escape_string (string string)
//
function escape_string ($string) {
   
   	global $CONF;
   
   	if (get_magic_quotes_gpc() == 0)  {
   	
   		if( is_array( $string) ) {
   			
   			return $string;
   			
   		} else {

      		if ($CONF['database_type'] == "mysql") { 	
      			$escaped_string = mysql_real_escape_string ($string);
      		}
      		if ($CONF['database_type'] == "mysqli") {  	
      			$escaped_string = mysqli_real_escape_string ($string);
      		}
      		if ($CONF['database_type'] == "pgsql") {  	
      			$escaped_string = pg_escape_string ($string);
   			}
      		
   		}
      
   	} else {
      
      $escaped_string = $string;
      
   	}
   
   	return $escaped_string;
}



// 
// encode_header
// Action: Encode a string according to RFC 1522 for use in headers if it contains 8-bit characters.
// Call: encode_header (string header, string charset)
//
function encode_header ($string, $default_charset) {
   
   if (strtolower ($default_charset) == 'iso-8859-1') {
      $string = str_replace ("\240",' ',$string);
   }

   $j = strlen ($string);
   $max_l = 75 - strlen ($default_charset) - 7;
   $aRet = array ();
   $ret = '';
   $iEncStart = $enc_init = false;
   $cur_l = $iOffset = 0;

   for ($i = 0; $i < $j; ++$i)
   {
      switch ($string{$i})
      {
         case '=':
         case '<':
         case '>':
         case ',':
         case '?':
         case '_':
         if ($iEncStart === false)
         {
            $iEncStart = $i;
         }
         $cur_l+=3;
         if ($cur_l > ($max_l-2))
         {
            $aRet[] = substr ($string,$iOffset,$iEncStart-$iOffset);
            $aRet[] = "=?$default_charset?Q?$ret?=";
            $iOffset = $i;
            $cur_l = 0;
            $ret = '';
            $iEncStart = false;
         }
         else
         {
            $ret .= sprintf ("=%02X",ord($string{$i}));
         }
         break;
         case '(':
         case ')':
         if ($iEncStart !== false)
         {
            $aRet[] = substr ($string,$iOffset,$iEncStart-$iOffset);
            $aRet[] = "=?$default_charset?Q?$ret?=";
            $iOffset = $i;
            $cur_l = 0;
            $ret = '';
            $iEncStart = false;
         }
         break;
         case ' ':
         if ($iEncStart !== false)
         {
            $cur_l++;
            if ($cur_l > $max_l)
            {
               $aRet[] = substr ($string,$iOffset,$iEncStart-$iOffset);
               $aRet[] = "=?$default_charset?Q?$ret?=";
               $iOffset = $i;
               $cur_l = 0;
               $ret = '';
               $iEncStart = false;
            }
            else
            {
               $ret .= '_';
            }
         }
         break;
         default:
         $k = ord ($string{$i});
         if ($k > 126)
         {
            if ($iEncStart === false)
            {
               // do not start encoding in the middle of a string, also take the rest of the word.
               $sLeadString = substr ($string,0,$i);
               $aLeadString = explode (' ',$sLeadString);
               $sToBeEncoded = array_pop ($aLeadString);                  
               $iEncStart = $i - strlen ($sToBeEncoded);
               $ret .= $sToBeEncoded;
               $cur_l += strlen ($sToBeEncoded);
            }
            $cur_l += 3;
            // first we add the encoded string that reached it's max size
            if ($cur_l > ($max_l-2))
            {
               $aRet[] = substr ($string,$iOffset,$iEncStart-$iOffset);
               $aRet[] = "=?$default_charset?Q?$ret?= ";
               $cur_l = 3;
               $ret = '';
               $iOffset = $i;
               $iEncStart = $i;
            }
            $enc_init = true;
            $ret .= sprintf ("=%02X", $k);
            }
            else
            {
            if ($iEncStart !== false)
            {
               $cur_l++;
               if ($cur_l > $max_l)
               {
                  $aRet[] = substr ($string,$iOffset,$iEncStart-$iOffset);
                  $aRet[] = "=?$default_charset?Q?$ret?=";
                  $iEncStart = false;
                  $iOffset = $i;
                  $cur_l = 0;
                  $ret = '';
                  }
                  else
                  {
                     $ret .= $string{$i};
                  }
               }
            }
            break;
         }
      }
      if ($enc_init)
      {
         if ($iEncStart !== false)
         {
            $aRet[] = substr ($string,$iOffset,$iEncStart-$iOffset);
            $aRet[] = "=?$default_charset?Q?$ret?=";
         }
         else
         {
            $aRet[] = substr ($string,$iOffset);
         }
         $string = implode ('',$aRet);
      }
   return $string;
}



//
// generate_password
// Action: Generates a random password
// Call: generate_password ()
//
function generate_password ()
{
   $password = substr (md5 (mt_rand ()), 0, 8);
   return $password;
}



function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}




//
// generatePassword
// Action: Generates a random password
// Call: generatePassword ()
//
function generatePassword( $admin ) {
	
	global $CONF;
	
	if( strtolower($admin) == "y" ) {
		$pwLength		= 14;
	} else {
		$pwLength		= 8;
	}
	
	$password			= "";

	while( checkPasswordPolicy( $password, strtolower($admin) ) == 0 ) {
		
		$password		= "";
		
		for( $i = 1; $i <= $pwLength; $i++ ) {
			
			$group			= rand(0, 3);
			mt_srand(make_seed());
			
			switch( $group ) {
				case 0:
					$index	= rand(0, 25);
					$value	= chr( $index + 65 );
					break;
					
				case 1:
					$index	= rand(0, 25);
					$value	= chr( $index + 97 );
					break;
					
				case 2:
					$value	= rand(0, 9);
					break;
					
				case 3:
					$group	= rand(0, 2);
					
					switch( $group ) {
						case 0:
							$index	= rand(33,47);
							break;
							
						case 1:
							$index = 60;
							while( ($index == 60) or ($index == 62) ) { 
								$index = rand(58, 64);
							}
							break;
							
						case 2:
							$index = rand(91, 96);
							break;
							
					}
					
					$value 	= chr( $index );
					break;
					
			}
			
			$password		.= $value;
		}
	}
	
	return( $password );
}



//
// pacrypt
// Action: Encrypts password based on config settings
// Call: pacrypt (string cleartextpassword)
//
function pacrypt ($pw, $pw_db="") {

	global $CONF, $MAGIC;
	
	if( isset( $CONF['pwcrypt'] ) ) {
		
		$crypt						= $CONF['pwcrypt'];
		
	} else {
		
		$crypt						= "crypt";
		
	}
	
	$size							= strlen( $MAGIC );
	if( substr($pw_db, 0, $size) == $MAGIC ) {
		# md5 crypted password
		
		$split_salt 				= preg_split ('/\$/', $pw_db);
      	if (isset ($split_salt[2])) { 
      		
      		$salt					 = $split_salt[2];
      	}
      	
      	$password 					= md5crypt( $pw, $salt );
      
	} elseif( $pw_db != "" ) {
		
		$salt 						= substr( $pw_db, 0, 2);
		$password 					= crypt ($pw, $salt);
		
	} else {
		
		if( $crypt == "md5" ) {
			
			$password				= md5crypt( $pw );
			
		} else {
		
			srand((double)microtime()*1000000);
		
			$s1   					= chr( rand( 1, 255 ) );
			$s2   					= chr( rand( 1, 255 ) );
			$salt 					= $s1.$s2;	
			$password 				= crypt ($pw, $salt);		
		}
	}
	
	return $password;
}



//
// pacrypt_install
// Action: Encrypts password based on config settings
// Call: pacrypt (string cleartextpassword)
//
function pacrypt_install ($pw, $pw_db="", $crypt="") {

	global $CONF, $MAGIC;
	
	if( $crypt == "" ) {
		if( isset( $CONF['pwcrypt'] ) ) {
			
			$crypt					= $CONF['pwcrypt'];
			
		} else {
			
			$crypt					= "crypt";
			
		}
	}
	
	$size							= strlen( $MAGIC );
	if( substr($pw_db, 0, $size) == $MAGIC ) {
		# md5 crypted password
		
		$split_salt 				= preg_split ('/\$/', $pw_db);
      	if (isset ($split_salt[2])) { 
      		
      		$salt					 = $split_salt[2];
      	}
      	
      	$password 					= md5crypt( $pw, $salt );
      
	} elseif( $pw_db != "" ) {
		
		$salt 						= substr( $pw_db, 0, 2);
		$password 					= crypt ($pw, $salt);
		
	} else {
		
		if( $crypt == "md5" ) {
			
			$password				= md5crypt( $pw );
			
		} else {
		
			srand((double)microtime()*1000000);
		
			$s1   					= chr( rand( 1, 255 ) );
			$s2   					= chr( rand( 1, 255 ) );
			$salt 					= $s1.$s2;	
			$password 				= crypt ($pw, $salt);		
		}
	}
	
	return $password;
}




//
// md5crypt
// Action: Creates MD5 encrypted password
// Call: md5crypt (string cleartextpassword)
//
if( determineOs() == "windows" ){
	
	$MAGIC				= '$apr1$';
	
} else {
	
	$MAGIC  			= "$1$";
	
}
$ITOA64 				= "./0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

function md5crypt ($pw, $salt="", $magic="") {
   
   global $MAGIC;
   

   if ($magic == "") $magic = $MAGIC;
   if ($salt == "") $salt = create_salt (); 
   
   $slist 							= explode ("$", $salt);
   
   if ($slist[0] == "1") $salt = $slist[1];

   $salt 							= substr ($salt, 0, 8);
   $ctx 							= $pw . $magic . $salt;
   $final 							= hex2bin (md5 ($pw . $salt . $pw));

   for ($i=strlen ($pw); $i>0; $i-=16) {
      if ($i > 16) {
         $ctx 						.= substr ($final,0,16);
      } else {
         $ctx 						.= substr ($final,0,$i);
      }
   }
   $i 								= strlen ($pw);
   
   while ($i > 0)    {
      
      if ($i & 1) $ctx 				.= chr (0);
      else $ctx 					.= $pw[0];
      $i 							= $i >> 1;
   }
   
   $final 							= hex2bin (md5 ($ctx));

   for ($i=0;$i<1000;$i++) {
      
      $ctx1 						= "";
      if ($i & 1)       {
         
         $ctx1 						.= $pw;
      } else {
         
         $ctx1 						.= substr ($final,0,16);
      }
      
      if ($i % 3) $ctx1 			.= $salt;
      if ($i % 7) $ctx1 			.= $pw;
      if ($i & 1) {
         $ctx1 						.= substr ($final,0,16);
      } else {
         $ctx1 						.= $pw;
      }
      
      $final 						= hex2bin (md5 ($ctx1));
   }
   
   $passwd 							= "";
   $passwd 							.= to64 (((ord ($final[0]) << 16) | (ord ($final[6]) << 8) | (ord ($final[12]))), 4);
   $passwd 							.= to64 (((ord ($final[1]) << 16) | (ord ($final[7]) << 8) | (ord ($final[13]))), 4);
   $passwd 							.= to64 (((ord ($final[2]) << 16) | (ord ($final[8]) << 8) | (ord ($final[14]))), 4);
   $passwd 							.= to64 (((ord ($final[3]) << 16) | (ord ($final[9]) << 8) | (ord ($final[15]))), 4);
   $passwd 							.= to64 (((ord ($final[4]) << 16) | (ord ($final[10]) << 8) | (ord ($final[5]))), 4);
   $passwd 							.= to64 (ord ($final[11]), 2);
   
   return "$magic$salt\$$passwd";

}

function create_salt () {
	
   srand ((double) microtime ()*1000000);
   $salt 							= substr (md5 (rand (0,9999999)), 0, 8);
   
   return $salt;
}

function hex2bin ($str) {
   
   $len 							= strlen ($str);
   $nstr 							= "";
   
   for ($i=0;$i<$len;$i+=2) {
      $num 							= sscanf (substr ($str,$i,2), "%x");
      $nstr							.=chr ($num[0]);
   }
   
   return $nstr;
}

function to64 ($v, $n) {
   
   global $ITOA64;
   $ret 							= "";
   
   while (($n - 1) >= 0)    {
      $n--;
      $ret 							.= $ITOA64[$v & 0x3f];
      $v 							= $v >> 6;
   }
   
   return $ret;
}



//
// checkPasswordPolicy
// Action: check password against password policy
// call: checkPasswordPolicy
// Return: 1 = password ok, 0 = password not ok
//

function checkPasswordPolicy( $password, $admin="y" ) {
	
	global $CONF;
	
	$smallLetters		= preg_match( '/[a-z]/', $password );
	$capitalLetters		= preg_match( '/[A-Z]/', $password );
	$numbers			= preg_match( '/[0-9]/', $password );
	if( isset( $CONF['passwordSpecialChars'] ) ) {
		$pattern		= '/'.$CONF['passwordSpecialChars'].'/';
	} else {
		$pattern		= '/'.'[\!\"\§\$\%\/\(\)=\?\*\+\#\-\_\.\:\,\;\<\>\|\@]'.'/';
	}
	$specialChars		= preg_match( $pattern, $password );
	$passwordLength		= strlen( $password );
	$groups				= 0;
	
	if( $smallLetters != 0 ) {
		$groups++;
	}
	
	if( $capitalLetters != 0 ) {
		$groups++;
	}
	
	if( $numbers != 0 ) {
		$groups++;
	}
	
	if( $specialChars != 0 ) {		
		$groups++;
	}
	
	if( $admin == "y" ) {
		
		if( isset($CONF['minPasswordlength']) ) {
			$minPasswordLength	= $CONF['minPasswordlength'];
		} else {
			$minPasswordLength	= 14;
		}
		if( $passwordLength < $minPasswordLength ) {
			
			$retval			= 0;
			
		} else {
			
			if( isset( $CONF['minPasswordGroups'] ) ) {
				$minPasswordGroups	= $CONF['minPasswordGroups'];
			} else {
				$minPasswordGroups	= 4;
			}
			if( isset( $minPasswordGroups ) ) {
			
				if( ($minPasswordGroups < 1) or ($minPasswordGroups > 4) ) {
					$minGroups	= 4;
				} else {
					$minGroups	= $minPasswordGroups;
				}
				
			} else {
				$minGroups		= 4;
			}
			
			if( $groups < $minGroups ) {
			
				$retval			= 0;
			
			} else {
				
				$retval			= 1;
				
			}	
		}
	
	} else {
		
		if( isset( $CONF['minPasswordlengthUser'] ) ) {
			$minPasswordlengthUser		= $CONF['minPasswordlengthUser'];
		} else {
			$minPasswordLengthUser		= 8;
		}
		if( $passwordLength < $CONF['minPasswordlengthUser'] ) {
			
			$retval			= 0;
			
		} else {
			
			if( isset($CONF['minPasswordGroupsUser']) ) {
				
				if( ($CONF['minPasswordGroupsUser'] < 1) or ($CONF['minPasswordGroupsUser'] > 4 ) ) {
					$minGroupsUser	= 3;
				} else {
					$minGroupsUser	= $CONF['minPasswordGroupsUser'];
				}
				
			} else {
				$minGroupsUser	= 3;
			}
			
			if( $groups < $minGroupsUser ) {
				
				$retval			= 0;
				
			} else {
				
				$retval			= 1;
			}
			
		}
		
	}
	
	return $retval;
}




//
// splitDateTime
// Action: split a datetime value from mysql to date and time
// Call: splitdateTime (string datetime)
//
function splitDateTime( $datetime ) {
	
	$year			= substr( $datetime, 0, 4 );
	$month			= substr( $datetime, 4, 2 );
	$day			= substr( $datetime, 6, 2 );
	$date   		= $day.".".$month.".".$year;
	
	$hour			= substr( $datetime, 8, 2 );
	$min			= substr( $datetime, 10, 2 );
	$sec			= substr( $datetime, 12, 2 );
	$time			= $hour.":".$min.":".$sec;
	
	return array( $date, $time );	
}




//
// splitValidDate
// Action split valid date
// Call: splitValidDate (string date)
//
function splitValidDate( $date ) {
	
	$year			= substr( $date, 0, 4 );
	$month			= substr( $date, 4, 2 );
	$day			= substr( $date, 6, 2 );
	
	$datestr		= $day.".".$month.".".$year;
	
	return $datestr;
}



//
// mkUnixTimestampFromDateTime
// Action: create a unix timestamp from a datetime field
// Call: mkUnixTimestampFromDateTime(string datetime)
//
function mkUnixTimestampFromDateTime( $datetime ) {
	
	$year			= substr( $datetime, 0, 4 );
	$month			= substr( $datetime, 4, 2 );
	$day			= substr( $datetime, 6, 2 );
	$hour			= substr( $datetime, 8, 2 );
	$min			= substr( $datetime, 10, 2 );
	$sec			= substr( $datetime, 12, 2 );
	
	$timestamp		= mktime( $hour, $min, $sec, $month, $day, $year, -1 );
	
	return $timestamp;
}



//
// determineOs
// Action: determine if windows or not
// Call: determineOs()
//
function determineOs() {
	
	$ret			= "undef";
	
	ob_start();
	eval("phpinfo();");
	$info 			= ob_get_contents();
	ob_end_clean();
	
	foreach(explode("\n", $info) as $line) 	{
	      
	      if(strpos($line, "System")!== false) {
	        
	        $show 	= trim(str_replace("System","", strip_tags($line)));
	        
	      }
	}
	
	if( preg_match('/WIN/i', $show) ) {
	        
	        $ret	= "windows";
	} else {
	        $ret	= "unix";
	}

	return( $ret );
}



//
// encode_subject
// Action: encode subject of a email
// Call: encode_subject( string $in_str, string $charset )
//
function encode_subject($in_str, $charset) { 
    $out_str = $in_str; 
    if ($out_str && $charset) { 

        // define start delimimter, end delimiter and spacer 
        $end = "?="; 
        $start = "=?" . $charset . "?B?"; 
        $spacer = $end . "\r\n " . $start; 

        // determine length of encoded text within chunks 
        // and ensure length is even 
        $length = 75 - strlen($start) - strlen($end); 

        /* 
            [EDIT BY danbrown AT php DOT net: The following 
            is a bugfix provided by (gardan AT gmx DOT de) 
            on 31-MAR-2005 with the following note: 
            "This means: $length should not be even, 
            but divisible by 4. The reason is that in 
            base64-encoding 3 8-bit-chars are represented 
            by 4 6-bit-chars. These 4 chars must not be 
            split between two encoded words, according 
            to RFC-2047. 
        */ 
        $length = $length - ($length % 4); 

        // encode the string and split it into chunks 
        // with spacers after each chunk 
        $out_str = base64_encode($out_str); 
        $out_str = chunk_split($out_str, $length, $spacer); 

        // remove trailing spacer and 
        // add start and end delimiters 
        $spacer = preg_quote($spacer); 
        $out_str = preg_replace("/" . $spacer . "$/", "", $out_str); 
        $out_str = $start . $out_str . $end; 
    } 
    return $out_str; 
}
?>
