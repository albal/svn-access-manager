-- phpMyAdmin SQL Dump
-- version 3.5.2.2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Oct 22, 2012 at 05:10 PM
-- Server version: 5.1.61
-- PHP Version: 5.3.3

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `svnadmin`
--

-- --------------------------------------------------------

--
-- Table structure for table `help`
--

DROP TABLE IF EXISTS `help`;
CREATE TABLE IF NOT EXISTS `help` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `topic` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `headline_en` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `headline_de` varchar(255) COLLATE latin1_german1_ci NOT NULL,
  `helptext_de` longtext COLLATE latin1_german1_ci NOT NULL,
  `helptext_en` longtext COLLATE latin1_german1_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_topic` (`topic`),
  FULLTEXT KEY `helptext_de` (`helptext_de`),
  FULLTEXT KEY `helptext_en` (`helptext_en`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_german1_ci COMMENT='Table of help texts' AUTO_INCREMENT=42 ;

--
-- Dumping data for table `help`
--

INSERT INTO `help` (`id`, `topic`, `headline_en`, `headline_de`, `helptext_de`, `helptext_en`) VALUES
(1, 'login', 'Login', 'Login', 'Geben Sie Ihren Benutzernamen und Ihr Passwort ein. Sollte Ihr Passwort abgelaufen sein, m&uuml;ssen Sie es nach dem Login &auml;ndern.', 'Insert your username and your password. If you passwors is expired you have to change it after login.'),
(2, 'general', 'General', 'Allgemein', 'Sie k&ouml;nnen Ihren Vornamen, Ihren Namen und die Emailadresse &auml;ndern. Bitte beachten Sie, dass Sie eine g&uuml;ltige E-Mailadresse hinterlegen, da Sie sonst Informationen, Ihre Benutzerkennung betreffen, nicht bekommen. Das kann zur Sperre der Benutzerkennung f&uuml;hren. \r\nWenn Sie eine Sicheheritsfrage und eine dazugeh&ouml;rige Antwort hinterlegen, k&ouml;nnen Sie Ihr Passwort selbst zur&uuml;chsetzen. Bei der Antwort muss auf gro&szlig;- und Kleinschreibung geachtet werden.', 'You can change your given name, your name and your email address. Please keep in mind that you supply a valid email address because otherwise you can not receive informations concerning your account. This can lead to a lock out of your account. \r\nIf you provide a security question and a security answer you can recover your password yourself. The security answer is case sensitive.'),
(3, 'password', 'Password', 'Passwort', 'Geben Sie Ihr altes Passwort und dann zweimal das neue Passwort ein. Das neue Passwort muss den Passwort Richtlinien entsprechen.\r\nBeachten Sie dass Ihr Passwort für das SVN Access manager Webinterface sofort g&uuml;ltig ist, es aber einige Zeit dauern kann, bis das Passwort f&uuml; den Zugriff auf die repositories g&uuml;ltig wird. Das h&auml;nt davon ab, ob und wie lange das Update Intervall gesetzt ist.', 'Fill in your old password and the new password two times. The new password must fit the password policy.\r\nNote that your new password becomes valid for the SVN Access Manager Webinterface immediately, but may take some time for repository access itself. The latter depends from if and how your system administrator has setup the update-interval for passwords.'),
(4, 'password_policy', 'Password policy', 'Passwort Richtlinien', 'Hinweise zu den Passwort Richtlinien.', 'Hints regarding the password policy.'),
(5, 'preferences', 'Preferences', 'Voreinstellungen', 'Hinterlegen Sie ihre bevorzugten Einstellungen.', 'Save your personnell preferences.'),
(6, 'main', 'Main menu', 'Hauptmen&uuml;', 'Das Hauptmen&uuml;. Hier sehen Sie alle Ihnen zur Verf&uuml;gung stehenden Funktionen.', 'The main menu. Here you can see all functions you can use.'),
(7, 'listaccessrights', 'Overview of access rights', '&Uuml;bersicht der Zugriffsrechte', 'Die Liste zeigt alle vergebenen Zugriffsrechte auf die verschiedenen Subversion Repositories. Es sind nur die Rechte zu sehen, die Sie auch administrieren k&ouml;nnen.\r\nSie k&ouml;nnen mehrere Rechte l&ouml;schen, indem Sie diese markieren und aus "ausgew&auml;hlte l&ouml;schen" klicken.', 'The list shows all available access rights to the different subversion repositories. You can only see the rights you can administer yourself.\r\nYou can delete a couple of rights by marking them and klick to "delete selected".'),
(8, 'listusers', 'Users', 'Benutzer', 'Aus der Liste der Benutzer kann ein Benutzer &uuml;ber die Symbole rechts unter Aktion ausgew&auml;hlt oder gel&ouml;scht werden.\r\nNeue Benutzer werden &uuml;ber das Plus-Symbol am unteren Ende der Liste eingerichtet.', 'An user can be selected or deleted by clicking on the symbols in the Action column.\r\nNew users can be added by clicking on the button with the plus sign at the bottom of the list.'),
(9, 'workonuser', 'User Administration / edit user', 'Benutzer Administration / Benutzer &auml;ndern', 'Ein Benutzer muss eine eindeutige Benutzerkennung haben. Diese kann nur bei der Neuanlage vergeben werden und kann dann nicht mehr ge&auml;ndert werden.\r\nBei der Neuanlage muss dem Benutzer ein Passwort entsprechend der Passwort Richtlinie zugewiesen werden.\r\nDie Emailadresse muss immer angegeben werden. Es muss sich um eine g&uuml;ltige E-Mailadresse handeln. Diese Adresse ist wichtig, da Benachrichtigugen zu abgelaufenen Passworten an diese Adresse geschickt werden.\r\nWenn ein Benutzer gesperrt ist, kann er auf keine Repositories mehr zugreifen.\r\nDas globale Repository Zugriffsrecht kann ''lesen'' oder ''schreiben'' sein. Das globale Zugriffsrecht kann nicht mit speziellen Repository Zugriffsrechten &uuml;berschrieben werden. \r\nIm Bereich "Globale Benutzer Rechte" k&ouml;nnen &uuml;bergreifende Rechte vergeben werden. Dabei schlie&szlig;t das Recht "L&ouml;schen" die rechte "editieren" und "lesen" mit ein und das Recht "editieren" schlie&szlig;t das Recht "lesen" mit ein.', 'An user must have an unique username. This username can only be set during initial generation and connot be changed afterwards. At generation of the user a password corresponding to the password policy must be assigned.\r\nThe email address must always be set. It must be a valid email address. The address is important because notifications regarding expired passwords are sent to this address.\r\nIf a user is locked the user can''t access any repository any longer.\r\nThe global repository access right can be ''read'' or ''write''. This global right can not be overwritten by dedicated repository access rights.\r\nIn the section "Global user rights" global rights can be assigned to the user. The right "delete" includes the rights "edit" and "read" and the right "edit" includes the right "read".'),
(10, 'listgroups', 'Group administration', 'Gruppen Administration', 'Aus der Liste der Gruppen kann eine Gruppe &uuml;ber die Symbole rechts unter Aktion ausgew&auml;hlt oder gel&ouml;scht werden.\r\nNeue Gruppen werden &uuml;ber das Plus-Symbol am unteren Ende der Liste eingerichtet.', 'A group can be selected or deleted by clicking on the symbols in the Action column.\r\nNew groups can be added by clicking on the button with the plus sign at the bottom of the list.'),
(11, 'addgrouptoproject', 'Add group to project', 'Gruppe zu Projekt hinzuf&uuml;gen', '', ''),
(12, 'addmembertoproject', 'addmembertoproject', 'addmembertoproject', '', ''),
(13, 'createacessfiles', 'createacessfiles', 'createacessfiles', '', ''),
(14, 'deleteaccessright', 'deleteaccessright', 'deleteaccessright', '', ''),
(15, 'deletegroup', 'deletegroup', 'deletegroup', '', ''),
(16, 'deletegroupaccessright', 'deletegroupaccessright', 'deletegroupaccessright', '', ''),
(17, 'deleteproject', 'deleteproject', 'deleteproject', '', ''),
(18, 'deleterepo', 'deleterepo', 'deleterepo', '', ''),
(19, 'deleteuser', 'deleteuser', 'deleteuser', '', ''),
(22, 'listgroupadmins', 'List group admins', 'Liste der gruppen Administratoren', 'Ein gruppenadministrator kann &uuml;ber die Symbole in der Spalte Aktion ausgew&auml;hlt oder gel&ouml;scht werden.\r\nEin neuer Gruppenadministrator kann durch klicken auf den Kopf mit dem Plus Zeichen angelegt werden.', 'A group administrator can be selected or deleted by clicking on the symbols in the Action column.\r\nNew group administrators can be added by clicking on the button with the plus sign at the bottom of the list.'),
(23, 'listprojects', 'listprojects', 'listprojects', '', ''),
(24, 'listrepos', 'Repository list', 'Repository Liste', 'Ein Repository kann durch Klicken auf die Symbole in der Spalte Aktion ausgew&auml;hlt oder gel&ouml;hlt werden.\r\nEin neues Repository kann durch klicken auf den Knopf mit dem Plus Zeichen am Ende der Liste erstellt werden.', 'A repository can be selected or deleted by clicking on the symbols in the Action column.\r\nNew repositories can be added by clicking on the button with the plus sign at the bottom of the list.'),
(26, 'nopermission', 'nopermission', 'nopermission', '', ''),
(27, 'repaccessrights', 'repaccessrights', 'repaccessrights', '', ''),
(28, 'repgranteduserrights', 'repgranteduserrights', 'repgranteduserrights', '', ''),
(29, 'replockedusers', 'replockedusers', 'replockedusers', '', ''),
(30, 'replog', 'replog', 'replog', '', ''),
(31, 'repshowgroup', 'repshowgroup', 'repshowgroup', '', ''),
(32, 'repshowuser', 'repshowuser', 'repshowuser', '', ''),
(33, 'searchresult', 'searchresult', 'searchresult', '', ''),
(34, 'selectproject', 'selectproject', 'selectproject', '', ''),
(35, 'selectgroup', 'Select group', 'Gruppe ausw&auml;hlen', 'W&auml;heln Sie die Guppe aus, die Sie bearbeiten wollen.', 'Select the group you want to work with.'),
(36, 'setaccessright', 'setaccessright', 'setaccessright', '', ''),
(37, 'workonaccessright', 'workonaccessright', 'workonaccessright', '', ''),
(38, 'workongroup', 'Add or edit group', 'Gruppe hinzuf&uuml;gen oder &auml;ndern', 'Eine gruppe muss einen eindeutigen Namen, eine Beschreibung und mindestens ein Mitglied haben. wenn die gruppe keine Mitglieder mehr hat, muss sie gel&ouml;scht werden.', 'A group must have an unique name, a description and at least one member. If the group will have no more members, you have to delete the group.'),
(39, 'workongroupaccessright', 'workongroupaccessright', 'workongroupaccessright', 'W&auml;hlen Sie den Benutzer und das Recht, das dem Benutzer gew&auml;hrt werden soll aus.\r\nDas Löschen recht schlie&szlig;t das Recht editieren und lesen ein. Das Editieren Recht erlaubt das lesen und &auml;ndern der Gruppe. Das Leserecht erlaubt dem Benutzer nur, die Gruppe anzusehen.', 'Select the user ans the right you want to grant to the user.\r\nThe delete right includes edit and read permission, edit includes the permission to read and edit the group. Read permissions only allows the user to see the group.'),
(40, 'workonproject', 'workonproject', 'workonproject', '', ''),
(41, 'workonrepo', 'workonrepo', 'workonrepo', '', '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
