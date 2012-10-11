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

/*

File:          install.php
Template File: install.tpl
$LastChangedDate: 2012-01-22 08:16:48 +0100 (Sun, 22 Jan 2012) $
$LastChangedBy: kriegeth $

$Id: install-alt.php 493 2012-01-22 07:16:48Z kriegeth $

*/

require ("../include/variables.inc.php");
require ("../include/db-functions-adodb.inc.php");
require ("../include/functions.inc.php");



$DBTABLES									= array( 'help', 'log', 'preferences', 'rights', 'workinfo', 'sessions', 
													 'svngroups', 'svnmailinglists', 'svnpasswordreset', 'svnprojects', 'svnrepos', 'svnusers', 
											  	 	 'svn_access_rights', 'svn_groups_responsible', 'svn_projects_mailinglists',
													 'svn_projects_responsible', 'svn_users_groups', 'users_rights');
																		 
													 

function dropMySQLDatabaseTables( $dbh ) {
	
	global $DBTABLES;
		
	$error									= 0;
	$tMessage								= "";
	
	foreach( $DBTABLES as $dbtable ) {
	
		if( $error == 0 ) {
			
			$query							= "DROP TABLE IF EXISTS `".$dbtable."`";
			$result							= db_query_install( $query, $dbh );
			if( mysql_errno() != 0 ) {
				
				$error						= 1;
				$tMessage					= sprintf( _("Cannot drop table %s"), "log" );
			}
		}	
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
}


function dropOracleDatabaseTables( $dbh, $schema ) {
	
	global $DBTABLES;
	
	$error									= 0;
	$tMessage								= "";
	
	foreach( $DBTABLES as $dbtable ) {
		
		if( $error == 0 ) {
			
			$query							= "begin execute immediate 'drop table $schema.$dbtable cascade constraints'; exception when others then null; end;";
			#error_log($query);
			$result							= db_query_install( $query, $dbh );
			$seq							= $dbtable."_seq";
			$query							= "begin execute immediate 'drop sequence $schema.$seq'; exception when others then null; end;";
			#error_log($query);
			$result							= db_query_install( $query, $dbh );
		}
		
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
}


function dropPostgresDatabaseTables( $dbh ) {
	
	global $DBTABLES;
	
	$error									= 0;
	$tMessage								= "";
	
	foreach( $DBTABLES as $dbtable ) {
		
		if( $error == 0 ) {
			
			$query							= "DROP TABLE IF EXISTS $dbtable CASCADE";
			$result							= db_query_install( $query, $dbh );
			$seq							= $dbtable."_id_seq";
			$query							= "DROP SEQUENCE IF EXISTS $seq CASCADE";
			$result							= db_query_install( $query, $dbh );
		}
		
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
}


function createDatabaseTables( $dbh, $charset, $collation, $dbtype, $schema, $tablespace, $dbuser ) {
	
	$error									= 0;
	$tMessage								= "";
	$query									= "SET client_encoding = '$charset'";
	$result									= db_query_install( $query, $dbh );
	$query									= "SET standard_conforming_strings = off";
	$result									= db_query_install( $query, $dbh );
	$query									= "SET check_function_bodies = false";
	$result									= db_query_install( $query, $dbh );
	$query									= "SET client_min_messages = warning";
	$result									= db_query_install( $query, $dbh );
	$query									= "SET escape_string_warning = off";
	$result									= db_query_install( $query, $dbh );
	if( $schema != "" ) {
		$query								= "SET search_path = '$schema'";
	} else {
		$query								= "SET search_path = ";
	}
	$result									= db_query_install( $query, $dbh );
	$query									= "SET default_tablespace = '$tablespace'";
	$result									= db_query_install( $query, $dbh );
	$query									= "SET default_with_oids = false;";
	$result									= db_query_install( $query, $dbh );
	
	// Table help
	$query									= "CREATE TABLE help (id bigint NOT NULL, 
												    topic character varying(255) NOT NULL,
												    headline_en character varying(255) NOT NULL,
												    headline_de character varying(255) NOT NULL,
												    helptext_de text NOT NULL,
												    helptext_en text NOT NULL
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.help OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE help IS 'Table of help texts';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE help_id_seq
    												START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.help_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE help_id_seq OWNED BY help.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE help ALTER COLUMN id SET DEFAULT nextval('help_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY help ADD CONSTRAINT help_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX help_topic_idx ON help USING btree (topic);";
	$result									= db_query_install( $query, $dbh );
	
	// Table log
	$query									= "CREATE TABLE log (
												    id bigint NOT NULL,
												    \"logtimestamp\" character varying(14) NOT NULL,
												    username character varying(255) NOT NULL,
												    ipaddress character varying(15) NOT NULL,
												    logmessage text NOT NULL
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.log OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE log IS 'Table of log messages';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE log_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );												    
	$query									= "ALTER TABLE $schema.log_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE log_id_seq OWNED BY log.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );	
	$query									= "ALTER TABLE ONLY log ADD CONSTRAINT log_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX log_timestamp_idx ON log USING btree (\"logtimestamp\");";
	$result									= db_query_install( $query, $dbh );
	
	// Table preferences
	$query									= "CREATE TABLE preferences (
												    id bigint NOT NULL,
												    user_id integer NOT NULL,
												    page_size integer NOT NULL,
												    user_sort_fields character varying(255) NOT NULL,
												    user_sort_order character varying(255) NOT NULL,
												    created character varying(14) NOT NULL  DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT ''
												);";
	$result									= db_query_install( $query, $dbh );												
	$query									= "ALTER TABLE $schema.preferences OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE preferences IS 'Table of user preferences';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE preferences_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );										
	$query									= "ALTER TABLE $schema.preferences_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE preferences_id_seq OWNED BY preferences.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE preferences ALTER COLUMN id SET DEFAULT nextval('preferences_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY preferences ADD CONSTRAINT preferences_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX preferences_user_id_idx ON preferences USING btree (user_id);";
	$result									= db_query_install( $query, $dbh );	
	
	// Table rights
	$query									= "CREATE TABLE rights (
												    id bigint NOT NULL,
												    right_name character varying(255) NOT NULL,
												    description_en character varying(255) NOT NULL,
												    description_de character varying(255) NOT NULL,
												    allowed_action character varying DEFAULT 'none'::character varying NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT rights_allowed_action_check CHECK (((allowed_action)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'edit'::character varying, 'delete'::character varying])::text[])))
												);";							   
	$result									= db_query_install( $query, $dbh ); 
	$query									= "ALTER TABLE $schema.rights OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE rights IS 'Table of rights to grant to users';";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE rights_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.rights_id_seq OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE rights_id_seq OWNED BY rights.id;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE rights ALTER COLUMN id SET DEFAULT nextval('rights_id_seq'::regclass);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY rights ADD CONSTRAINT rights_pkey PRIMARY KEY (id);";							   
	$result									= db_query_install( $query, $dbh );
	
	// Table workinfo
	$query									= "CREATE TABLE workinfo (
												    id bigint NOT NULL,
												    \"usertimestamp\" timestamp without time zone DEFAULT now() NOT NULL,
												    action character varying(255) NOT NULL,
												    status character varying(255) NOT NULL,
												    type character varying(255) NOT NULL
												);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.workinfo OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE workinfo IS 'table of workinfos';";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE workinfo_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.workinfo_id_seq OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE workinfo_id_seq OWNED BY workinfo.id;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE workinfo ALTER COLUMN id SET DEFAULT nextval('workinfo_id_seq'::regclass);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY workinfo ADD CONSTRAINT workinfo_pkey PRIMARY KEY (id);";							   
	$result									= db_query_install( $query, $dbh );
	
	// Table sessions
	$query									= "CREATE TABLE sessions (
												    session_id character varying(255) NOT NULL,
												    session_expires integer DEFAULT 0 NOT NULL,
												    session_data text,
												    CONSTRAINT sessions_session_expires_check CHECK ((session_expires >= 0))
												);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.sessions OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY sessions ADD CONSTRAINT sessions_pkey PRIMARY KEY (session_id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX sessions_session_expires_idx ON sessions USING btree (session_expires);";		
	$result									= db_query_install( $query, $dbh );
	
	// Table svngroups
	$query									= "CREATE TABLE svngroups (
												    id bigint NOT NULL,
												    groupname character varying(255) NOT NULL,
												    description character varying(255) NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT ''
												);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svngroups OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svngroups IS 'Table of svn user groups';";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svngroups_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svngroups_id_seq OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svngroups_id_seq OWNED BY svngroups.id;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svngroups ALTER COLUMN id SET DEFAULT nextval('svngroups_id_seq'::regclass);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svngroups ADD CONSTRAINT svngroups_pkey PRIMARY KEY (id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svngroups_groupname_idx ON svngroups USING btree (groupname);";							   
	$result									= db_query_install( $query, $dbh );
	
	// Table svnprojects
	$query									= "CREATE TABLE svnprojects (
												    id bigint NOT NULL,
												    repo_id integer NOT NULL,
												    svnmodule character varying(255) NOT NULL,
												    modulepath character varying(255) NOT NULL,
												    description character varying(255) DEFAULT '',
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT svnprojects_repo_id_check CHECK ((repo_id >= 0))
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnprojects OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svnprojects IS 'Table of svn modules';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svnprojects_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnprojects_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svnprojects_id_seq OWNED BY svnprojects.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svnprojects ALTER COLUMN id SET DEFAULT nextval('svnprojects_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svnprojects ADD CONSTRAINT svnprojects_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svnprojects_deleted_idx ON svnprojects USING btree (deleted);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svnprojects_repo_id_idx ON svnprojects USING btree (repo_id);";
	$result									= db_query_install( $query, $dbh );
	
	// Table svnusers
	$query									= "CREATE TABLE svnusers (
												    id bigint NOT NULL,
												    userid character varying(255) NOT NULL,
												    name character varying(255) NOT NULL,
												    givenname character varying(255) NOT NULL,
												    password character varying(255) DEFAULT ''::character varying NOT NULL,
												    passwordexpires smallint DEFAULT 1::smallint NOT NULL,
												    locked smallint DEFAULT 0::smallint NOT NULL,
												    emailaddress character varying(255) DEFAULT ''::character varying NOT NULL,
												    admin character varying(1) DEFAULT 'n'::character varying NOT NULL,
												    user_mode character varying(10) NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    password_modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    superadmin smallint DEFAULT 0::smallint NOT NULL,
												    securityquestion character varying(255) DEFAULT ''::character varying,
												    securityanswer character varying(255) DEFAULT ''::character varying
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnusers OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svnusers IS 'Table of all known users';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svnusers_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnusers_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svnusers_id_seq OWNED BY svnusers.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svnusers ALTER COLUMN id SET DEFAULT nextval('svnusers_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svnusers ADD CONSTRAINT svnusers_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svnusers ADD CONSTRAINT svnusers_userid_key UNIQUE (userid, deleted);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svnusers_deleted_idx ON svnusers USING btree (deleted);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svnusers_locked_idx ON svnusers USING btree (locked);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svnusers_passwordexpires_idx ON svnusers USING btree (passwordexpires);";
	$result									= db_query_install( $query, $dbh );
	
	
	// Table svn_access_rights				   
	$query									= "CREATE TABLE svn_access_rights (
												    id bigint NOT NULL,
												    project_id integer,
												    user_id integer,
												    group_id integer,
												    path text NOT NULL,
												    valid_from character varying(14) NOT NULL DEFAULT '00000000000000',
												    valid_until character varying(14) NOT NULL DEFAULT '99999999999999',
												    access_right character varying DEFAULT 'none'::character varying NOT NULL,
												    recursive character varying DEFAULT 'yes'::character varying NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT svn_access_rights_access_right_check CHECK (((access_right)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'write'::character varying])::text[]))),
												    CONSTRAINT svn_access_rights_recursive_check CHECK (((recursive)::text = ANY ((ARRAY['yes'::character varying, 'no'::character varying])::text[])))
												);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_access_rights OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svn_access_rights IS 'Table of user or group access rights';";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON COLUMN svn_access_rights.valid_from IS 'JHJJMMTT';";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON COLUMN svn_access_rights.valid_until IS 'JHJJMMTT';";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svn_access_rights_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_access_rights_id_seq OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svn_access_rights_id_seq OWNED BY svn_access_rights.id;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svn_access_rights ALTER COLUMN id SET DEFAULT nextval('svn_access_rights_id_seq'::regclass);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_pkey PRIMARY KEY (id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_access_rights_deleted_idx ON svn_access_rights USING btree (deleted);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_access_rights_group_id_idx ON svn_access_rights USING btree (group_id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_access_rights_path_idx ON svn_access_rights USING btree (path);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_access_rights_project_id_idx ON svn_access_rights USING btree (project_id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_access_rights_user_id_idx ON svn_access_rights USING btree (user_id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_group_id_fkey FOREIGN KEY (group_id) REFERENCES svngroups(id) ON UPDATE RESTRICT ON DELETE CASCADE;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_project_id_fkey FOREIGN KEY (project_id) REFERENCES svnprojects(id) ON UPDATE RESTRICT ON DELETE CASCADE;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_user_id_fkey FOREIGN KEY (user_id) REFERENCES svnusers(id) ON UPDATE RESTRICT ON DELETE CASCADE;";							   
	$result									= db_query_install( $query, $dbh );
	
	// Table svn_groups_responsible
	$query									= "CREATE TABLE svn_groups_responsible (
												    id bigint NOT NULL,
												    user_id integer NOT NULL,
												    group_id integer NOT NULL,
												    allowed character varying DEFAULT 'none'::character varying NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT svn_groups_responsible_allowed_check CHECK (((allowed)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'edit'::character varying, 'delete'::character varying])::text[]))),
												    CONSTRAINT svn_groups_responsible_group_id_check CHECK ((group_id >= 0)),
												    CONSTRAINT svn_groups_responsible_user_id_check CHECK ((user_id >= 0))
												);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_groups_responsible OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svn_groups_responsible_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_groups_responsible_id_seq OWNER TO $dbuser;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svn_groups_responsible_id_seq OWNED BY svn_groups_responsible.id;";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svn_groups_responsible ALTER COLUMN id SET DEFAULT nextval('svn_groups_responsible_id_seq'::regclass);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_groups_responsible ADD CONSTRAINT svn_groups_responsible_pkey PRIMARY KEY (id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_groups_responsible_1_idx ON svn_groups_responsible USING btree (user_id, group_id);";							   
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_groups_responsible_deleted_idx ON svn_groups_responsible USING btree (deleted);";							   
	$result									= db_query_install( $query, $dbh );
	
	// Table svn_projects_mailinglists
	$query									= "CREATE TABLE svn_projects_mailinglists (
												    id bigint NOT NULL,
												    project_id integer NOT NULL,
												    mailinglisten_id integer NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT svn_projects_mailinglists_mailinglisten_id_check CHECK ((mailinglisten_id >= 0)),
												    CONSTRAINT svn_projects_mailinglists_project_id_check CHECK ((project_id >= 0))
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_projects_mailinglists OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svn_projects_mailinglists IS 'Table of modules and mailinglist relations';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svn_projects_mailinglists_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_projects_mailinglists_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svn_projects_mailinglists_id_seq OWNED BY svn_projects_mailinglists.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svn_projects_mailinglists ALTER COLUMN id SET DEFAULT nextval('svn_projects_mailinglists_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_projects_mailinglists ADD CONSTRAINT svn_projects_mailinglists_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_projects_mailinglists_1_idx ON svn_projects_mailinglists USING btree (project_id, mailinglisten_id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_projects_mailinglists_mailinglisten_id_idx ON svn_projects_mailinglists USING btree (mailinglisten_id);";
	$result									= db_query_install( $query, $dbh );
	
	// Table svn_projects_responsible
	$query									= "CREATE TABLE svn_projects_responsible (
												    id bigint NOT NULL,
												    project_id integer NOT NULL,
												    user_id integer NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT ''
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_projects_responsible OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svn_projects_responsible IS 'Table of project responsible users';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svn_projects_responsible_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_projects_responsible_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svn_projects_responsible_id_seq OWNED BY svn_projects_responsible.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svn_projects_responsible ALTER COLUMN id SET DEFAULT nextval('svn_projects_responsible_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_projects_responsible ADD CONSTRAINT svn_projects_responsible_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_projects_responsible_deleted_idx ON svn_projects_responsible USING btree (deleted);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_projects_responsible_project_id_idx ON svn_projects_responsible USING btree (project_id);";
	$result									= db_query_install( $query, $dbh );
	
	// Table svn_users_groups
	$query									= "CREATE TABLE svn_users_groups (
												    id bigint NOT NULL,
												    user_id integer NOT NULL,
												    group_id integer NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT svn_users_groups_group_id_check CHECK ((group_id >= 0)),
												    CONSTRAINT svn_users_groups_user_id_check CHECK ((user_id >= 0))
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_users_groups OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svn_users_groups IS 'Table of user group relations';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svn_users_groups_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svn_users_groups_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svn_users_groups_id_seq OWNED BY svn_users_groups.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svn_users_groups ALTER COLUMN id SET DEFAULT nextval('svn_users_groups_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svn_users_groups ADD CONSTRAINT svn_users_groups_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_users_groups_deleted_idx ON svn_users_groups USING btree (deleted);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_users_groups_group_id_idx ON svn_users_groups USING btree (group_id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svn_users_groups_user_id_idx ON svn_users_groups USING btree (user_id);";
	$result									= db_query_install( $query, $dbh );
	
	// Table svnmailinglists
	$query									= "CREATE TABLE svnmailinglists (
												    id bigint NOT NULL,
												    mailinglist character varying(255) NOT NULL,
												    emailaddress character varying(255) NOT NULL,
												    description text NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT ''
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnmailinglists OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svnmailinglists IS 'Table of available svn mailing lists';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svnmailinglists_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnmailinglists_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svnmailinglists_id_seq OWNED BY svnmailinglists.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svnmailinglists ALTER COLUMN id SET DEFAULT nextval('svnmailinglists_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svnmailinglists ADD CONSTRAINT svnmailinglists_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	
	// Table svnpasswordreset
	$query									= "CREATE TABLE svnpasswordreset (
												    id bigint NOT NULL,
												    unixtime integer NOT NULL,
												    username character varying(255) NOT NULL,
												    token character varying(255) NOT NULL,
												    idstr character varying(255) NOT NULL
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnpasswordreset OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svnpasswordreset_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnpasswordreset_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svnpasswordreset_id_seq OWNED BY svnpasswordreset.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svnpasswordreset ALTER COLUMN id SET DEFAULT nextval('svnpasswordreset_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svnpasswordreset ADD CONSTRAINT svnpasswordreset_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	
	// Table svnrepos
	$query									= "CREATE TABLE svnrepos (
												    id bigint NOT NULL,
												    reponame character varying(255) NOT NULL,
												    repopath character varying(255) NOT NULL,
												    repouser character varying(255) NOT NULL,
												    repopassword character varying(255) NOT NULL,
												    different_auth_files smallint DEFAULT 0::smallint NOT NULL,
												    auth_user_file character varying(255) NOT NULL,
												    svn_access_file character varying(255) NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT ''
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnrepos OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE svnrepos IS 'Table of svn repositories';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE svnrepos_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.svnrepos_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE svnrepos_id_seq OWNED BY svnrepos.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE svnrepos ALTER COLUMN id SET DEFAULT nextval('svnrepos_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY svnrepos ADD CONSTRAINT svnrepos_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX svnrepos_deleted_idx ON svnrepos USING btree (deleted);";
	$result									= db_query_install( $query, $dbh );
	
	// Table users_rights
	$query									= "CREATE TABLE users_rights (
												    id bigint NOT NULL,
												    user_id integer NOT NULL,
												    right_id integer NOT NULL,
												    allowed character varying DEFAULT 'none'::character varying NOT NULL,
												    created character varying(14) NOT NULL DEFAULT '00000000000000',
												    created_user character varying(255) DEFAULT '',
												    modified character varying(14) NOT NULL DEFAULT '00000000000000',
												    modified_user character varying(255) DEFAULT '',
												    deleted character varying(14) NOT NULL DEFAULT '00000000000000',
												    deleted_user character varying(255) DEFAULT '',
												    CONSTRAINT users_rights_allowed_check CHECK (((allowed)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'add'::character varying, 'edit'::character varying, 'delete'::character varying])::text[])))
												);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.users_rights OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "COMMENT ON TABLE users_rights IS 'Table of granted user rights';";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE SEQUENCE users_rights_id_seq
												    START WITH 1
												    INCREMENT BY 1
												    NO MAXVALUE
												    NO MINVALUE
												    CACHE 1;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE $schema.users_rights_id_seq OWNER TO $dbuser;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER SEQUENCE users_rights_id_seq OWNED BY users_rights.id;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE users_rights ALTER COLUMN id SET DEFAULT nextval('users_rights_id_seq'::regclass);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY users_rights ADD CONSTRAINT users_rights_pkey PRIMARY KEY (id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX users_rights_right_id_idx ON users_rights USING btree (right_id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "CREATE INDEX users_rights_user_id_idx ON users_rights USING btree (user_id);";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY users_rights ADD CONSTRAINT users_rights_right_id_fkey FOREIGN KEY (right_id) REFERENCES rights(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
	$result									= db_query_install( $query, $dbh );
	$query									= "ALTER TABLE ONLY users_rights ADD CONSTRAINT users_rights_user_id_fkey FOREIGN KEY (user_id) REFERENCES svnusers(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
	$result									= db_query_install( $query, $dbh );
	
	
}


function createOracleDatabaseTables( $dbh, $tDatabaseCharset, $tDatabaseCollation, $tDatabase, $schema, $tDatabaseTablespace, $tDatabaseUser ) {

	$error									= 0;
	$tMessage								= "";
	
	$query									= "CREATE SEQUENCE $schema.HELP_SEQ MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"LOG_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"PREFERENCES_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"RIGHTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVN_ACCESS_RIGHTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVN_GROUPS_RESPONSIBLE_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVN_PROJECTS_MAILINGLISTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVN_PROJECTS_RESPONSIBLE_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVN_USERS_GROUPS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVNGROUPS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVNMAILINGLISTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVNPASSWORDRESET_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVNPROJECTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVNREPOS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"SVNUSERS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"USERS_RIGHTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE SEQUENCE $schema.\"WORKINFO_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE TABLE $schema.HELP
												  (
												    \"ID\"          NUMBER(*,0) NOT NULL ENABLE,
												    \"TOPIC\"       VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"HEADLINE_EN\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"HEADLINE_DE\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"HELPTEXT_DE\" CLOB NOT NULL ENABLE,
												    \"HELPTEXT_EN\" CLOB NOT NULL ENABLE,
												    CONSTRAINT \"HELP_PK\" PRIMARY KEY (\"ID\") ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.HELP IS 'Table of help texts'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.HELP_TOPIC_IDX ON $schema.HELP
												    (
												      \"TOPIC\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.HELP_TRG BEFORE
												  INSERT ON $schema.HELP FOR EACH ROW BEGIN IF :NEW.ID IS NULL THEN
												  SELECT HELP_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.HELP_TRG ENABLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE TABLE $schema.LOG
												  (
												    \"ID\" NUMBER(*,0) NOT NULL ENABLE,
												    \"LOGTIMESTAMP\" VARCHAR2(14 BYTE) NOT NULL ENABLE,
												    \"USERNAME\"  VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"IPADDRESS\" VARCHAR2(15 BYTE) NOT NULL ENABLE,
												    \"LOGMESSAGE\" CLOB NOT NULL ENABLE,
												    CONSTRAINT LOG_PK PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.LOG IS 'Table of log messages'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.LOG_TIMESTAMP_IDX ON $schema.LOG
												    (
												      \"LOGTIMESTAMP\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.LOG_TRG BEFORE
												  INSERT ON $schema.LOG FOR EACH ROW BEGIN
												  SELECT LOG_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.LOG_TRG ENABLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE TABLE $schema.\"PREFERENCES\"
												  (
												    \"ID\"               NUMBER(*,0) NOT NULL ENABLE,
												    \"USER_ID\"          NUMBER(*,0) NOT NULL ENABLE,
												    \"PAGE_SIZE\"        NUMBER(*,0) NOT NULL ENABLE,
												    \"USER_SORT_FIELDS\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"USER_SORT_ORDER\"  VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"CREATED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"     VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"         VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\"    VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"     VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"PREFERENCES_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"PREFERENCES\" IS 'Table of user preferences'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"PREFERENCES_USER_ID_IDX\" ON $schema.\"PREFERENCES\"
												    (
												      \"USER_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"PREFERENCES_TRG\" BEFORE
												  INSERT ON $schema.PREFERENCES FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN
												  SELECT PREFERENCES_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"PREFERENCES_TRG\" ENABLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE TABLE $schema.\"WORKINFO\"
												  (
												    \"ID\" NUMBER(*,0) NOT NULL ENABLE,
												    \"USERTIMESTAMP\" TIMESTAMP (6) DEFAULT CURRENT_TIMESTAMP NOT NULL ENABLE,
												    \"ACTION\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"STATUS\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"TYPE\"   VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    CONSTRAINT \"WORKINFO_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"WORKINFO\" IS 'Table of workinfos'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"WORKINFO_TRG\" BEFORE
												  INSERT ON $schema.WORKINFO FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT WORKINFO_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"WORKINFO_TRG\" ENABLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE TABLE $schema.\"RIGHTS\"
												  (
												    \"ID\"             NUMBER(*,0) NOT NULL ENABLE,
												    \"RIGHT_NAME\"     VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"DESCRIPTION_EN\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"DESCRIPTION_DE\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"ALLOWED_ACTION\" VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE,
												    \"CREATED\"        VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"   VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\"  VARCHAR2(155 BYTE) DEFAULT '',
												    \"DELETED\"        VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"   VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"RIGHTS_PK\" PRIMARY KEY (\"ID\")  ENABLE,
												    CONSTRAINT \"RIGHTS_ALLOWED_ACTION_CHECK\" CHECK (ALLOWED_ACTION = 'none'
												  OR ALLOWED_ACTION                                                = 'read'
												  OR ALLOWED_ACTION                                                = 'edit'
												  OR ALLOWED_ACTION                                                = 'delete') ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"RIGHTS\" IS 'Table of rights to grant to users'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"RIGHTS_TRG\" BEFORE
												  INSERT ON $schema.RIGHTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT RIGHTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"RIGHTS_TRG\" ENABLE";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE TABLE $schema.\"SESSIONS\"
												  (
												    \"SESSION_ID\"      VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"SESSION_EXPIRES\" NUMBER(*,0) DEFAULT 0 NOT NULL ENABLE,
												    \"SESSION_DATA\" CLOB,
												    CONSTRAINT \"SESSIONS_SESSION_EXPIRES_CHECK\" CHECK (SESSION_EXPIRES >= 0) ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SESSIONS\" IS 'Table of session information'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SESSIONS_SESSION_EXPIRES_IDX\" ON $schema.\"SESSIONS\"
												    (
												      \"SESSION_EXPIRES\"
												    )";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVNGROUPS\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"GROUPNAME\"     VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"DESCRIPTION\"   VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVNGROUPS_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVNGROUPS\" IS 'Table of svn user groups'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNGROUPS_GROUPNAME_IDX\" ON $schema.\"SVNGROUPS\"
												    (
												      \"GROUPNAME\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVNGROUPS_TRG\" BEFORE
												  INSERT ON $schema.SVNGROUPS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNGROUPS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVNGROUPS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVNMAILINGLISTS\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"MAILINGLIST\"   VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"EMAILADDRESS\"  VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"DESCRIPTION\"   VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVNMAILINGLISTS_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVNMAILINGLISTS\" IS 'Table of available svn mailing lists'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVNMAILINGLISTS_TRG\" BEFORE
												  INSERT ON $schema.SVNMAILINGLISTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNMAILINGLISTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVNMAILINGLISTS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVNPASSWORDRESET\"
												  (
												    \"ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"UNIXTIME\" NUMBER(*,0) NOT NULL ENABLE,
												    \"USERNAME\" VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"TOKEN\"    VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"IDSTR\"    VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    CONSTRAINT \"SVNPASSWORDRESET_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
  	$result									= db_query_install( $query, $dbh );
  	
	$query									= "COMMENT ON TABLE $schema.\"SVNPASSWORDRESET\" IS 'Table with password reset information'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVNPASSWORDRESET_TRG\" BEFORE
												  INSERT ON $schema.SVNPASSWORDRESET FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNPASSWORDRESET_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVNPASSWORDRESET_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVNREPOS\"
												  (
												    \"ID\"                   NUMBER(*,0) NOT NULL ENABLE,
												    \"REPONAME\"             VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"REPOPATH\"             VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"REPOUSER\"             VARCHAR2(255 BYTE) DEFAULT '',
												    \"REPOPASSWORD\"         VARCHAR2(255 BYTE) DEFAULT '',
												    \"DIFFERENT_AUTH_FILES\" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
												    \"AUTH_USER_FILE\"       VARCHAR2(255 BYTE) DEFAULT '',
												    \"SVN_ACCESS_FILE\"      VARCHAR2(255 BYTE) DEFAULT '',
												    \"CREATED\"              VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"         VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"             VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\"        VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"              VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"         VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVNREPOS_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVNREPOS\" IS 'Table of svn repositories'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNREPOS_DELETED_IDX\" ON $schema.\"SVNREPOS\"
												    (
												      \"DELETED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVNREPOS_TRG\" BEFORE
												  INSERT ON $schema.SVNREPOS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNREPOS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVNREPOS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVNPROJECTS\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"REPO_ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"SVNMODULE\"     VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"MODULEPATH\"    VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"DESCRIPTION\"   VARCHAR2(255 BYTE) DEFAULT '',
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVNPROJECTS_PK\" PRIMARY KEY (\"ID\")  ENABLE,
												    CONSTRAINT \"SVNPROJECTS_REPO_ID_CHECK\" CHECK (REPO_ID >= 0) ENABLE,
												    CONSTRAINT \"SVNPROJECTS_REPO_ID_FK\" FOREIGN KEY (\"REPO_ID\") REFERENCES $schema.\"SVNREPOS\" (\"ID\") ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVNPROJECTS\" IS 'Table of svn modules'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNPROJECTS_DELETED_IDX\" ON $schema.\"SVNPROJECTS\"
												    (
												      \"DELETED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNPROJECTS_REPO_ID_IDX\" ON $schema.\"SVNPROJECTS\"
												    (
												      \"REPO_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVNPROJECTS_TRG\" BEFORE
												  INSERT ON $schema.SVNPROJECTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNPROJECTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVNPROJECTS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.SVNUSERS
												  (
												    \"ID\"                NUMBER(*,0) NOT NULL ENABLE,
												    \"USERID\"            VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"NAME\"              VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"GIVENNAME\"         VARCHAR2(255 BYTE) NOT NULL ENABLE,
												    \"PASSWORD\"          VARCHAR2(255 BYTE) DEFAULT '',
												    \"PASSWORDEXPIRES\"   NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE,
												    \"LOCKED\"            NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
												    \"EMAILADDRESS\"      VARCHAR2(255 BYTE) DEFAULT '',
												    \"ADMIN\"             VARCHAR2(1 BYTE) DEFAULT 'n' NOT NULL ENABLE,
												    \"USER_MODE\"         VARCHAR2(10 BYTE) NOT NULL ENABLE,
												    \"CREATED\"           VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"      VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\"     VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"           VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"      VARCHAR2(255 BYTE) DEFAULT '',
												    \"PASSWORD_MODIFIED\" VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"SUPERADMIN\"        NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE,
												    \"SECURITYQUESTION\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"SECURITYANSWER\"    VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT SVNUSERS_PK PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.SVNUSERS IS 'Table of all known users'";
	$result									= db_query_install( $query, $dbh );

	$query									= "CREATE INDEX $schema.\"SVNUSERS_DELETED_IDX\" ON $schema.\"SVNUSERS\"
												    (
												      \"DELETED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNUSERS_LOCKED_IDX\" ON $schema.\"SVNUSERS\"
												    (
												      \"LOCKED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNUSERS_PASSWORDEXPIRES_IDX\" ON $schema.\"SVNUSERS\"
												    (
												      \"PASSWORDEXPIRES\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVNUSERS_TRG\" BEFORE
												  INSERT ON $schema.SVNUSERS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNUSERS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVNUSERS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVN_ACCESS_RIGHTS\"
												  (
												    \"ID\"         NUMBER(*,0) NOT NULL ENABLE,
												    \"PROJECT_ID\" NUMBER(*,0),
												    \"USER_ID\"    NUMBER(*,0),
												    \"GROUP_ID\"   NUMBER(*,0),
												    \"PATH\" VARCHAR2(4000) NOT NULL ENABLE,
												    \"VALID_FROM\"    VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"VALID_UNTIL\"   VARCHAR2(14 BYTE) DEFAULT '99999999999999' NOT NULL ENABLE,
												    \"ACCESS_RIGHT\"  VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE,
												    \"RECURSIVE\"     VARCHAR2(255 BYTE) DEFAULT 'yes' NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVN_ACCESS_RIGHTS_PK\" PRIMARY KEY (\"ID\")  ENABLE,
												    CONSTRAINT \"SVN_ACCESS_RECURSIVE_CHECK\" CHECK (RECURSIVE        = 'yes'
												  OR RECURSIVE                                                      = 'no') ENABLE,
												    CONSTRAINT \"SVN_ACCESS_RIGHTS_ACCESS_CHECK\" CHECK (ACCESS_RIGHT = 'none'
												  OR ACCESS_RIGHT                                                   = 'read'
												  OR ACCESS_RIGHT                                                   = 'write') ENABLE,
												    CONSTRAINT \"SVN_ACCESS_RIGHTS_SVNGROU_FK1\" FOREIGN KEY (\"GROUP_ID\") REFERENCES $schema.\"SVNGROUPS\" (\"ID\") ON
												  DELETE CASCADE ENABLE,
												    CONSTRAINT \"SVN_ACCESS_RIGHTS_SVNPROJ_FK1\" FOREIGN KEY (\"PROJECT_ID\") REFERENCES $schema.\"SVNPROJECTS\" (\"ID\") ON
												  DELETE CASCADE ENABLE,
												    CONSTRAINT \"SVN_ACCESS_RIGHTS_SVNUSER_FK1\" FOREIGN KEY (\"USER_ID\") REFERENCES $schema.\"SVNUSERS\" (\"ID\") ON
												  DELETE CASCADE ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVN_ACCESS_RIGHTS\" IS 'Table of user or group access rights'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVNACCESSRIGHTSPROJECTID_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\"
												    (
												      \"PROJECT_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVN_ACCESS_RIGHTS_DELETED_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\"
												    (
												      \"DELETED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVN_ACCESS_RIGHTS_GROUP_ID_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\"
												    (
												      \"GROUP_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVN_ACCESS_RIGHTS_USER_ID_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\"
												    (
												      \"USER_ID\"
												    )";
    $result									= db_query_install( $query, $dbh );
    
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVN_ACCESS_RIGHTS_TRG\" BEFORE
												  INSERT ON $schema.SVN_ACCESS_RIGHTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_ACCESS_RIGHTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVN_ACCESS_RIGHTS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVN_GROUPS_RESPONSIBLE\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"GROUP_ID\"      NUMBER(*,0) NOT NULL ENABLE,
												    \"ALLOWED\"       VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVN_GROUPS_RESPONSIBLE_PK\" PRIMARY KEY (\"ID\")  ENABLE,
												    CONSTRAINT \"ALLOWED_CHECK\" CHECK (ALLOWED    = 'none'
												  OR ALLOWED                                     = 'read'
												  OR ALLOWED                                     = 'edit'
												  OR ALLOWED                                     = 'delete') ENABLE,
												    CONSTRAINT \"GROUP_ID_CHECK\" CHECK (GROUP_ID >= 0) ENABLE,
												    CONSTRAINT \"USER_ID_CHECK\" CHECK (USER_ID   >= 0) ENABLE,
												    CONSTRAINT \"SVN_GROUPS_RESPONSIBLE_SV_FK1\" FOREIGN KEY (\"USER_ID\") REFERENCES $schema.\"SVNUSERS\" (\"ID\") ON
												  DELETE CASCADE ENABLE,
												    CONSTRAINT \"SVN_GROUPS_RESPONSIBLE_SV_FK2\" FOREIGN KEY (\"GROUP_ID\") REFERENCES $schema.\"SVNGROUPS\" (\"ID\") ON
												  DELETE CASCADE ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVN_GROUPS_RESPONSIBLE\" IS 'Table of group responsible people'";
  	$result									= db_query_install( $query, $dbh );
  	
	$query									= "CREATE INDEX $schema.\"DELETED_IDX\" ON $schema.\"SVN_GROUPS_RESPONSIBLE\"
												    (
												      \"DELETED\"
												    )";
    $result									= db_query_install( $query, $dbh );
    
	$query									= "CREATE INDEX $schema.\"SVN_GROUPS_RESPONSIBLE_1_IDX\" ON $schema.\"SVN_GROUPS_RESPONSIBLE\"
												    (
												      \"USER_ID\",
												      \"GROUP_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVN_GROUPS_RESPONSIBLE_TRG\" BEFORE
												  INSERT ON $schema.SVN_GROUPS_RESPONSIBLE FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_GROUPS_RESPONSIBLE_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVN_GROUPS_RESPONSIBLE_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVN_PROJECTS_MAILINGLISTS\"
												  (
												    \"ID\"               NUMBER(*,0) NOT NULL ENABLE,
												    \"PROJECT_ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"MAILINGLISTEN_ID\" NUMBER(*,0) NOT NULL ENABLE,
												    \"CREATED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"     VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"         VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\"    VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETD_USER\"      VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVN_PROJECTS_MAILINGLISTS_PK\" PRIMARY KEY (\"ID\")  ENABLE,
												    CONSTRAINT \"SVN_PROJECTS_MAILINGLISTS_FK1\" FOREIGN KEY (\"PROJECT_ID\") REFERENCES $schema.\"SVNPROJECTS\" (\"ID\") ON
												  DELETE CASCADE ENABLE,
												    CONSTRAINT \"SVN_PROJECTS_MAILINGLISTS_FK2\" FOREIGN KEY (\"MAILINGLISTEN_ID\") REFERENCES $schema.\"SVNMAILINGLISTS\" (\"ID\") ON
												  DELETE CASCADE ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVN_PROJECTS_MAILINGLISTS\" IS 'Table of modules and mailinglist relations'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"MAILINGLISTEN_ID_IDX\" ON $schema.\"SVN_PROJECTS_MAILINGLISTS\"
												    (
												      \"MAILINGLISTEN_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"MAILINGLISTS_1_IDX\" ON $schema.\"SVN_PROJECTS_MAILINGLISTS\"
												    (
												      \"PROJECT_ID\",
												      \"MAILINGLISTEN_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVN_PROJECTS_MAILINGLISTS_TRG\" BEFORE
												  INSERT ON $schema.SVN_PROJECTS_MAILINGLISTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_PROJECTS_MAILINGLISTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVN_PROJECTS_MAILINGLISTS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVN_PROJECTS_RESPONSIBLE\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"PROJECT_ID\"    NUMBER(*,0) NOT NULL ENABLE,
												    \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVN_PROJECTS_RESPONSIBLE_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVN_PROJECTS_RESPONSIBLE\" IS 'Table of project responsible users'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"PRJ_RESP_DELETED_IDX\" ON $schema.\"SVN_PROJECTS_RESPONSIBLE\"
												    (
												      \"DELETED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"PRJ_RESP_PROJECT_ID_IDX\" ON $schema.\"SVN_PROJECTS_RESPONSIBLE\"
												    (
												      \"PROJECT_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVN_PROJECTS_RESPONSIBLE_TRG\" BEFORE
												  INSERT ON $schema.SVN_PROJECTS_RESPONSIBLE FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_PROJECTS_RESPONSIBLE_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVN_PROJECTS_RESPONSIBLE_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"SVN_USERS_GROUPS\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"GROUP_ID\"      NUMBER(*,0) NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"SVN_USERS_GROUPS_PK\" PRIMARY KEY (\"ID\")  ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"SVN_USERS_GROUPS\" IS 'Table of user group relations'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVN_USERS_GROUPS_DELETED_IDX\" ON $schema.\"SVN_USERS_GROUPS\"
												    (
												      \"DELETED\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVN_USERS_GROUPS_GROUP_ID_IDX\" ON $schema.\"SVN_USERS_GROUPS\"
												    (
												      \"GROUP_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"SVN_USERS_GROUPS_USER_ID_IDX\" ON $schema.\"SVN_USERS_GROUPS\"
												    (
												      \"USER_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"SVN_USERS_GROUPS_TRG\" BEFORE
												  INSERT ON $schema.SVN_USERS_GROUPS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_USERS_GROUPS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"SVN_USERS_GROUPS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
    $query									= "CREATE TABLE $schema.\"USERS_RIGHTS\"
												  (
												    \"ID\"            NUMBER(*,0) NOT NULL ENABLE,
												    \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE,
												    \"RIGHT_ID\"      NUMBER(*,0) NOT NULL ENABLE,
												    \"ALLOWED\"       VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE,
												    \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '',
												    \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,
												    \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',
												    CONSTRAINT \"USERS_RIGHTS_PK\" PRIMARY KEY (\"ID\")  ENABLE,
												    CONSTRAINT \"USERS_RIGHTS_ALLOWED_CHECK\" CHECK (ALLOWED = 'none'
												  OR ALLOWED                                               = 'read'
												  OR ALLOWED                                               = 'add'
												  OR ALLOWED                                               = 'edit'
												  OR ALLOWED                                               = 'delete') ENABLE,
												    CONSTRAINT \"USERS_RIGHTS_RIGHT_ID_FKEY\" FOREIGN KEY (\"RIGHT_ID\") REFERENCES $schema.\"RIGHTS\" (\"ID\") ON
												  DELETE CASCADE ENABLE,
												    CONSTRAINT \"USERS_RIGHTS_USER_ID_FKEY\" FOREIGN KEY (\"USER_ID\") REFERENCES $schema.\"SVNUSERS\" (\"ID\") ON
												  DELETE CASCADE ENABLE
												  )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "COMMENT ON TABLE $schema.\"USERS_RIGHTS\" IS 'Table of granted user rights'";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"USERS_RIGHTS_RIGHT_ID_IDX\" ON $schema.\"USERS_RIGHTS\"
												    (
												      \"RIGHT_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE INDEX $schema.\"USERS_RIGHTS_USER_ID_IDX\" ON $schema.\"USERS_RIGHTS\"
												    (
												      \"USER_ID\"
												    )";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "CREATE OR REPLACE TRIGGER $schema.\"USERS_RIGHTS_TRG\" BEFORE
												  INSERT ON $schema.USERS_RIGHTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT USERS_RIGHTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
	$result									= db_query_install( $query, $dbh );
	
	$query									= "ALTER TRIGGER $schema.\"USERS_RIGHTS_TRG\" ENABLE";
    $result									= db_query_install( $query, $dbh );
    
	$ret									= array();
	
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	#error_log( $error." - ". $tMessage );
	
	return $ret;	
}


function createMySQLDatabaseTables( $dbh, $charset, $collation ) {
	
	$error									= 0;
	$tMessage								= "";
	
	$query									= "CREATE TABLE IF NOT EXISTS `log` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`logtimestamp` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`username` varchar(255) NOT NULL,
  													`ipaddress` varchar(15) NOT NULL,
  													`logmessage` longtext NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_timestamp` (`logtimestamp`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of log messages';";
	$result									= db_query_install( $query, $dbh );
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `preferences` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`user_id` int(10) NOT NULL,
  													`page_size` int(4) NOT NULL,
  													`user_sort_fields` varchar(255) NOT NULL,
  													`user_sort_order` varchar(255) NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_userid` (`user_id`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of user preferences';";
		$result								= db_query_install( $query, $dbh );
		
	}

	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `rights` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`right_name` varchar(255) NOT NULL,
  													`description_en` varchar(255) NOT NULL,
  													`description_de` varchar(255) NOT NULL,
  													`allowed_action` enum('none','read','edit','delete') NOT NULL default 'none',
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of rights to grant to users';";
		$result								= db_query_install( $query, $dbh );
		
	}

	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `sessions` (
  													`session_id` varchar(255) NOT NULL,
  													`session_expires` int(10) unsigned NOT NULL default '0',
  													`session_data` text,
  													PRIMARY KEY  (`session_id`),
  													KEY `idx_expires` (`session_expires`)
												) ENGINE=MyISAM DEFAULT CHARSET=$charset COLLATE=$collation;";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_access_rights` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`project_id` int(10) NOT NULL,
  													`user_id` int(10) NOT NULL,
  													`group_id` int(10) NOT NULL,
  													`path` longtext NOT NULL,
  													`valid_from` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  													`valid_until` varchar(14) NOT NULL COMMENT 'JHJJMMTT',
  													`access_right` enum('none','read','write') NOT NULL default 'none',
  													`recursive` enum('yes','no') NOT NULL default 'yes',
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_projectid` (`project_id`),
  													KEY `idx_userid` (`user_id`),
  													KEY `idx_groupid` (`group_id`),
  													KEY `idx_path` (`path`(512)),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of user or group access rights';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_projects_mailinglists` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`project_id` int(10) unsigned NOT NULL,
  													`mailinglisten_id` int(10) unsigned NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `moduleid` (`project_id`,`mailinglisten_id`),
  													KEY `mailinglistenid` (`mailinglisten_id`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of modules and mailinglist relations';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_projects_responsible` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`project_id` int(10) NOT NULL,
  													`user_id` int(10) NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_projectid` (`project_id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of project responsible users';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svn_users_groups` (
  													`id` int(10) NOT NULL auto_increment,
  													`user_id` int(10) unsigned NOT NULL,
  													`group_id` int(10) unsigned NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_groupid` (`group_id`),
  													KEY `idx_userid` (`user_id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of user group relations';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svngroups` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`groupname` varchar(255) NOT NULL,
  													`description` varchar(255) NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `groupname` (`groupname`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of svn user groups';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnmailinglists` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`mailinglist` varchar(255) NOT NULL,
  													`emailaddress` varchar(255) NOT NULL,
  													`description` mediumtext NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of available svn mailing lists';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnprojects` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`repo_id` int(10) unsigned NOT NULL,
  													`svnmodule` varchar(255) NOT NULL,
  													`modulepath` varchar(255) NOT NULL,
  													`description` varchar(255) NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,	
  													PRIMARY KEY  (`id`),
  													KEY `idx_repoid` (`repo_id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of svn modules';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnrepos` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`reponame` varchar(255) NOT NULL,
  													`repopath` varchar(255) NOT NULL,
  													`repouser` varchar(255) NOT NULL,
  													`repopassword` varchar(255) NOT NULL,
  													`different_auth_files` tinyint(1) NOT NULL DEFAULT '0',
  													`auth_user_file` varchar(255) NOT NULL,
  													`svn_access_file` varchar(255) NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of svn repositories';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnusers` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`userid` varchar(255) NOT NULL,
  													`name` varchar(255) NOT NULL,
  													`givenname` varchar(255) NOT NULL,
  													`password` varchar(255) NOT NULL default '',
  													`passwordexpires` tinyint(1) NOT NULL default '1',
  													`locked` tinyint(1) NOT NULL default '0',
  													`emailaddress` varchar(255) NOT NULL default '',
  													`admin` char(1) NOT NULL default 'n',
  													`user_mode` varchar(10) NOT NULL,
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													`password_modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`superadmin` tinyint(1) NOT NULL default '0',
  													`securityquestion` varchar(255) default '',
  													`securityanswer` varchar(255) default '',
  													PRIMARY KEY  (`id`),
  													UNIQUE KEY `idx_userid` (`userid`,`deleted`),
  													KEY `idx_mode` (`locked`),
  													KEY `idx_passwordexpires` (`passwordexpires`),
  													KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of all known users';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `workinfo` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`usertimestamp` timestamp NOT NULL default CURRENT_TIMESTAMP,
  													`action` varchar(255) NOT NULL,
  													`status` varchar(255) NOT NULL,
  													`type` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='table of workinfo';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `help` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`topic` varchar(255) NOT NULL,
  													`headline_en` varchar(255) NOT NULL,
  													`headline_de` varchar(255) NOT NULL,
  													`helptext_de` longtext NOT NULL,
  													`helptext_en` longtext NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_topic` (`topic`),
  													FULLTEXT KEY `helptext_de` (`helptext_de`),
  													FULLTEXT KEY `helptext_en` (`helptext_en`)
												) ENGINE=MyISAM  DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of help texts';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `users_rights` (
  													`id` int(10) unsigned NOT NULL auto_increment,
  													`user_id` int(10) NOT NULL,
  													`right_id` int(10) NOT NULL,
  													`allowed` enum('none','read','add','edit','delete') NOT NULL default 'none',
  													`created` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`created_user` varchar(255) NOT NULL,
  													`modified` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`modified_user` varchar(255) NOT NULL,
  													`deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
  													`deleted_user` varchar(255) NOT NULL,
  													PRIMARY KEY  (`id`),
  													KEY `idx_user_id` (`user_id`),
  													KEY `idx_right_id` (`right_id`)
												) ENGINE=InnoDB DEFAULT CHARSET=$charset COLLATE=$collation COMMENT='Table of granted user rights';";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE `svn_groups_responsible` (
												  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
												  `user_id` int(10) unsigned NOT NULL,
												  `group_id` int(10) unsigned NOT NULL,
												  `allowed` enum('none','read','edit','delete') NOT NULL DEFAULT 'none',
												  `created` varchar(14) NOT NULL DEFAULT '00000000000000',
												  `created_user` varchar(255) NOT NULL,
												  `modified` varchar(14) NOT NULL DEFAULT '00000000000000',
												  `modified_user` varchar(255) NOT NULL,
												  `deleted` varchar(14) NOT NULL DEFAULT '00000000000000',
												  `deleted_user` varchar(255) NOT NULL,
												  PRIMARY KEY (`id`),
												  KEY `idx_projectid_userid_groupid` (`user_id`,`group_id`),
												  KEY `idx_deleted` (`deleted`)
												) ENGINE=InnoDB  DEFAULT CHARSET=$charset COLLATE=$collation;";
		$result								= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		
		$query								= "CREATE TABLE IF NOT EXISTS `svnpasswordreset` (
  												  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
												  `unixtime` int(11) NOT NULL,
												  `username` varchar(255) NOT NULL,
												  `token` varchar(255) NOT NULL,
												  `idstr` varchar(255) NOT NULL,
												  PRIMARY KEY (`id`)
												) ENGINE=InnoDB  DEFAULT CHARSET=$charset;";
		$result								= db_query_install( $query, $dbh );
		
	}
		
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	#error_log( $error." - ". $tMessage );
	
	return $ret;
	
}

function loadDbData( $dbh, $charset, $collation, $databasetype ) {
	
	db_ta( 'BEGIN', $dbh );
		
	$error								= 0;
	$tMessage							= "";
	$dbnow								= db_now();
	$query								= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('User admin', 'Administer users', 'Benutzer verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
	$result								= db_query_install( $query, $dbh );
		
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Group admin', 'Administer groups', 'Gruppen verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
			
	}
	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Project admin', 'Administer projects', 'Projekte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Repository admin', 'Administer repositories', 'Repositories verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {			
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Access rights admin', 'Administer repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
			$error						= 1;
			$tMessage					= sprintf( _("Error inserting data into rights table: %s" ), mysql_error() );
		}
		
	}
		
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		db_ta( 'COMMIT', $dbh );
	} else {
		db_ta( 'ROLLBACK', $dbh );
	}
		
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	#error_log( $error." - ". $tMessage );
	
	return $ret;
}

function loadPostgresDbData( $dbh, $charset, $collation, $databasetype, $schema ) {
	
	if( $schema != "" ) {
		$query							= "SET search_path = '$schema'";
	} else {
		$query							= "SET search_path = ";
	}
	$result								= db_query_install( $query, $dbh );
	
	db_ta( 'BEGIN', $dbh );
		
	$error								= 0;
	$tMessage							= "";
	$dbnow								= db_now();
	$query								= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('User admin', 'Administer users', 'Benutzer verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
	$result								= db_query_install( $query, $dbh );
		
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Group admin', 'Administer groups', 'Gruppen verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
			
	}
	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Project admin', 'Administer projects', 'Projekte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Repository admin', 'Administer repositories', 'Repositories verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {			
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Access rights admin', 'Administer repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
			$error						= 1;
			$tMessage					= sprintf( _("Error inserting data into rights table: %s" ), mysql_error() );
		}
		
	}
		
	if( $error == 0 ) {
	
		$query							= "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		db_ta( 'COMMIT', $dbh );
	} else {
		db_ta( 'ROLLBACK', $dbh );
	}
		
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	#error_log( $error." - ". $tMessage );
	
	return $ret;
}

function loadOracleDbData( $dbh, $charset, $collation, $databasetype, $schema ) {
	
	db_ta( 'BEGIN', $dbh );
		
	$error								= 0;
	$tMessage							= "";
	$dbnow								= db_now();
	$query								= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('User admin', 'Administer users', 'Benutzer verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
	$result								= db_query_install( $query, $dbh );
		
	if( $error == 0 ) {
	
		$query							= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Group admin', 'Administer groups', 'Gruppen verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
		$result							= db_query_install( $query, $dbh );
			
	}
	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Project admin', 'Administer projects', 'Projekte verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Repository admin', 'Administer repositories', 'Repositories verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {			
	
		$query							= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Access rights admin', 'Administer repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ' )";
		$result							= db_query_install( $query, $dbh );
		
	}	
	if( $error == 0 ) {
	
		$query							= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
		$result							= db_query_install( $query, $dbh );
		if( mysql_errno() != 0 ) {
			$error						= 1;
			$tMessage					= sprintf( _("Error inserting data into rights table: %s" ), mysql_error() );
		}
		
	}
		
	if( $error == 0 ) {
	
		$query							= "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " .
										  "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
		$result							= db_query_install( $query, $dbh );
		
	}
	
	if( $error == 0 ) {
		db_ta( 'COMMIT', $dbh );
	} else {
		db_ta( 'ROLLBACK', $dbh );
	}
		
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	#error_log( $error." - ". $tMessage );
	
	return $ret;
}

function createAdmin( $userid, $password, $givenname, $name, $emailaddress, $databasetype, $dbh, $schema ) {
	
	db_ta( 'BEGIN', $dbh );
	
	$CONF									= array();
	$CONF['database_host'] 					= $_SESSION['svn_inst']['databaseHost'];
	$CONF['database_user'] 					= $_SESSION['svn_inst']['databaseUser'];
	$CONF['database_password'] 				= $_SESSION['svn_inst']['databasePassword'];
	$CONF['database_name'] 					= $_SESSION['svn_inst']['databaseName'];
	$CONF['database_schema']				= $_SESSION['svn_inst']['databaseSchema'];
	$CONF['database_tablespace']			= $_SESSION['svn_inst']['databaseTablespace'];
	$CONF['pwcrypt']						= $_SESSION['svn_inst']['useMd5'];
	
	#error_log( "crypt algorithm is ".$CONF['pwcrypt'] );
	
	$error									= 0;
	$tMessage								= "";
	$pwcrypt								= $dbh->qstr( pacrypt_install( $password, "", $CONF['pwcrypt'] ), get_magic_quotes_gpc() );
	$dbnow									= db_now();
	if( ($databasetype == "oci8") or (substr($databasetype, 0, 8) == "postgres") ) {
		$query								= "INSERT INTO $schema.svnusers (userid, name, givenname, password, emailaddress, user_mode, admin, created, created_user, password_modified, superadmin) " .
											  "VALUES ('$userid', '$name', '$givenname', $pwcrypt, '$emailaddress', 'write', 'y', '$dbnow', 'install', '$dbnow', 1)";
	} else {
		$query								= "INSERT INTO svnusers (userid, name, givenname, password, emailaddress, user_mode, admin, created, created_user, password_modified, superadmin) " .
											  "VALUES ('$userid', '$name', '$givenname', $pwcrypt, '$emailaddress', 'write', 'y', '$dbnow', 'install', '$dbnow', 1)";
	}
	$result									= db_query_install( $query, $dbh );
	$uid									= db_get_last_insert_id( 'svnusers', 'id', $dbh, $_SESSION['svn_inst']['databaseSchema'] );
	db_ta( 'COMMIT', $dbh );
	#error_log( "uid read: $uid" );
		
	$query									= "SELECT id, allowed_action " .
											  "  FROM rights " .
											  " WHERE deleted = '00000000000000'";
	#error_log( $query );
	$result									= db_query_install( $query, $dbh );
	#error_log( "rows = ".$result['rows'] );
	
	while( ($error == 0) and ($row = db_assoc( $result['result'] )) ) {
		
		$allowed							= $row['allowed_action'];
		$id									= $row['id'];
		$dbnow								= db_now();
		if( ($databasetype == "oci8") or (substr($databasetype, 0, 8) == "postgres") ) {
			$query							= "INSERT INTO $schema.users_rights (user_id, right_id, allowed, created, created_user) " .
											  "VALUES ($uid, $id, '$allowed', '$dbnow', 'install')";
		} else {
			$query							= "INSERT INTO users_rights (user_id, right_id, allowed, created, created_user) " .
											  "VALUES ($uid, $id, '$allowed', '$dbnow', 'install')";
		}
		#error_log( $query );
		$resultinsert						= db_query_install( $query, $dbh );
		
		if( mysql_errno() != 0 ) {
			
			$error							= 1;
			$tMessage						= sprintf( _("Error inserting user access right for admin: %s" ), mysql_error() );
			
		}			
		
	}
	
	if( $error == 0 ) {
		db_ta( 'COMMIT', $dbh );
	} else {
		db_ta( 'ROLLBACK', $dbh );
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	#error_log( "createAdmin: ".$error." - ". $tMessage );
	
	return $ret;
	
}



function loadHelpTexts( $database, $schema, $dbh ) {
	
	$error									= 0;
	$tMessage								= "";
	$filename								= "";
	
	if( file_exists ( realpath ( "./$filename" ) ) ) {
		
		$filename							= "./$filename";
		 
	} elseif( file_exists ( realpath ( "../$filename" ) ) ) {
		
		$filename							= "../$filename";
		
	} else {
		
		$filename							= "";
	}

	if( $filename != "" ) {
	
		if( $fh_in = @fopen( $filename, "r" ) ) {
			
			db_ta( "BEGIN", $dbh );
			
			if (substr($database, 0, 8) == "postgres" ) {
		    	$schema					= ($schema == "") ? "" : $schema.".";
		    } elseif( $database == "oci8" ) {
		    	$schema					= ($schema == "") ? "" : $schema.".";
		    } else {
		    	$schema					= "";
		    }
			
			while( ! feof( $fh_in ) ) {
			
				$query 						= fgets( $fh_in );
				if( $query != "" ) {

					$query						= str_replace( " INTO help ", " INTO ".$schema."help ", $query );
					$query						= preg_replace( '/;$/', '', $query );
					#error_log( $query );
					$result						= db_query_install( $query, $dbh );
				}
				
			}
			
			@fclose( $fh_in );
			
			if( $error == 0 ) {
				db_ta( 'COMMIT', $dbh );
			} else {
				db_ta( 'ROLLBACK', $dbh );
			}
		}	
	}
	
	$ret									= array();
	$ret['error']							= $error;
	$ret['errormsg']						= $tMessage;
	
	return $ret;
}




function doDbtest() {

	$tErrors						= array();
	$CONF['database_host'] 			= $_SESSION['svn_inst']['databaseHost'];
	$CONF['database_user'] 			= $_SESSION['svn_inst']['databaseUser'];
	$CONF['database_password'] 		= $_SESSION['svn_inst']['databasePassword'];
	$CONF['database_name'] 			= $_SESSION['svn_inst']['databaseName'];
	$CONF['database_schema']		= $_SESSION['svn_inst']['databaseSchema'];
	$CONF['database_tablespace']	= $_SESSION['svn_inst']['databaseTablespace'];

	$dbh							= db_connect_install($_SESSION['svn_inst']['databaseHost'], $_SESSION['svn_inst']['databaseUser'], $_SESSION['svn_inst']['databasePassword'], $_SESSION['svn_inst']['databaseName'], $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database'], "yes");
	
	if( is_array( $dbh ) ) {
		$tErrors[]					= $dbh['error'];
	} else {
		$tErrors[]					= _("Database test ok, connection works");
	}
	
	$tDatabaseHost					= isset( $_SESSION['svn_inst']['databaseHost'] ) 		? $_SESSION['svn_inst']['databaseHost'] : "";
	$tDatabaseUser					= isset( $_SESSION['svn_inst']['databaseUser'] ) 		? $_SESSION['svn_inst']['databaseUser'] : "";
	$tDatabasePassword				= isset( $_SESSION['svn_inst']['databasePassword'] ) 	? $_SESSION['svn_inst']['databasePassword'] : ""; 
	$tDatabaseName					= isset( $_SESSION['svn_inst']['databaseName'] ) 		? $_SESSION['svn_inst']['databaseName'] : "";
	$tDatabaseSchema				= isset( $_SESSION['svn_inst']['databaseSchema'] ) 		? $_SESSION['svn_inst']['databaseSchema'] : "";
	$tDatabaseTablespace			= isset( $_SESSION['svn_inst']['databaseTablespace'] ) 	? $_SESSION['svn_inst']['databaseTablespace'] : "";
	$tDatabaseCharset				= isset( $_SESSION['svn_inst']['databaseCharset'] ) 	? $_SESSION['svn_inst']['databaseCharset'] : "";
	$tDatabaseCollation				= isset( $_SESSION['svn_inst']['databaseCollation'] ) 	? $_SESSION['svn_inst']['databaseCollation'] : "";
				
	include ("../templates/install_page_2.tpl");
}



function doLdapTest() {
	
	$tErrors								= array();
	
	if( $ldap = @ldap_connect( $_SESSION['svn_inst']['ldapHost'], $_SESSION['svn_inst']['ldapPort'] ) ) {
		
		if( $rs = @ldap_bind( $ldap, $_SESSION['svn_inst']['ldapBinddn'], $_SESSION['svn_inst']['ldapBindpw']) ) {
			
			$tErrors[]						= _("LDAP connection test ok, connection works");
			
		} else {
			
			$tErrors[]						= sprintf( _("Can't bind to ldap server: %s"), ldap_error( $ldap ) );
		}
		
		@ldap_unbind( $ldap );
		
	} else {
		
		$tErrors[]							= _("Can't connect to ldap server, hostname/ip and port are ok?");
		
	}
	
	$tCreateDatabaseTables		= isset( $_SESSION['svn_inst']['createDatabaseTables'] ) ? $_SESSION['svn_inst']['createDatabaseTables'] : ""; 
	$tDropDatabaseTables		= isset( $_SESSION['svn_inst']['dropDatabaseTables'] ) ? $_SESSION['svn_inst']['dropDatabaseTables'] : "";
	$tDatabase					= isset( $_SESSION['svn_inst']['database'] ) ? $_SESSION['svn_inst']['database'] : "";
	$tSessionInDatabase			= isset( $_SESSION['svn_inst']['sessionInDatabase'] ) ? $_SESSION['svn_inst']['sessionInDatabase'] : "";
	$tUseLdap					= isset( $_SESSION['svn_inst']['useLdap'] ) ? $_SESSION['svn_inst']['useLdap'] : "";
	$tLdapHost					= isset( $_SESSION['svn_inst']['ldapHost'] ) ? $_SESSION['svn_inst']['ldapHost'] : "";
	$tLdapPort					= isset( $_SESSION['svn_inst']['ldapPort'] ) ? $_SESSION['svn_inst']['ldapPort'] : "";
	$tLdapProtocol				= isset( $_SESSION['svn_inst']['ldapProtocol'] ) ? $_SESSION['svn_inst']['ldapProtocol'] : "";
	$tLdapBinddn				= isset( $_SESSION['svn_inst']['ldapBinddn'] ) ? $_SESSION['svn_inst']['ldapBinddn'] : "";
	$tLdapBindpw				= isset( $_SESSION['svn_inst']['ldapBindpw'] ) ? $_SESSION['svn_inst']['ldapBindpw'] : "";
	$tLdapUserdn				= isset( $_SESSION['svn_inst']['ldapUserdn'] ) ? $_SESSION['svn_inst']['ldapUserdn'] : "";
	$tLdapUserFilter			= isset( $_SESSION['svn_inst']['ldapUserFilter'] ) ? $_SESSION['svn_inst']['ldapUserFilter'] : "";
	$tLdapUserObjectclass		= isset( $_SESSION['svn_inst']['ldapUserObjectclass'] ) ? $_SESSION['svn_inst']['ldapUserObjectclass'] : "";
	$tLdapUserAdditionalFilter  = isset( $_SESSION['svn_inst']['ldapUserAdditionalFilter'] ) ? $_SESSION['svn_inst']['ldapUserAdditionalFilter'] : "";
	
	if( $tCreateDatabaseTables == "YES" ) {
		$tCreateDatabaseTablesYes	= "checked";
		$tCreateDatabaseTablesNo	= "";
	} else {
		$tCreateDatabaseTablesYes	= "";
		$tCreateDatabaseTablesNo	= "checked";
	}
	
	if( $tDropDatabaseTables == "YES" ) {
		$tDropDatabaseTablesYes		= "checked";
		$tDropDatabaseTablesNo		= "";
	} else {
		$tDropDatabaseTablesYes		= "";
		$tDropDatabaseTablesNo		= "checked";
	}
	
	if( $tDatabase == "mysql" ) {
		$tDatabaseMySQL				= "checked";
		$tDatabasePostgreSQL		= "";
		$tDatabaseOracle			= "";
	} elseif( $tDatabase == "postgres8" ) {
		$tDatabaseMySQL				= "";
		$tDatabasePostgreSQL		= "checked";
		$tDatabaseOracle			= "";
	} elseif( $tDatabase == "oci8" ) {
		$tDatabaseMySQL				= "";
		$tDatabasePostgreSQL		= "";
		$tDatabaseOracle			= "checked";
	} else {
		$tDatabaseMySQL				= "";
		$tDatabasePostgreSQL		= "";
	}
	
	if( $tSessionInDatabase == "YES" ) {
		$tSessionInDatabaseYes		= "checked";
		$tSessionInDatabaseNo		= "";
	} else {
		$tSessionInDatabaseYes		= "";
		$tSessionInDatabaseNo		= "checked";
	}
	
	if( $tUseLdap == "YES" ) {
		$tUseLdapYes				= "checked";
		$tUseLdapNo					= "";
	} else {
		$tUseLdapYes				= "";
		$tUseLdapNo					= "checked";
	}
	
	if( $tLdapProtocol == "3" ) {
		$tLdap3						= "checked";
		$tLdap2						= "";
	} else {
		$tLdap3						= "";
		$tLdap2						= "checked";
	}
	
	include ("../templates/install_page_1.tpl");
	exit;
}



function doInstall() {
	
	$tMessage								= "";
	$error									= 0;
	$tErrors								= array();
	
	if ( file_exists ( realpath ( "./config/config.inc.php" ) ) ) {
		
		$configfile							= realpath ( "./config/config.inc.php" );
		
	} elseif( file_exists ( realpath ( "../config/config.inc.php" ) ) ) {
		
		$configfile							= realpath ( "../config/config.inc.php" );
		
	} else {
		
		$configfile							= "../config/config.inc.php";
		
	}
	
	$configpath								= dirname( $configfile );
	#$confignew								= $configpath."/config.inc.php.new";
	$confignew								= "/etc/svn-access-manager/config.inc.php.new";
	$configtmpl								= $configpath."/config.inc.php.tpl";
	$configfile								= "/etc/svn-access-manager/config.inc.php";
	
	
	
	
	if( $error == 0 ) {
			
		if( $_SESSION['svn_inst']['databaseHost'] == "" ) {
			
			$tErrors[]					= _("Database host is missing!");
			$error						= 1;
			
		}
		
		if( $_SESSION['svn_inst']['databaseUser'] == "" ) {
			
			$tErrors[]					= _("Database user is missing!");
			$error						= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['databaseName'] == "" ) {
			
			$tErrors[]					= _("Database name is missing!" );
			$error						= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['databaseCharset'] == "" ) {
			
			$tErrors[]					= _("Database charset is missing!" );
			$error						= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['databaseCollation'] == "" ) {
			
			$tErrors[]					= _("Database collation is missing!" );
			$error						= 1;
			
		}
		
		if( strtoupper($_SESSION['svn_inst']['useLdap']) == "YES" ) {
				
				if( $_SESSION['svn_inst']['ldapHost'] == "" ) {
					
					$tErrors[]				= _("LDAP host is missing!");
					$error					= 1;
				
				}
				
				if( $_SESSION['svn_inst']['ldapPort'] == "" ) {
					
					$tErrors[]				= _("LDAP port is missing!");
					$error					= 1;
					
				}
				
				if( ($_SESSION['svn_inst']['ldapProtocol'] != "2") and ($_SESSION['svn_inst']['ldapProtocol'] != "3") ) {
					
					$tErrors[]				= sprintf( _("Invalid protocol version %s!"), $_SESSION['svn_inst']['ldapProtocol'] );
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapBinddn'] == "" ) {
					
					$tErrors[]				= _("LDAP bind dn is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapBindpw'] == "" ) {
					
					$tErrors[]				= _("LDAP bind password is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapUserdn'] == "" ) {
					
					$tErrors[]				= _("LDAP user dn is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapUserFilter'] == "" ) {
					
					$tErrors[]				= _("LDAP user filter attribute is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapUserObjectclass'] == "" ) {
					
					$tErrors[]				= _("LDAP user object class is missing!");
					$error					= 1;
										
				}
				
				if( $_SESSION['svn_inst']['ldapAttrUid'] == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for uid is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapAttrName'] == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for name is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapAttrGivenname'] == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for given name is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapAttrMail'] == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for mail is missing!");
					$error					= 1;
					
				}
				
				if( $_SESSION['svn_inst']['ldapAttrPassword'] == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for user password is missing!");
					$error					= 1;
					
				}
				
			}
		
		if( $_SESSION['svn_inst']['websiteCharset'] == "" ) {
			
			$tErrors[]						= _("Website charset is missing!");
			$error							= 1;
			
		}
		
		if( $_SESSION['svn_inst']['lpwMailSender'] == "" ) {
			
			$tErrors[]						= _("Lost password mail sender address is missing!");
			$error							= 1;
			
		} elseif( ! check_email( $_SESSION['svn_inst']['lpwMailSender'] ) ) {
			
			$tErrors[]						= sprintf( _("Lost password mail sender address %s is not a valid email address!" ), $_SESSION['svn_inst']['lpwMailSender'] );
			$error							= 1;
			
		}
		
		if( $_SESSION['svn_inst']['lpwLinkValid'] == "" ) {
			
			$tErrors[]						= _("Lost password days link valid missing!");
			$error							= 1;
			
		} elseif( ! is_numeric( $_SESSION['svn_inst']['lpwLinkValid'] ) ) {
			
			$tErrors[]						= _("Lost password days link valid must be numeric!" );
			$error							= 1;
			
		}
		
		if( $_SESSION['svn_inst']['username'] == "" ) {
			
			$tErrors[]						= _("Administrator username is missing!" );
			$error							= 1;
			
		} 
		
		if( ($_SESSION['svn_inst']['password'] == "") or ($_SESSION['svn_inst']['password2'] == "") ) {
			
			$tErrors[]						= _("Administrator password is missing!" );
			$error							= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['password'] != $_SESSION['svn_inst']['password2'] ) {
			
			$tErrors[]						= _("Administrator passwords do not match!" );
			$error							= 1;
			
		} elseif( checkPasswordPolicy( $_SESSION['svn_inst']['password'], 'y' ) == 0 ) {
			
			$tErrors[]						= _("Administrator password is not strong enough!" );
			$error							= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['name'] == "" ) {
			
			$tErrors[]						= _("Administrator name is missing!" );
			$error							= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['adminEmail'] == "" ) {
			
			$tErrors[]						= _("Administrator email address is missing!" );
			$error							= 1;
			
		} elseif( ! check_email($_SESSION['svn_inst']['adminEmail']) ) {
			
			$tErrors[]						= sprintf( _("Administrator email address %s is not a valid email address!"), $_SESSION['svn_inst']['adminEmail'] );
			$error							= 1;
			
		}
		
		if( $_SESSION['svn_inst']['useSvnAccessFile'] == "YES" ) {
			
			if( $_SESSION['svn_inst']['svnAccessFile'] == "" ) {
				
				$tErrors[]					= _( "SVN Access File is missing!" );
				$error						= 1;
				
			}
			
			if( $_SESSION['svn_inst']['authUserFile'] == "" ) {
				
				$tErrors[]					= _("Auth user file is missing!" );
				$error						= 1;
				
			}
		}
		
		if( $_SESSION['svn_inst']['viewvcConfig'] == "YES" ) {
		
			if( $_SESSION['svn_inst']['viewvcConfigDir'] == "" ) {
				
				$tErrors[]					= _("ViewVC configuration directory is missing!");
				$error						= 1;
				
			} elseif( $_SESSION['svn_inst']['viewvcAlias'] == "" ) {
				
				$tErrors[]					= _("ViewVC webserver alias is missing!");
				$error						= 1;
				
			} elseif( $_SESSION['svn_inst']['viewvcRealm'] == "" ) {
				
				$tErrors[]					= _("ViewVC realm is missing!" );
				$error						= 1;
				
			}
		}
		
		if( $_SESSION['svn_inst']['svnCommand'] == "" ) {
			
			$tErrors[]						= _("SVN command is missing!" );
			$error							= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['svnadminCommand'] == "" ) { 
		
			$tErrors[]						= _("Svnadmin command missing!" );
			$error							= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['grepCommand'] == "" ) {
			
			$tErrors[]						= _("Grep command is missinbg!" );
			$error							= 1;
			
		} 
		
		if( $_SESSION['svn_inst']['pageSize'] == "" ) {
			
			$tErrors[]						= _("Page size is missing!" );
			$error							= 1;
			
		} 
		
		if( ! is_numeric( $_SESSION['svn_inst']['pageSize'] ) ) {
			
			$tErrors[]						= _("Page size is not numeric!" );
			$error							= 1;
			
		} 
		
		if( ! is_numeric( $_SESSION['svn_inst']['minAdminPwSize'] ) ) {
			
			$tErrors[]						= _("Minimal administrator password length is not numeric!" );
			$error							= 1;
			
		} 
		
		if( ! is_numeric( $_SESSION['svn_inst']['minUserPwSize'] ) ) {
			
			$tErrors[]						= _("Minimal user password length is not numeric!" );
			$error							= 1;
			
		}
		
	}
	
	if( $error == 0 ) {
			
		if( $fh_in = @fopen( $configtmpl, "r" ) ) {
			
			$viewvcconf					= $_SESSION['svn_inst']['viewvcConfigDir']."/viewvc-apache.conf";
			$viewvcgroups				= $_SESSION['svn_inst']['viewvcConfigDir']."/viewvc-groups";
			$content 					= fread ( $fh_in, filesize ($configtmpl));
			@fclose( $fh_in );
			
			$output						= "";
			$retcode					= 0;
			$cmd 						= $_SESSION['svn_inst']['svnadminCommand']." help create";
			exec( $cmd, $output, $retcode );
			if( $retcode == 0 ) {
				
				$treffer 				= preg_grep( '/\-\-pre\-(.*)\-compatible/', $output );
				
				if( count( $treffer ) > 0 ) { 
					
					foreach( $treffer as $entry ) {
		
					        $entry 		= explode( ":", $entry);
					        $entry 		= $entry[0];
					        $entry 		= preg_replace( '/^\s+/', '', $entry );
					        $entry 		= preg_replace( '/\s+$/', '', $entry );
					
					}
		
					$preCompatible		= $entry;
					
				} else {
					$preCompatible		= "--pre-1.4-compatible";
				}
				
			} else {
				$preCompatible			= "--pre-1.4-compatible";
			}
			
			if( isset( $_SERVER['SCRIPT_FILENAME'] ) ) {
				$installBase			= dirname( dirname( $_SERVER['SCRIPT_FILENAME'] ) );
			} else {
				$installBase			= '';
			}
			
			$content					= str_replace( '###DBTYPE###', 				$_SESSION['svn_inst']['database'], $content );
			$content 					= str_replace( '###DBHOST###', 				$_SESSION['svn_inst']['databaseHost'], $content );
			$content					= str_replace( '###DBUSER###', 				$_SESSION['svn_inst']['databaseUser'], $content );
			$content					= str_replace( '###DBPASS###', 				$_SESSION['svn_inst']['databasePassword'], $content );
			$content					= str_replace( '###DBNAME###', 				$_SESSION['svn_inst']['databaseName'], $content );
			$content					= str_replace( '###DBSCHEMA###', 			$_SESSION['svn_inst']['databaseSchema'], $content );
			$content					= str_replace( '###DBTABLESPACE###', 		$_SESSION['svn_inst']['databaseTablespace'], $content );
			$content					= str_replace( '###DBCHARSET###', 			$_SESSION['svn_inst']['databaseCharset'], $content );
			$content					= str_replace( '###DBCOLLATION###', 		$_SESSION['svn_inst']['databaseCollation'], $content );
			$content					= str_replace( '###USELOGGING###', 			$_SESSION['svn_inst']['logging'], $content );
			$content					= str_replace( '###PAGESIZE###', 			$_SESSION['svn_inst']['pageSize'], $content );
			$content					= str_replace( '###SVNCMD###', 				$_SESSION['svn_inst']['svnCommand'], $content );
			$content					= str_replace( '###GREPCMD###', 			$_SESSION['svn_inst']['grepCommand'], $content );
			$content					= str_replace( '###USEJS###', 				$_SESSION['svn_inst']['javaScript'], $content );
			$content					= str_replace( '###SVNACCESSFILE###', 		$_SESSION['svn_inst']['svnAccessFile'], $content );
			$content					= str_replace( '###ACCESSCONTROLLEVEL###', 	$_SESSION['svn_inst']['accessControlLevel'], $content );
			$content					= str_replace( '###SVNAUTHFILE###', 		$_SESSION['svn_inst']['authUserFile'], $content );
			$content					= str_replace( '###CREATEACCESSFILE###', 	$_SESSION['svn_inst']['useSvnAccessFile'], $content );
			$content					= str_replace( '###CREATEAUTHFILE###', 		$_SESSION['svn_inst']['useAuthUserFile'], $content );
			$content					= str_replace( '###ADMINEMAIL###', 			$_SESSION['svn_inst']['adminEmail'], $content );
			$content					= str_replace( '###MINPWADMIN###', 			$_SESSION['svn_inst']['minAdminPwSize'], $content );
			$content					= str_replace( '###MINPWUSER###', 			$_SESSION['svn_inst']['minUserPwSize'], $content );
			$content					= str_replace( '###SESSIONINDB###', 		$_SESSION['svn_inst']['sessionInDatabase'], $content );
			$content					= str_replace( '###PWCRYPT###', 			$_SESSION['svn_inst']['useMd5'], $content );
			$content					= str_replace( '###CREATEVIEWVCCONF###', 	$_SESSION['svn_inst']['viewvcConfig'], $content );
			$content					= str_replace( '###VIEWVCCONF###', 			$viewvcconf, $content );
			$content					= str_replace( '###VIEWVCGROUPS###', 		$viewvcgroups, $content );
			$content					= str_replace( '###VIEWVCLOCATION###', 		$_SESSION['svn_inst']['viewvcAlias'], $content );
			$content					= str_replace( '###VIEWVCAPACHERELOAD###', 	$_SESSION['svn_inst']['viewvcApacheReload'], $content );
			$content					= str_replace( '###VIEWVCREALM###', 		$_SESSION['svn_inst']['viewvcRealm'], $content );
			$content					= str_replace( '###SEPERATEFILESPERREPO###',$_SESSION['svn_inst']['perRepoFiles'], $content );
			$content					= str_replace( '###SVNADMINCMD###', 		$_SESSION['svn_inst']['svnadminCommand'], $content );
			$content					= str_replace( '###WEBSITECHARSET###', 		$_SESSION['svn_inst']['websiteCharset'], $content );
			$content					= str_replace( '###LOSTPWSENDER###', 		$_SESSION['svn_inst']['lpwMailSender'], $content );
			$content					= str_replace( '###LOSTPWMAXERROR###', 		3, $content );
			$content					= str_replace( '###LOSTPWLINKVALID###', 	$_SESSION['svn_inst']['lpwLinkValid'], $content );
			$content					= str_replace( '###PRECOMPATIBLE###', 		$preCompatible, $content );
			$content					= str_replace( '###INSTALLBASE###',			$installBase, $content );
			$content					= str_replace( '###USELDAP###',				$_SESSION['svn_inst']['useLdap'], $content );
			$content					= str_replace( '###BINDDN###',				$_SESSION['svn_inst']['ldapBinddn'], $content );
			$content					= str_replace( '###BINDPW###',				$_SESSION['svn_inst']['ldapBindpw'], $content );
			$content					= str_replace( '###USERDN###',				$_SESSION['svn_inst']['ldapUserdn'], $content );
			$content					= str_replace( '###USERFILTERATTR###',		$_SESSION['svn_inst']['ldapUserFilter'], $content );
			$content					= str_replace( '###USEROBJECTCLASS###',		$_SESSION['svn_inst']['ldapUserObjectclass'], $content );
			$content					= str_replace( '###USERADDITIONALFILTER###',$_SESSION['svn_inst']['ldapUserAdditionalFilter'], $content );
			$content					= str_replace( '###LDAPHOST###',			$_SESSION['svn_inst']['ldapHost'], $content );
			$content					= str_replace( '###LDAPPORT###',			$_SESSION['svn_inst']['ldapPort'], $content );
			$content					= str_replace( '###LDAPPROTOCOL###',		$_SESSION['svn_inst']['ldapProtocol'], $content );
			$content					= str_replace( '###MAPUID###',				$_SESSION['svn_inst']['ldapAttrUid'], $content );
			$content					= str_replace( '###MAPNAME###',				$_SESSION['svn_inst']['ldapAttrName'], $content );
			$content					= str_replace( '###MAPGIVENNAME###',		$_SESSION['svn_inst']['ldapAttrGivenname'], $content );
			$content					= str_replace( '###MAPMAIL###',				$_SESSION['svn_inst']['ldapAttrMail'], $content );
			$content					= str_replace( '###MAPPASSWORD###',			$_SESSION['svn_inst']['ldapAttrPassword'], $content );
			
		} else {
			
			$tErrors[] 					= _("can't open config template for reading!");
			$error						= 1;
			
		}
			
	}
	
	if( $error == 0 ) {
		
		if( $fh_out = @fopen($confignew, "w" ) ) {
			
			if( ! @fwrite( $fh_out, $content ) ) {
				
				$tErrors[]				= _("Can't write new config.inc.php file!" );
				$error					= 1;
				
			} 
			
		} else {
			
			$tErrors[] 					= _("can't open config.inc.php for writing. Please make sure the config directory is writeable for the webserver user!" );
			$error						= 1;
		}
		
	}
	
	if( $error == 0 ) {
		
		if( @copy( $confignew, $configfile) ) {
			
			if( ! @unlink( $confignew ) ) {
				
				if( determineOs() == "windows" ) {
					$error				= 0;
				} else {
					$error				= 1;
					$tErrors[]			= _("Error deleting temporary config file");
				}
				
			} else {
				
				$tResult[]				= _("config.inc.php successfully created");
				
			}
			
		} else {
			
			$error						= 1;
			$tErrors[]					= sprintf( _("Error copying temporary config file %s to %s!"), $confignew, $configfile );
			
		}
	}
	
	if( $error == 0 ) {
		
		$CONF['database_host'] 			= $_SESSION['svn_inst']['databaseHost'];
		$CONF['database_user'] 			= $_SESSION['svn_inst']['databaseUser'];
		$CONF['database_password'] 		= $_SESSION['svn_inst']['databasePassword'];
		$CONF['database_name'] 			= $_SESSION['svn_inst']['databaseName'];
		$CONF['database_schema']		= $_SESSION['svn_inst']['databaseSchema'];
		$CONF['database_tablespace']	= $_SESSION['svn_inst']['databaseTablespace'];

		if( $_SESSION['svn_inst']['createDatabaseTables'] == "YES" ) {
			
			$dbh						= db_connect_install($_SESSION['svn_inst']['databaseHost'], $_SESSION['svn_inst']['databaseUser'], $_SESSION['svn_inst']['databasePassword'], $_SESSION['svn_inst']['databaseName'], $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database']);
			
			if( $_SESSION['svn_inst']['dropDatabaseTables'] == "YES" ) {
		
				if( strtoupper($_SESSION['svn_inst']['database']) == "MYSQL" ) {
					
					$ret				= dropMySQLDatabaseTables( $dbh );
					
				} elseif( strtoupper($_SESSION['svn_inst']['database']) == "POSTGRES8") {
					
					$ret				= dropPostgresDatabaseTables( $dbh );
					
				} elseif( strtoupper($_SESSION['svn_inst']['database']) == "OCI8" ) {
					
					$ret				= dropOracleDatabaseTables( $dbh, $_SESSION['svn_inst']['databaseSchema'] );
					
				}
				if( $ret['error'] != 0 ) {
				
					$tErrors[]			= $ret['errormsg'];
					$error				= 1;
				
				} else {
					
					$tResult[]			= _("Database tables successfully dropped");
				}
				
			} else {
					
				$tResult[]				= _("No database tables dropped");
					 
			}
			
			if( $error == 0 ) {
				
				if( strtoupper($_SESSION['svn_inst']['database']) == "MYSQL" ) {
					
					$ret				= createMySQLDatabaseTables( $dbh, $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'] );
					
				} elseif( strtoupper($_SESSION['svn_inst']['database']) == "POSTGRES8") {
					
					$ret				= createDatabaseTables( $dbh, $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database'], $_SESSION['svn_inst']['databaseSchema'], $_SESSION['svn_inst']['databaseTablespace'], $_SESSION['svn_inst']['databaseUser'] );
					
				} elseif( strtoupper($_SESSION['svn_inst']['database']) == "OCI8" ) {
					
					$ret				= createOracleDatabaseTables( $dbh, $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database'], $_SESSION['svn_inst']['databaseSchema'], $_SESSION['svn_inst']['databaseTablespace'], $_SESSION['svn_inst']['databaseUser'] );
					
				}
				if( $ret['error'] != 0 ) {
				
					$tErrors[]			= $ret['errormsg'];
				
				} else {
					
					$tResult[]			= _("Database tables successfully created");			
					
				}
			
			}
			
			if( $error == 0 ) {
				
				if( strtoupper($_SESSION['svn_inst']['database']) == "MYSQL" ) {
					
					$ret				= loadDbData( $dbh, $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database'] );
					
				} elseif( strtoupper($_SESSION['svn_inst']['database']) == "POSTGRES8" ) {
					
					$ret				= loadPostgresDbData( $dbh, $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database'], $_SESSION['svn_inst']['databaseSchema'] );
					
				} elseif( strtoupper($_SESSION['svn_inst']['database']) == "OCI8" ) {
					
					$ret				= loadOracleDbData( $dbh, $_SESSION['svn_inst']['databaseCharset'], $_SESSION['svn_inst']['databaseCollation'], $_SESSION['svn_inst']['database'], $_SESSION['svn_inst']['databaseSchema'] );
					
				}
				
				if( $ret['error'] != 0 ) {
				
					$tErrors[]			= $ret['errormsg'];
				
				} else {
					
					$tResult[]			= _("Database tables successfully created");			
					
				}
			}
			
			if( $error == 0 ) {
				
				$ret					= createAdmin( $_SESSION['svn_inst']['username'], $_SESSION['svn_inst']['password'], $_SESSION['svn_inst']['givenname'], $_SESSION['svn_inst']['name'], $_SESSION['svn_inst']['adminEmail'], $_SESSION['svn_inst']['database'], $dbh, $_SESSION['svn_inst']['databaseSchema'] );
				if( $ret['error'] != 0 ) {
				
					$tErrors[]			= $ret['errormsg'];
				
				} else {
					
					$tResult[]			= _("Admin account successfully created");			
					
				}
			}
			
			if( $error == 0 ) {
				
				$ret					= loadHelpTexts( $_SESSION['svn_inst']['database'], $_SESSION['svn_inst']['databaseSchema'], $dbh );
				
			}
			
			db_disconnect( $dbh );
			
		} else {
			
			$tResult[]					= _("No database tables created");
		}
		
	}
	
	if( $error == 0 ) {
		
		$CONF							= array();
		$CONF['copyright']				= '(C) 2008, 2009, 2010 Thomas Krieger (tom(at)svn-access-manager(dot)org)';
		$tAuthUserFile					= isset( $_SESSION['svn_inst']['authUserFile'] ) ? $_SESSION['svn_inst']['authUserFile'] : "";
		$tSvnAccessFile					= isset( $_SESSION['svn_inst']['svnAccessFile'] ) ? $_SESSION['svn_inst']['svnAccessFile'] : "";
		
		include ("../templates/installresult.tpl");
		
	} else {
	
		$tLogging						= isset( $_SESSION['svn_inst']['logging'] ) 		? $_SESSION['svn_inst']['logging'] 			: "YES";
		$tJavaScript					= isset( $_SESSION['svn_inst']['javaScript'] ) 		? $_SESSION['svn_inst']['javaScript'] 		: "YES";
		$tPageSize						= isset( $_SESSION['svn_inst']['pageSize'] ) 		? $_SESSION['svn_inst']['pageSize'] 		: "30";
		$tMinAdminPwSize				= isset( $_SESSION['svn_inst']['minAdminPwSize'] ) 	? $_SESSION['svn_inst']['minAdminPwSize'] 	: "14";
		$tMinUserPwSize					= isset( $_SESSION['svn_inst']['minUserPwSize'] ) 	? $_SESSION['svn_inst']['minUserPwSize'] 	: "8"; 
		$tUseMd5						= isset( $_SESSION['svn_inst']['useMd5'] ) 			? $_SESSION['svn_inst']['useMd5'] 			: "md5";
		
		if( $tJavaScript == "YES" ) {
			$tJavaScriptYes				= "checked";
			$tJavaScriptNo				= "";
		} else {
			$tJavaScriptYes				= "";
			$tJavaScriptNo				= "checked";
		}
		
		if( $tLogging == "YES" ) {
			$tLoggingYes				= "checked";
			$tLoggingNo					= "";
		} else {
			$tLoggingYes				= "";
			$tLoggingNo					= "checked";
		}
		
		if( $tUseMd5 == "md5" ) {
			$tMd5Yes					= "checked";
			$tMd5No						= "";
			$CONF['pwcrypt']			= "md5";
		} else {
			$tMd5Yes					= "";
			$tMd5No						= "checked";
			$CONF['pwcrypt']			= "crypt";
		}
				
		include ("../templates/install_page_6.tpl");
		
	}
}



initialize_i18n();
 
if ($_SERVER['REQUEST_METHOD'] == "GET") {
   
   	$s 										= new Session;
   	session_start();
   	session_register("svn_inst");
    $_SESSION['svn_inst']['page']			= "1";
   	   	
   	$CONF									= array();
	$CONF['database_type']					= "mysql";
	$CONF['database_innodb']                = 'YES';
	$CONF['copyright']						= '(C) 2008, 2009, 2010 Thomas Krieger (tom(at)svn-access-manager(dot)org)';
	
   	$tCreateDatabaseTablesYes				= "checked";
   	$tCreateDatabaseTablesNo				= "";
   	$tDropDatabaseTablesYes					= "checked";
   	$tDropDatabaseTablesNo					= "";
   	$tDatabaseMySQL							= "checked";
   	$tDatabasePostgreSQL					= "";
   	$tDatabaseOracle						= "";
   	$tSessionInDatabaseYes					= "checked";
   	$tSessionInDatabaseNo					= "";
   	$tUseLdapYes							= "";
   	$tUseLdapNo								= "checked";
   	$tLdapHost								= "";
   	$tLdapPort								= "636";
   	$tLdap2									= "";
   	$tLdap3									= "checked";
   	$tLdapBinddn							= "";
   	$tLdapBindpw							= "";
   	$tLdapUserdn							= "";
   	$tLdapUserFilter						= "";
   	$tLdapUserObjectclass					= "";
   	$tLdapUserAdditionalFilter				= "";
   	
   	$tErrors								= array();
   
   	include ("../templates/install_page_1.tpl");
   
}



if ($_SERVER['REQUEST_METHOD'] == "POST") {

	$s 										= new Session;
   	session_start();
   	if (!session_is_registered ("svn_inst"))  {
    	session_register("svn_inst");
    	$_SESSION['svn_inst']['page']		= "1";
   	}
    
    $tResult								= array();
	$tErrors								= array();
	$CONF									= array();
	$CONF['database_innodb']                = 'YES';
	$CONF['copyright']						= '(C) 2008, 2009, 2010 Thomas Krieger (tom(at)svn-access-manager(dot)org)';
	
	if( isset( $_POST['fSubmit_next'] ) ) {
		
		if( $_SESSION['svn_inst']['page'] == "1" ) {
			
			$error							= 0;
			$tCreateDatabaseTables			= isset( $_POST['fCreateDatabaseTables'] ) 	? ( $_POST['fCreateDatabaseTables'] )	: "";
			$tDropDatabaseTables			= isset( $_POST['fDropDatabaseTables'] ) 	? ( $_POST['fDropDatabaseTables'] )		: "";
			$tDatabase						= isset( $_POST['fDatabase'] )				? ( $_POST['fDatabase'] )				: "";
			$tSessionInDatabase				= isset( $_POST['fSessionInDatabase'])		? ( $_POST['fSessionInDatabase'] )		: "";
			$tUseLdap						= isset( $_POST['fUseLdap'])				? ( $_POST['fUseLdap'] )				: "";
			$tLdapHost						= isset( $_POST['fLdapHost'])				? ( $_POST['fLdapHost'])				: "";
			$tLdapPort						= isset( $_POST['fLdapPort'])				? ( $_POST['fLdapPort'])				: "389";
			$tLdapProtocol					= isset( $_POST['fLdapProtocol'])			? ( $_POST['fLdapProtocol'])			: "3";
			$tLdapBinddn					= isset( $_POST['fLdapBinddn'])				? ( $_POST['fLdapBinddn'])				: "";
			$tLdapBindpw					= isset( $_POST['fLdapBindpw'])				? ( $_POST['fLdapBindpw'])				: "";
			$tLdapUserdn					= isset( $_POST['fLdapUserdn'])				? ( $_POST['fLdapUserdn'])				: "";
			$tLdapUserFilter				= isset( $_POST['fLdapUserFilter'])			? ( $_POST['fLdapUserFilter'])			: "";
			$tLdapUserObjectclass			= isset( $_POST['fLdapUserObjectclass'])	? ( $_POST['fLdapUserObjectclass'])		: "";
			$tLdapUserAdditionalFilter		= isset( $_POST['fLdapUserAdditionalFilter']) ? ( $_POST['fLdapUserAdditionalFilter']) : "";
			
			$_SESSION['svn_inst']['createDatabaseTables']		= $tCreateDatabaseTables;
			$_SESSION['svn_inst']['dropDatabaseTables']			= $tDropDatabaseTables;
			$_SESSION['svn_inst']['database']					= $tDatabase;
			$_SESSION['svn_inst']['sessionInDatabase']			= $tSessionInDatabase;
			
			if( strtoupper( $tDatabase) == "MYSQL" ) {
				$tDatabaseCharsetDefault	= "latin1";
				$tDatabaseCollationDefault	= "latin1_german1_ci";
			} else {
				$tDatabaseCharsetDefault	= "";
				$tDatabaseCollationDefault	= "";
			}
			
			if( strtoupper($tUseLdap) == "YES" ) {
				
				if( $tLdapHost == "" ) {
					
					$tErrors[]				= _("LDAP host is missing!");
					$error					= 1;
				
				}
				
				if( $tLdapPort == "" ) {
					
					$tErrors[]				= _("LDAP port is missing!");
					$error					= 1;
					
				}
				
				if( ($tLdapProtocol != "2") and ($tLdapProtocol != "3") ) {
					
					$tErrors[]				= sprintf( _("Invalid protocol version %s!"), $tLdapProtocol );
					$error					= 1;
					
				}
				
				if( $tLdapBinddn == "" ) {
					
					$tErrors[]				= _("LDAP bind dn is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapBindpw == "" ) {
					
					$tErrors[]				= _("LDAP bind password is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapUserdn == "" ) {
					
					$tErrors[]				= _("LDAP user dn is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapUserFilter == "" ) {
					
					$tErrors[]				= _("LDAP user filter attribute is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapUserObjectclass == "" ) {
					
					$tErrors[]				= _("LDAP user object class is missing!");
					$error					= 1;
										
				}
				
			}
			
			if( $tCreateDatabaseTables == "YES" ) {
				$tCreateDatabaseTablesYes	= "checked";
				$tCreateDatabaseTablesNo	= "";
			} else {
				$tCreateDatabaseTablesYes	= "";
				$tCreateDatabaseTablesNo	= "checked";
			}
			
			if( $tDropDatabaseTables == "YES" ) {
				$tDropDatabaseTablesYes		= "checked";
				$tDropDatabaseTablesNo		= "";
			} else {
				$tDropDatabaseTablesYes		= "";
				$tDropDatabaseTablesNo		= "checked";
			}
			
			if( $tDatabase == "mysql" ) {
				$tDatabaseMySQL				= "checked";
				$tDatabasePostgreSQL		= "";
				$tDatabaseOracle			= "";
			} elseif( $tDatabase == "postgres8" ) {
				$tDatabaseMySQL				= "";
				$tDatabasePostgreSQL		= "checked";
				$tDatabaseOracle			= "";
			} elseif( $tDatabase == "oci8" ) {
				$tDatabaseMySQL				= "";
				$tDatabasePostgreSQL		= "";
				$tDatabaseOracle			= "checked";
			} else {
				$tDatabaseMySQL				= "";
				$tDatabasePostgreSQL		= "";
			}
			
			if( $tSessionInDatabase == "YES" ) {
				$tSessionInDatabaseYes		= "checked";
				$tSessionInDatabaseNo		= "";
			} else {
				$tSessionInDatabaseYes		= "";
				$tSessionInDatabaseNo		= "checked";
			}
			
			if( $tUseLdap == "YES" ) {
				$tUseLdapYes				= "checked";
				$tUseLdapNo					= "";
			} else {
				$tUseLdapYes				= "";
				$tUseLdapNo					= "checked";
			}
			
			if( $tLdapProtocol == "3" ) {
				$tLdap3						= "checked";
				$tLdap2						= "";
			} else {
				$tLdap3						= "";
				$tLdap2						= "checked";
			}
			
			if( $error == 0 ) {
					
				$_SESSION['svn_inst']['useLdap']					= $tUseLdap;
				$_SESSION['svn_inst']['ldapHost']					= $tLdapHost;
				$_SESSION['svn_inst']['ldapPort']					= $tLdapPort;
				$_SESSION['svn_inst']['ldapProtocol']				= $tLdapProtocol;
				$_SESSION['svn_inst']['ldapBinddn']					= $tLdapBinddn;
				$_SESSION['svn_inst']['ldapBindpw']					= $tLdapBindpw;
				$_SESSION['svn_inst']['ldapUserdn']					= $tLdapUserdn;
				$_SESSION['svn_inst']['ldapUserFilter']				= $tLdapUserFilter;
				$_SESSION['svn_inst']['ldapUserObjectclass']		= $tLdapUserObjectclass;
				$_SESSION['svn_inst']['ldapUserAdditionalFilter']	= $tLdapUserAdditionalFilter;
				
				$tDatabaseHost				= isset( $_SESSION['svn_inst']['databaseHost'] ) 		? $_SESSION['svn_inst']['databaseHost'] 		: "";
				$tDatabaseUser				= isset( $_SESSION['svn_inst']['databaseUser'] ) 		? $_SESSION['svn_inst']['databaseUser'] 		: "";
				$tDatabasePassword			= isset( $_SESSION['svn_inst']['databasePassword'] ) 	? $_SESSION['svn_inst']['databasePassword'] 	: ""; 
				$tDatabaseName				= isset( $_SESSION['svn_inst']['databaseName'] ) 		? $_SESSION['svn_inst']['databaseName'] 		: "";
				$tDatabaseSchema			= isset( $_SESSION['svn_inst']['databaseSchema'] ) 		? $_SESSION['svn_inst']['databaseSchema'] 		: "";
				$tDatabaseTablespace		= isset( $_SESSION['svn_inst']['databaseTablespace'] ) 	? $_SESSION['svn_inst']['databaseTablespace'] 	: "";
				$tDatabaseCharset			= isset( $_SESSION['svn_inst']['databaseCharset'] ) 	? $_SESSION['svn_inst']['databaseCharset'] 		: $tDatabaseCharsetDefault;
				$tDatabaseCollation			= isset( $_SESSION['svn_inst']['databaseCollation'] ) 	? $_SESSION['svn_inst']['databaseCollation'] 	: $tDatabaseCollationDefault;
				$tLdapAttrUid				= isset( $_SESSION['svn_inst']['ldapAttrUid'] ) 		? $_SESSION['svn_inst']['ldapAttrUid'] 			: "uid";
				$tLdapAttrName				= isset( $_SESSION['svn_inst']['ldapAttrName'] ) 		? $_SESSION['svn_inst']['ldapAttrName'] 		: "sn";
				$tLdapAttrGivenname			= isset( $_SESSION['svn_inst']['ldapAttrGivenname'] ) 	? $_SESSION['svn_inst']['ldapAttrGivenname'] 	: "givenName";
				$tLdapAttrMail				= isset( $_SESSION['svn_inst']['ldapAttrMail'] ) 		? $_SESSION['svn_inst']['ldapAttrMail'] 		: "mail";
				$tLdapAttrPassword			= isset( $_SESSION['svn_inst']['ldapAttrPassword'] ) 	? $_SESSION['svn_inst']['ldapAttrPassword'] 	: "userPassword";
		
				$_SESSION['svn_inst']['page']++;
				include ("../templates/install_page_2.tpl");
				exit;
			
			} else {
				
				include ("../templates/install_page_1.tpl");
				exit;
				
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "2" ) {
			
			$error							= 0;
			$tDatabaseHost					= isset( $_POST['fDatabaseHost'] )			? ( $_POST['fDatabaseHost'] )			: "";
			$tDatabaseUser					= isset( $_POST['fDatabaseUser'] )			? ( $_POST['fDatabaseUser'] )			: "";
			$tDatabasePassword				= isset( $_POST['fDatabasePassword'] )		? ( $_POST['fDatabasePassword'] )		: "";
			$tDatabaseName					= isset( $_POST['fDatabaseName'] )			? ( $_POST['fDatabaseName'] )			: "";
			$tDatabaseSchema				= isset( $_POST['fDatabaseSchema'] )		? ( $_POST['fDatabaseSchema'] )			: "";
			$tDatabaseTablespace			= isset( $_POST['fDatabaseTablespace'] )	? ( $_POST['fDatabaseTablespace'] )		: "";
			$tDatabaseCharset				= isset( $_POST['fDatabaseCharset'] )		? ( $_POST['fDatabaseCharset'] )		: "";
			$tDatabaseCollation				= isset( $_POST['fDatabaseCollation'] )		? ( $_POST['fDatabaseCollation'] )		: "";
			$tLdapAttrUid					= isset( $_POST['fLdapAttrUid'])			? ( $_POST['fLdapAttrUid'])				: "";
			$tLdapAttrName					= isset( $_POST['fLdapAttrName'])			? ( $_POST['fLdapAttrName'])			: "";
			$tLdapAttrGivenname				= isset( $_POST['fLdapAttrGivenname'])		? ( $_POST['fLdapAttrGivenname'])		: "";
			$tLdapAttrMail					= isset( $_POST['fLdapAttrMail'])			? ( $_POST['fLdapAttrMail'])			: "";
			$tLdapAttrPassword				= isset( $_POST['fLdapAttrPassword'])		? ( $_POST['fLdapAttrPassword'])		: "";
			
			if( $tDatabaseHost == "" ) {
			
				$tErrors[]					= _("Database host is missing!");
				$error						= 1;
				
			}
			
			if( $tDatabaseUser == "" ) {
				
				$tErrors[]					= _("Database user is missing!");
				$error						= 1;
				
			} 
			
			if( $tDatabaseName == "" ) {
				
				$tErrors[]					= _("Database name is missing!" );
				$error						= 1;
				
			} 
			
			if( $tDatabaseCharset == "" ) {
				
				$tErrors[]					= _("Database charset is missing!" );
				$error						= 1;
				
			} 
			
			if( $tDatabaseCollation == "" ) {
				
				$tErrors[]					= _("Database collation is missing!" );
				$error						= 1;
				
			}
			
			if( $_SESSION['svn_inst']['useLdap'] == "YES" ) {
				
				if( $tLdapAttrUid == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for uid is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapAttrName == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for name is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapAttrGivenname == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for given name is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapAttrMail == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for mail is missing!");
					$error					= 1;
					
				}
				
				if( $tLdapAttrPassword == "" ) {
					
					$tErrors[]				= _("LDAP attribute mapping for user password is missing!");
					$error					= 1;
					
				}
			}
			
			if( $error == 0 ) {
			
				$_SESSION['svn_inst']['databaseHost']		= $tDatabaseHost;
				$_SESSION['svn_inst']['databaseUser']		= $tDatabaseUser;
				$_SESSION['svn_inst']['databasePassword']	= $tDatabasePassword;
				$_SESSION['svn_inst']['databaseName']		= $tDatabaseName;
				$_SESSION['svn_inst']['databaseSchema']		= $tDatabaseSchema;
				$_SESSION['svn_inst']['databaseTablespace']	= $tDatabaseTablespace;
				$_SESSION['svn_inst']['databaseCharset']	= $tDatabaseCharset;
				$_SESSION['svn_inst']['databaseCollation']	= $tDatabaseCollation;
				$_SESSION['svn_inst']['ldapAttrUid']		= $tLdapAttrUid;
				$_SESSION['svn_inst']['ldapAttrName']		= $tLdapAttrName;
				$_SESSION['svn_inst']['ldapAttrGivenname']	= $tLdapAttrGivenname;
				$_SESSION['svn_inst']['ldapAttrMail']		= $tLdapAttrMail;
				$_SESSION['svn_inst']['ldapAttrPassword']	= $tLdapAttrPassword;
			
				$tWebsiteCharset			= isset( $_SESSION['svn_inst']['websiteCharset'] ) 	? $_SESSION['svn_inst']['websiteCharset'] 	: "iso8859-15";
				$tLpwMailSender				= isset( $_SESSION['svn_inst']['lpwMailSender'] ) 	? $_SESSION['svn_inst']['lpwMailSender'] 	: "";
				$tLpwLinkValid				= isset( $_SESSION['svn_inst']['lpwLinkValid'] ) 	? $_SESSION['svn_inst']['lpwLinkValid'] 	: "";
				
				$_SESSION['svn_inst']['page']++;
				include ("../templates/install_page_3.tpl");
				exit;
			
			} else {
				
				include ("../templates/install_page_2.tpl");
			exit;
			
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "3" ) {
			
			$error							= 0;
			$tWebsiteCharset				= isset( $_POST['fWebsiteCharset'] )		? ( $_POST['fWebsiteCharset'] )			: "";
			$tLpwMailSender					= isset( $_POST['fLpwMailSender'] )			? ( $_POST['fLpwMailSender'] )			: "";
			$tLpwLinkValid					= isset( $_POST['fLpwLinkValid'] ) 			? ( $_POST['fLpwLinkValid'] )			: "";
			
			if( $tWebsiteCharset == "" ) {
			
				$tErrors[]						= _("Website charset is missing!");
				$error							= 1;
				
			}
			
			if( $tLpwMailSender == "" ) {
				
				$tErrors[]						= _("Lost password mail sender address is missing!");
				$error							= 1;
				
			} elseif( ! check_email( $tLpwMailSender ) ) {
				
				$tErrors[]						= sprintf( _("Lost password mail sender address %s is not a valid email address!" ), $tLpwMailSender );
				$error							= 1;
				
			}
			
			if( $tLpwLinkValid == "" ) {
				
				$tErrors[]						= _("Lost password days link valid missing!");
				$error							= 1;
				
			} elseif( ! is_numeric( $tLpwLinkValid) ) {
				
				$tErrors[]						= _("Lost password days link valid must be numeric!" );
				$error							= 1;
				
			}
		
			if( $error == 0 ) {
				
				$_SESSION['svn_inst']['websiteCharset']			= $tWebsiteCharset;
				$_SESSION['svn_inst']['lpwMailSender']			= $tLpwMailSender;
				$_SESSION['svn_inst']['lpwLinkValid']			= $tLpwLinkValid;
				
				$tUsername					= isset( $_SESSION['svn_inst']['username'] ) ? $_SESSION['svn_inst']['username'] : "";
				$tPassword					= isset( $_SESSION['svn_inst']['password'] ) ? $_SESSION['svn_inst']['password'] : "";
				$tPassword2					= isset( $_SESSION['svn_inst']['password2'] ) ? $_SESSION['svn_inst']['password2'] : "";
				$tGivenname					= isset( $_SESSION['svn_inst']['givenname'] ) ? $_SESSION['svn_inst']['givenname'] : "";
				$tName						= isset( $_SESSION['svn_inst']['name'] ) ? $_SESSION['svn_inst']['name'] : "";
				$tAdminEmail				= isset( $_SESSION['svn_inst']['adminEmail'] ) ? $_SESSION['svn_inst']['adminEmail'] : "";
				
				$_SESSION['svn_inst']['page']++;
				include ("../templates/install_page_4.tpl");
				exit;
			
			} else {
				
				include ("../templates/install_page_3.tpl");
				exit;
				
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "4" ) {
			
			$error							= 0;
			$tUsername						= isset( $_POST['fUsername'] ) 				? ( $_POST['fUsername'] )				: "";
			$tPassword						= isset( $_POST['fPassword'] )				? ( $_POST['fPassword'] )				: "";
			$tPassword2						= isset( $_POST['fPassword2'] )				? ( $_POST['fPassword2'] )				: "";
			$tGivenname						= isset( $_POST['fGivenname'] ) 			? ( $_POST['fGivenname'] )				: "";
			$tName							= isset( $_POST['fName'] )					? ( $_POST['fName'] )					: "";
			$tAdminEmail					= isset( $_POST['fAdminEmail'] )			? ( $_POST['fAdminEmail'] )				: "";
			
			if( $tUsername == "" ) {
				
				$tErrors[]						= _("Administrator username is missing!" );
				$error							= 1;
				
			}
			
			if( ($tPassword == "") or ($tPassword2 == "") ) {
				
				$tErrors[]						= _("Administrator password is missing!" );
				$error							= 1;
				
			} elseif( $tPassword != $tPassword2 ) {
				
				$tErrors[]						= _("Administrator passwords do not match!" );
				$error							= 1;
				
			} elseif( checkPasswordPolicy( $tPassword, 'y' ) == 0 ) {
				
				$tErrors[]						= _("Administrator password is not strong enough!" );
				$error							= 1;
				
			} 
			
			if( $tName == "" ) {
				
				$tErrors[]						= _("Administrator name is missing!" );
				$error							= 1;
				
			} 
			
			if( $tAdminEmail == "" ) {
				
				$tErrors[]						= _("Administrator email address is missing!" );
				$error							= 1;
				
			} elseif( ! check_email($tAdminEmail) ) {
				
				$tErrors[]						= sprintf( _("Administrator email address %s is not a valid email address!"), $tAdminEmail );
				$error							= 1;
				
			}
		
			if( $error == 0 ) {
				
				$_SESSION['svn_inst']['username']				= $tUsername; 
				$_SESSION['svn_inst']['password']				= $tPassword;
				$_SESSION['svn_inst']['password2']				= $tPassword2;
				$_SESSION['svn_inst']['givenname']				= $tGivenname;
				$_SESSION['svn_inst']['name']					= $tName;
				$_SESSION['svn_inst']['adminEmail']				= $tAdminEmail;
			   	
			   	$tUseSvnAccessFile			= isset( $_SESSION['svn_inst']['useSvnAccessFile'] ) 	? $_SESSION['svn_inst']['useSvnAccessFile'] 	: "";
				$tSvnAccessFile				= isset( $_SESSION['svn_inst']['svnAccessFile'] ) 		? $_SESSION['svn_inst']['svnAccessFile'] 		: "";
				$tAccessControlLevel		= isset( $_SESSION['svn_inst']['accessControlLevel'] ) 	? $_SESSION['svn_inst']['accessControlLevel'] 	: "dirs";
				$tUseAuthUserFile			= isset( $_SESSION['svn_inst']['useAuthUserFile'] ) 	? $_SESSION['svn_inst']['useAuthUserFile'] 		: "";
				$tAuthUserFile				= isset( $_SESSION['svn_inst']['authUserFile'] ) 		? $_SESSION['svn_inst']['authUserFile'] 		: "";
				$tSvnCommand				= isset( $_SESSION['svn_inst']['svnCommand'] ) 			? $_SESSION['svn_inst']['svnCommand'] 			: "";
				$tSvnadminCommand			= isset( $_SESSION['svn_inst']['svnadminCommand'] ) 	? $_SESSION['svn_inst']['svnadminCommand'] 		: "";
				$tGrepCommand				= isset( $_SESSION['svn_inst']['grepCommand'] ) 		? $_SESSION['svn_inst']['grepCommand'] 			: "";
				$tViewvcConfig				= isset( $_SESSION['svn_inst']['viewvcConfig'] ) 		? $_SESSION['svn_inst']['viewvcConfig'] 		: "";
				$tViewvcConfigDir			= isset( $_SESSION['svn_inst']['viewvcConfigDir'] ) 	? $_SESSION['svn_inst']['viewvcConfigDir'] 		: "";
				$tViewvcAlias				= isset( $_SESSION['svn_inst']['viewvcAlias'] ) 		? $_SESSION['svn_inst']['viewvcAlias'] 			: "/viewvc"; 
				$tViewvcApacheReload		= isset( $_SESSION['svn_inst']['viewvcApacheReload'] ) 	? $_SESSION['svn_inst']['viewvcApacheReload'] 	: "";
				$tViewvcRealm				= isset( $_SESSION['svn_inst']['viewvcRealm'] ) 		? $_SESSION['svn_inst']['viewvcRealm'] 			: "ViewVC Access Control";
				$tPerRepoFiles				= isset( $_SESSION['svn_inst']['perRepoFiles'] ) 		? $_SESSION['svn_inst']['perRepoFiles'] 		: "";
				
				# common locations where to find grep and svn under linux/unix
			   	$svnpath					= array('/usr/local/bin/svn', '/usr/bin/svn', '/bin/svn');
			   	$svnadminpath				= array('/usr/local/bin/svnadmin', '/usr/bin/svnadmin', '/bin/svnadmin');
			   	$greppath					= array('/usr/local/bin/grep', '/usr/bin/grep', '/bin/grep');
			   	$apachepath					= array('/etc/init.d/httpd', '/etc/init.d/apache2', '/etc/init.d/apache');
			   	
			   	for( $i = 0; $i < count($svnpath); $i++ ) {
			   		if( file_exists( $svnpath[$i] ) ) {
			   			if( $tSvnCommand == "" ) {
			   				$tSvnCommand		= $svnpath[$i];
			   			}
			   		}
			   	}
			   	
			   	for( $i = 0; $i < count($svnadminpath); $i++ ) {
			   		if( file_exists( $svnadminpath[$i] ) ) {
			   			if( $tSvnadminCommand == "" ) {
			   				$tSvnadminCommand		= $svnadminpath[$i];
			   			}
			   		}
			   	}
			   	
			   	for( $i=0; $i < count($greppath ); $i++ ) {
			   		if( file_exists( $greppath[$i] ) ) {
			   			if( $tGrepCommand == "" ) {
			   				$tGrepCommand		= $greppath[$i];
			   			}
			   		}
			   	}
			   
			   	for( $i=0; $i < count($apachepath); $i++ ) {
			   		if( file_exists($apachepath[$i] ) ) {
			   			if( $tViewvcApacheReload == "" ) {
			   				$tViewvcApacheReload= "sudo ".$apachepath[$i]." graceful";
			   			}
			   		}
			   	}
				
				if( $tUseAuthUserFile == "YES" ) {
					$tUseAuthUserFileYes		= "checked";
					$tUseAuthUSerFileNo			= "";	
				} else {
					$tUseAuthUserFileYes		= "";
					$tUseAuthUserFileNo			= "checked";
				}
				
				if( $tUseSvnAccessFile == "YES" ) {
					$tUseSvnAccessFileYes		= "checked";
					$tUseSvnAccessFileNo		= "";
				} else {
					$tUseSvnAccessFileYes		= "";
					$tUseSvnAccessFileNo		= "checked";
				}
				
				if( $tAccessControlLevel == "dirs" ) {
					$tAccessControlLevelDirs	= "checked";
					$tAccessControlLevelFiles	= "";
				} else {
					$tAccessControlLevelDirs	= "";
					$tAccessControlLevelFiles	= "checked";
				}
				
				if( $tPerRepoFiles == "YES" ) {
					$tPerRepoFilesYes			= "checked";
					$tPerRepoFilesNo			= "";
				} else {
					$tPerRepoFilesYes			= "";
					$tPerRepoFilesNo			= "checked";
				}
				
				if( $tViewvcConfig == "YES" ) {
					$tViewvcConfigYes			= "checked";
					$tViewvcConfigNo			= "";
				} else {
					$tViewvcConfigYes			= "";
					$tViewvcConfigNo			= "checked";
				}
				
				$_SESSION['svn_inst']['page']++;
				include ("../templates/install_page_5.tpl");
				exit;
				
			} else {
				
				if( $tUseAuthUserFile == "YES" ) {
					$tUseAuthUserFileYes		= "checked";
					$tUseAuthUSerFileNo			= "";	
				} else {
					$tUseAuthUserFileYes		= "";
					$tUseAuthUserFileNo			= "checked";
				}
				
				if( $tUseSvnAccessFile == "YES" ) {
					$tUseSvnAccessFileYes		= "checked";
					$tUseSvnAccessFileNo		= "";
				} else {
					$tUseSvnAccessFileYes		= "";
					$tUseSvnAccessFileNo		= "checked";
				}
				
				if( $tAccessControlLevel == "dirs" ) {
					$tAccessControlLevelDirs	= "checked";
					$tAccessControlLevelFiles	= "";
				} else {
					$tAccessControlLevelDirs	= "";
					$tAccessControlLevelFiles	= "checked";
				}
				
				if( $tPerRepoFiles == "YES" ) {
					$tPerRepoFilesYes			= "checked";
					$tPerRepoFilesNo			= "";
				} else {
					$tPerRepoFilesYes			= "";
					$tPerRepoFilesNo			= "checked";
				}
				
				if( $tViewvcConfig == "YES" ) {
					$tViewvcConfigYes			= "checked";
					$tViewvcConfigNo			= "";
				} else {
					$tViewvcConfigYes			= "";
					$tViewvcConfigNo			= "checked";
				}
				
				include ("../templates/install_page_4.tpl");
				exit;
				
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "5" ) {
   	
   			$error							= 0;
			$tUseSvnAccessFile				= isset( $_POST['fUseSvnAccessFile'] )		? ( $_POST['fUseSvnAccessFile'] )		: "";
			$tSvnAccessFile					= isset( $_POST['fSvnAccessFile'] )			? ( $_POST['fSvnAccessFile'] )			: "";
			$tAccessControlLevel			= isset( $_POST['fAccessControlLevel'] )	? ( $_POST['fAccessControlLevel'] )		: "";
			$tUseAuthUserFile				= isset( $_POST['fUseAuthUserFile'] )		? ( $_POST['fUseAuthUserFile'] )		: "";
			$tAuthUserFile					= isset( $_POST['fAuthUserFile'] )			? ( $_POST['fAuthUserFile'] )			: "";
			$tSvnCommand					= isset( $_POST['fSvnCommand'] )			? ( $_POST['fSvnCommand'] )				: "";
			$tSvnadminCommand				= isset( $_POST['fSvnadminCommand'] )		? ( $_POST['fSvnadminCommand'] )		: "";
			$tGrepCommand					= isset( $_POST['fGrepCommand'] )			? ( $_POST['fGrepCommand'] )			: "";
			$tViewvcConfig					= isset( $_POST['fViewvcConfig'] )			? ( $_POST['fViewvcConfig'] )			: "";
			$tViewvcConfigDir				= isset( $_POST['fViewvcConfigDir'] ) 		? ( $_POST['fViewvcConfigDir'] )		: "";
			$tViewvcAlias					= isset( $_POST['fViewvcAlias'] )			? ( $_POST['fViewvcAlias'] )			: "";
			$tViewvcApacheReload			= isset( $_POST['fViewvcApacheReload'] )	? ( $_POST['fViewvcApacheReload'] )		: "";
			$tViewvcRealm					= isset( $_POST['fViewvcRealm'] )			? ( $_POST['fViewvcRealm'] )			: ""; 
			$tPerRepoFiles					= isset( $_POST['fPerRepoFiles'] )			? ( $_POST['fPerRepoFiles'] )			: "";
			
			if( $tViewvcConfig == "YES" ) {
		
				if( $tViewvcConfigDir == "" ) {
					
					$tErrors[]					= _("ViewVC configuration directory is missing!");
					$error						= 1;
					
				} 
				
				if( $tViewvcAlias == "" ) {
					
					$tErrors[]					= _("ViewVC webserver alias is missing!");
					$error						= 1;
					
				} 
				
				if( $tViewvcRealm == "" ) {
					
					$tErrors[]					= _("ViewVC realm is missing!" );
					$error						= 1;
					
				}
			}
			
			if( $tSvnCommand == "" ) {
				
				$tErrors[]						= _("SVN command is missing!" );
				$error							= 1;
				
			} 
			
			if( $tSvnadminCommand == "" ) { 
			
				$tErrors[]						= _("Svnadmin command missing!" );
				$error							= 1;
				
			} 
			
			if( $tGrepCommand == "" ) {
				
				$tErrors[]						= _("Grep command is missinbg!" );
				$error							= 1;
				
			} 
			
			if( $error == 0 ) {
				
				$_SESSION['svn_inst']['useSvnAccessFile']				= $tUseSvnAccessFile;
				$_SESSION['svn_inst']['svnAccessFile']					= $tSvnAccessFile; 
				$_SESSION['svn_inst']['accessControlLevel']				= $tAccessControlLevel; 
				$_SESSION['svn_inst']['useAuthUserFile']				= $tUseAuthUserFile; 
				$_SESSION['svn_inst']['authUserFile']					= $tAuthUserFile; 
				$_SESSION['svn_inst']['svnCommand']						= $tSvnCommand; 
				$_SESSION['svn_inst']['svnadminCommand']				= $tSvnadminCommand; 
				$_SESSION['svn_inst']['grepCommand']					= $tGrepCommand; 
				$_SESSION['svn_inst']['viewvcConfig']					= $tViewvcConfig; 
				$_SESSION['svn_inst']['viewvcConfigDir']				= $tViewvcConfigDir; 
				$_SESSION['svn_inst']['viewvcAlias']					= $tViewvcAlias; 
				$_SESSION['svn_inst']['viewvcApacheReload']				= $tViewvcApacheReload; 
				$_SESSION['svn_inst']['viewvcRealm']					= $tViewvcRealm; 
				$_SESSION['svn_inst']['perRepoFiles']					= $tPerRepoFiles;  
				
				$tLogging						= isset( $_SESSION['svn_inst']['logging'] ) 		? $_SESSION['svn_inst']['logging'] 			: "YES";
				$tJavaScript					= isset( $_SESSION['svn_inst']['javaScript'] ) 		? $_SESSION['svn_inst']['javaScript'] 		: "YES";
				$tPageSize						= isset( $_SESSION['svn_inst']['pageSize'] ) 		? $_SESSION['svn_inst']['pageSize'] 		: "30";
				$tMinAdminPwSize				= isset( $_SESSION['svn_inst']['minAdminPwSize'] ) 	? $_SESSION['svn_inst']['minAdminPwSize'] 	: "14";
				$tMinUserPwSize					= isset( $_SESSION['svn_inst']['minUserPwSize'] ) 	? $_SESSION['svn_inst']['minUserPwSize'] 	: "8"; 
				$tUseMd5						= isset( $_SESSION['svn_inst']['useMd5'] ) 			? $_SESSION['svn_inst']['useMd5'] 			: "md5";
				
				if( $tJavaScript == "YES" ) {
					$tJavaScriptYes				= "checked";
					$tJavaScriptNo				= "";
				} else {
					$tJavaScriptYes				= "";
					$tJavaScriptNo				= "checked";
				}
				
				if( $tLogging == "YES" ) {
					$tLoggingYes				= "checked";
					$tLoggingNo					= "";
				} else {
					$tLoggingYes				= "";
					$tLoggingNo					= "checked";
				}
				
				if( $tUseMd5 == "md5" ) {
					$tMd5Yes					= "checked";
					$tMd5No						= "";
					$CONF['pwcrypt']			= "md5";
				} else {
					$tMd5Yes					= "";
					$tMd5No						= "checked";
					$CONF['pwcrypt']			= "crypt";
				}
		
				$_SESSION['svn_inst']['page']++;
				include ("../templates/install_page_6.tpl");
				exit;
			
			} else {
				
				if( $tUseAuthUserFile == "YES" ) {
					$tUseAuthUserFileYes		= "checked";
					$tUseAuthUSerFileNo			= "";	
				} else {
					$tUseAuthUserFileYes		= "";
					$tUseAuthUserFileNo			= "checked";
				}
				
				if( $tUseSvnAccessFile == "YES" ) {
					$tUseSvnAccessFileYes		= "checked";
					$tUseSvnAccessFileNo		= "";
				} else {
					$tUseSvnAccessFileYes		= "";
					$tUseSvnAccessFileNo		= "checked";
				}
				
				if( $tAccessControlLevel == "dirs" ) {
					$tAccessControlLevelDirs	= "checked";
					$tAccessControlLevelFiles	= "";
				} else {
					$tAccessControlLevelDirs	= "";
					$tAccessControlLevelFiles	= "checked";
				}
				
				if( $tPerRepoFiles == "YES" ) {
					$tPerRepoFilesYes			= "checked";
					$tPerRepoFilesNo			= "";
				} else {
					$tPerRepoFilesYes			= "";
					$tPerRepoFilesNo			= "checked";
				}
				
				if( $tViewvcConfig == "YES" ) {
					$tViewvcConfigYes			= "checked";
					$tViewvcConfigNo			= "";
				} else {
					$tViewvcConfigYes			= "";
					$tViewvcConfigNo			= "checked";
				}
				
				include ("../templates/install_page_5.tpl");
				exit;
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "6" ) {
			
			$error							= 0;
			$tLogging						= isset( $_POST['fLogging'] )				? ( $_POST['fLogging'] )				: "";
			$tJavaScript					= isset( $_POST['fJavaScript'] )			? ( $_POST['fJavaScript'] )				: "";
			$tPageSize						= isset( $_POST['fPageSize'] )				? ( $_POST['fPageSize'] )				: 30;
			$tMinAdminPwSize				= isset( $_POST['fMinAdminPwSize'] )		? ( $_POST['fMinAdminPwSize'] )			: 14;
			$tMinUserPwSize					= isset( $_POST['fMinUserPwSize'] 	)		? ( $_POST['fMinUserPwSize'] )			: 8;
			$tUseMd5						= isset( $_POST['fUseMd5'] )				? ( $_POST['fUseMd5'] ) 				: "";
			
			if( $tPageSize == "" ) {
				
				$tErrors[]						= _("Page size is missing!" );
				$error							= 1;
				
			} 
			
			if( ! is_numeric($tPageSize) ) {
				
				$tErrors[]						= _("Page size is not numeric!" );
				$error							= 1;
				
			} 
			
			if( ! is_numeric( $tMinAdminPwSize) ) {
				
				$tErrors[]						= _("Minimal administrator password length is not numeric!" );
				$error							= 1;
				
			} 
			
			if( ! is_numeric( $tMinUserPwSize ) ) {
				
				$tErrors[]						= _("Minimal user password length is not numeric!" );
				$error							= 1;
				
			}
		
			if( $error == 0 ) {
				
				$_SESSION['svn_inst']['logging']					= $tLogging;
				$_SESSION['svn_inst']['javaScript']					= $tJavaScript;
				$_SESSION['svn_inst']['pageSize']					= $tPageSize;
				$_SESSION['svn_inst']['minAdminPwSize']				= $tMinAdminPwSize;
				$_SESSION['svn_inst']['minUserPwSize']				= $tMinUserPwSize;
				$_SESSION['svn_inst']['useMd5']						= $tUseMd5;
				
			} else {
				
				if( $tJavaScript == "YES" ) {
					$tJavaScriptYes				= "checked";
					$tJavaScriptNo				= "";
				} else {
					$tJavaScriptYes				= "";
					$tJavaScriptNo				= "checked";
				}
				
				if( $tLogging == "YES" ) {
					$tLoggingYes				= "checked";
					$tLoggingNo					= "";
				} else {
					$tLoggingYes				= "";
					$tLoggingNo					= "checked";
				}
				
				if( $tUseMd5 == "md5" ) {
					$tMd5Yes					= "checked";
					$tMd5No						= "";
					$CONF['pwcrypt']			= "md5";
				} else {
					$tMd5Yes					= "";
					$tMd5No						= "checked";
					$CONF['pwcrypt']			= "crypt";
				}
				
				include ("../templates/install_page_6.tpl");
				exit;
			}
			
		} else {
						
		}
		
	} elseif( isset( $_POST['fSubmit_back'] ) ) {
		
		if( $_SESSION['svn_inst']['page'] == "1" ) {
			
		} elseif( $_SESSION['svn_inst']['page'] == "2" ) {
			
			$error							= 0;
			$tDatabaseHost					= isset( $_POST['fDatabaseHost'] )			? ( $_POST['fDatabaseHost'] )			: "";
			$tDatabaseUser					= isset( $_POST['fDatabaseUser'] )			? ( $_POST['fDatabaseUser'] )			: "";
			$tDatabasePassword				= isset( $_POST['fDatabasePassword'] )		? ( $_POST['fDatabasePassword'] )		: "";
			$tDatabaseName					= isset( $_POST['fDatabaseName'] )			? ( $_POST['fDatabaseName'] )			: "";
			$tDatabaseSchema				= isset( $_POST['fDatabaseSchema'] )		? ( $_POST['fDatabaseSchema'] )			: "";
			$tDatabaseTablespace			= isset( $_POST['fDatabaseTablespace'] )	? ( $_POST['fDatabaseTablespace'] )		: "";
			$tDatabaseCharset				= isset( $_POST['fDatabaseCharset'] )		? ( $_POST['fDatabaseCharset'] )		: "";
			$tDatabaseCollation				= isset( $_POST['fDatabaseCollation'] )		? ( $_POST['fDatabaseCollation'] )		: "";
			
			if( $tDatabaseHost == "" ) {
			
				$tErrors[]					= _("Database host is missing!");
				$error						= 1;
				
			}
			
			if( $tDatabaseUser == "" ) {
				
				$tErrors[]					= _("Database user is missing!");
				$error						= 1;
				
			} 
			
			if( $tDatabaseName == "" ) {
				
				$tErrors[]					= _("Database name is missing!" );
				$error						= 1;
				
			} 
			
			if( $tDatabaseCharset == "" ) {
				
				$tErrors[]					= _("Database charset is missing!" );
				$error						= 1;
				
			} 
			
			if( $tDatabaseCollation == "" ) {
				
				$tErrors[]					= _("Database collation is missing!" );
				$error						= 1;
				
			}
			
			if( $error == 0 ) {
				$_SESSION['svn_inst']['databaseHost']		= $tDatabaseHost;
				$_SESSION['svn_inst']['databaseUser']		= $tDatabaseUser;
				$_SESSION['svn_inst']['databasePassword']	= $tDatabasePassword;
				$_SESSION['svn_inst']['databaseName']		= $tDatabaseName;
				$_SESSION['svn_inst']['databaseSchema']		= $tDatabaseSchema;
				$_SESSION['svn_inst']['databaseTablespace']	= $tDatabaseTablespace;
				$_SESSION['svn_inst']['databaseCharset']	= $tDatabaseCharset;
				$_SESSION['svn_inst']['databaseCollation']	= $tDatabaseCollation;
				
				$tCreateDatabaseTables		= isset( $_SESSION['svn_inst']['createDatabaseTables'] ) ? $_SESSION['svn_inst']['createDatabaseTables'] : ""; 
				$tDropDatabaseTables		= isset( $_SESSION['svn_inst']['dropDatabaseTables'] ) ? $_SESSION['svn_inst']['dropDatabaseTables'] : "";
				$tDatabase					= isset( $_SESSION['svn_inst']['database'] ) ? $_SESSION['svn_inst']['database'] : "";
				$tSessionInDatabase			= isset( $_SESSION['svn_inst']['sessionInDatabase'] ) ? $_SESSION['svn_inst']['sessionInDatabase'] : "";
				$tUseLdap					= isset( $_SESSION['svn_inst']['useLdap'] ) ? $_SESSION['svn_inst']['useLdap'] : "";
				$tLdapHost					= isset( $_SESSION['svn_inst']['ldapHost'] ) ? $_SESSION['svn_inst']['ldapHost'] : "";
				$tLdapPort					= isset( $_SESSION['svn_inst']['ldapPort'] ) ? $_SESSION['svn_inst']['ldapPort'] : "";
				$tLdapProtocol				= isset( $_SESSION['svn_inst']['ldapProtocol'] ) ? $_SESSION['svn_inst']['ldapProtocol'] : "";
				$tLdapBinddn				= isset( $_SESSION['svn_inst']['ldapBinddn'] ) ? $_SESSION['svn_inst']['ldapBinddn'] : "";
				$tLdapBindpw				= isset( $_SESSION['svn_inst']['ldapBindpw'] ) ? $_SESSION['svn_inst']['ldapBindpw'] : "";
				$tLdapUserdn				= isset( $_SESSION['svn_inst']['ldapUserdn'] ) ? $_SESSION['svn_inst']['ldapUserdn'] : "";
				$tLdapUserFilter			= isset( $_SESSION['svn_inst']['ldapUserFilter'] ) ? $_SESSION['svn_inst']['ldapUserFilter'] : "";
				$tLdapUserObjectclass		= isset( $_SESSION['svn_inst']['ldapUserObjectclass'] ) ? $_SESSION['svn_inst']['ldapUserObjectclass'] : "";
				$tLdapUserAdditionalFilter  = isset( $_SESSION['svn_inst']['ldapUserAdditionalFilter'] ) ? $_SESSION['svn_inst']['ldapUserAdditionalFilter'] : "";
				
				if( $tCreateDatabaseTables == "YES" ) {
					$tCreateDatabaseTablesYes	= "checked";
					$tCreateDatabaseTablesNo	= "";
				} else {
					$tCreateDatabaseTablesYes	= "";
					$tCreateDatabaseTablesNo	= "checked";
				}
				
				if( $tDropDatabaseTables == "YES" ) {
					$tDropDatabaseTablesYes		= "checked";
					$tDropDatabaseTablesNo		= "";
				} else {
					$tDropDatabaseTablesYes		= "";
					$tDropDatabaseTablesNo		= "checked";
				}
				
				if( $tDatabase == "mysql" ) {
					$tDatabaseMySQL				= "checked";
					$tDatabasePostgreSQL		= "";
					$tDatabaseOracle			= "";
				} elseif( $tDatabase == "postgres8" ) {
					$tDatabaseMySQL				= "";
					$tDatabasePostgreSQL		= "checked";
					$tDatabaseOracle			= "";
				} elseif( $tDatabase == "oci8" ) {
					$tDatabaseMySQL				= "";
					$tDatabasePostgreSQL		= "";
					$tDatabaseOracle			= "checked";
				} else {
					$tDatabaseMySQL				= "";
					$tDatabasePostgreSQL		= "";
				}
				
				if( $tSessionInDatabase == "YES" ) {
					$tSessionInDatabaseYes		= "checked";
					$tSessionInDatabaseNo		= "";
				} else {
					$tSessionInDatabaseYes		= "";
					$tSessionInDatabaseNo		= "checked";
				}
				
				if( $tUseLdap == "YES" ) {
					$tUseLdapYes				= "checked";
					$tUseLdapNo					= "";
				} else {
					$tUseLdapYes				= "";
					$tUseLdapNo					= "checked";
				}
				
				if( $tLdapProtocol == "3" ) {
					$tLdap3						= "checked";
					$tLdap2						= "";
				} else {
					$tLdap3						= "";
					$tLdap2						= "checked";
				}
				
				$_SESSION['svn_inst']['page']--;
				include ("../templates/install_page_1.tpl");
				exit;
			
			} else {
				
				$tCreateDatabaseTables		= isset( $_SESSION['svn_inst']['createDatabaseTables'] ) ? $_SESSION['svn_inst']['createDatabaseTables'] : ""; 
				$tDropDatabaseTables		= isset( $_SESSION['svn_inst']['dropDatabaseTables'] ) ? $_SESSION['svn_inst']['dropDatabaseTables'] : "";
				$tDatabase					= isset( $_SESSION['svn_inst']['database'] ) ? $_SESSION['svn_inst']['database'] : "";
				$tSessionInDatabase			= isset( $_SESSION['svn_inst']['sessionInDatabase'] ) ? $_SESSION['svn_inst']['sessionInDatabase'] : "";
				$tLdapAttrUid				= isset( $_SESSION['svn_inst']['ldapAttrUid'] ) ? $_SESSION['svn_inst']['ldapAttrUid'] : "uid";
				$tLdapAttrName				= isset( $_SESSION['svn_inst']['ldapAttrName'] ) ? $_SESSION['svn_inst']['ldapAttrName'] : "sn";
				$tLdapAttrGivenname			= isset( $_SESSION['svn_inst']['ldapAttrGivenname'] ) ? $_SESSION['svn_inst']['ldapAttrGivenname'] : "givenName";
				$tLdapAttrMail				= isset( $_SESSION['svn_inst']['ldapAttrMail'] ) ? $_SESSION['svn_inst']['ldapAttrMail'] : "mail";
				$tLdapAttrPassword			= isset( $_SESSION['svn_inst']['ldapAttrPassword'] ) ? $_SESSION['svn_inst']['ldapAttrPassword'] : "userpassword";
				
				if( $tCreateDatabaseTables == "YES" ) {
					$tCreateDatabaseTablesYes	= "checked";
					$tCreateDatabaseTablesNo	= "";
				} else {
					$tCreateDatabaseTablesYes	= "";
					$tCreateDatabaseTablesNo	= "checked";
				}
				
				if( $tDropDatabaseTables == "YES" ) {
					$tDropDatabaseTablesYes		= "checked";
					$tDropDatabaseTablesNo		= "";
				} else {
					$tDropDatabaseTablesYes		= "";
					$tDropDatabaseTablesNo		= "checked";
				}
				
				if( $tDatabase == "mysql" ) {
					$tDatabaseMySQL				= "checked";
					$tDatabasePostgreSQL		= "";
					$tDatabaseOracle			= "";
				} elseif( $tDatabase == "postgres8" ) {
					$tDatabaseMySQL				= "";
					$tDatabasePostgreSQL		= "checked";
					$tDatabaseOracle			= "";
				} elseif( $tDatabase == "oci8" ) {
					$tDatabaseMySQL				= "";
					$tDatabasePostgreSQL		= "";
					$tDatabaseOracle			= "checked";
				} else {
					$tDatabaseMySQL				= "";
					$tDatabasePostgreSQL		= "";
				}
				
				if( $tSessionInDatabase == "YES" ) {
					$tSessionInDatabaseYes		= "checked";
					$tSessionInDatabaseNo		= "";
				} else {
					$tSessionInDatabaseYes		= "";
					$tSessionInDatabaseNo		= "checked";
				}
				
				include ("../templates/install_page_2.tpl");
				exit;
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "3" ) {
			
			$error							= 0;
			$tWebsiteCharset				= isset( $_POST['fWebsiteCharset'] )		? ( $_POST['fWebsiteCharset'] )			: "";
			$tLpwMailSender					= isset( $_POST['fLpwMailSender'] )			? ( $_POST['fLpwMailSender'] )			: "";
			$tLpwLinkValid					= isset( $_POST['fLpwLinkValid'] ) 			? ( $_POST['fLpwLinkValid'] )			: "";
			
			if( $tWebsiteCharset == "" ) {
			
				$tErrors[]						= _("Website charset is missing!");
				$error							= 1;
				
			}
			
			if( $tLpwMailSender == "" ) {
				
				$tErrors[]						= _("Lost password mail sender address is missing!");
				$error							= 1;
				
			} elseif( ! check_email( $tLpwMailSender ) ) {
				
				$tErrors[]						= sprintf( _("Lost password mail sender address %s is not a valid email address!" ), $tLpwMailSender );
				$error							= 1;
				
			}
			
			if( $tLpwLinkValid == "" ) {
				
				$tErrors[]						= _("Lost password days link valid missing!");
				$error							= 1;
				
			} elseif( ! is_numeric( $tLpwLinkValid) ) {
				
				$tErrors[]						= _("Lost password days link valid must be numeric!" );
				$error							= 1;
				
			}
			
			if( $error == 0 ) {
				$_SESSION['svn_inst']['websiteCharset']			= $tWebsiteCharset;
				$_SESSION['svn_inst']['lpwMailSender']			= $tLpwMailSender;
				$_SESSION['svn_inst']['lpwLinkValid']			= $tLpwLinkValid;
				
				$tDatabaseHost				= isset( $_SESSION['svn_inst']['databaseHost'] ) 		? $_SESSION['svn_inst']['databaseHost'] 		: "";
				$tDatabaseUser				= isset( $_SESSION['svn_inst']['databaseUser'] ) 		? $_SESSION['svn_inst']['databaseUser'] 		: "";
				$tDatabasePassword			= isset( $_SESSION['svn_inst']['databasePassword'] ) 	? $_SESSION['svn_inst']['databasePassword'] 	: ""; 
				$tDatabaseName				= isset( $_SESSION['svn_inst']['databaseName'] ) 		? $_SESSION['svn_inst']['databaseName'] 		: "";
				$tDatabaseSchema			= isset( $_SESSION['svn_inst']['databaseSchema'] ) 		? $_SESSION['svn_inst']['databaseSchema'] 		: "";
				$tDatabaseTablespace		= isset( $_SESSION['svn_inst']['databaseTablespace'] ) 	? $_SESSION['svn_inst']['databaseTablespace'] 	: "";
				$tDatabaseCharset			= isset( $_SESSION['svn_inst']['databaseCharset'] ) 	? $_SESSION['svn_inst']['databaseCharset'] 		: $tDatabaseCharsetDefault;
				$tDatabaseCollation			= isset( $_SESSION['svn_inst']['databaseCollation'] ) 	? $_SESSION['svn_inst']['databaseCollation'] 	: $tDatabaseCollationDefault;
				
				$_SESSION['svn_inst']['page']--;
				include ("../templates/install_page_2.tpl");
				exit;
			
			} else {
				
				include ("../templates/install_page_3.tpl");
				exit;
				
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "4" ) {
			
			$error							= 0;
			$tUsername						= isset( $_POST['fUsername'] ) 				? ( $_POST['fUsername'] )				: "";
			$tPassword						= isset( $_POST['fPassword'] )				? ( $_POST['fPassword'] )				: "";
			$tPassword2						= isset( $_POST['fPassword2'] )				? ( $_POST['fPassword2'] )				: "";
			$tGivenname						= isset( $_POST['fGivenname'] ) 			? ( $_POST['fGivenname'] )				: "";
			$tName							= isset( $_POST['fName'] )					? ( $_POST['fName'] )					: "";
			$tAdminEmail					= isset( $_POST['fAdminEmail'] )			? ( $_POST['fAdminEmail'] )				: "";
			
			if( $tUsername == "" ) {
				
				$tErrors[]						= _("Administrator username is missing!" );
				$error							= 1;
				
			}
			
			if( ($tPassword == "") or ($tPassword2 == "") ) {
				
				$tErrors[]						= _("Administrator password is missing!" );
				$error							= 1;
				
			} elseif( $tPassword != $tPassword2 ) {
				
				$tErrors[]						= _("Administrator passwords do not match!" );
				$error							= 1;
				
			} elseif( checkPasswordPolicy( $tPassword, 'y' ) == 0 ) {
				
				$tErrors[]						= _("Administrator password is not strong enough!" );
				$error							= 1;
				
			} 
			
			if( $tName == "" ) {
				
				$tErrors[]						= _("Administrator name is missing!" );
				$error							= 1;
				
			} 
			
			if( $tAdminEmail == "" ) {
				
				$tErrors[]						= _("Administrator email address is missing!" );
				$error							= 1;
				
			} elseif( ! check_email($tAdminEmail) ) {
				
				$tErrors[]						= sprintf( _("Administrator email address %s is not a valid email address!"), $tAdminEmail );
				$error							= 1;
				
			}
			
			if( $error == 0 ) {
				$_SESSION['svn_inst']['username']				= $tUsername; 
				$_SESSION['svn_inst']['password']				= $tPassword;
				$_SESSION['svn_inst']['password2']				= $tPassword2;
				$_SESSION['svn_inst']['givenname']				= $tGivenname;
				$_SESSION['svn_inst']['name']					= $tName;
				$_SESSION['svn_inst']['adminEmail']				= $tAdminEmail;
				
				$tWebsiteCharset			= isset( $_SESSION['svn_inst']['websiteCharset'] ) 	? $_SESSION['svn_inst']['websiteCharset'] 	: "iso8859-15";
				$tLpwMailSender				= isset( $_SESSION['svn_inst']['lpwMailSender'] ) 	? $_SESSION['svn_inst']['lpwMailSender'] 	: "";
				$tLpwLinkValid				= isset( $_SESSION['svn_inst']['lpwLinkValid'] ) 	? $_SESSION['svn_inst']['lpwLinkValid'] 	: "";
				
				$_SESSION['svn_inst']['page']--;
				include ("../templates/install_page_3.tpl");
				exit;
			
			} else {
				
				include ("../templates/install_page_4.tpl");
				exit;
				
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "5" ) {
			
			$error							= 0;
			$tUseSvnAccessFile				= isset( $_POST['fUseSvnAccessFile'] )		? ( $_POST['fUseSvnAccessFile'] )		: "";
			$tSvnAccessFile					= isset( $_POST['fSvnAccessFile'] )			? ( $_POST['fSvnAccessFile'] )			: "";
			$tAccessControlLevel			= isset( $_POST['fAccessControlLevel'] )	? ( $_POST['fAccessControlLevel'] )		: "";
			$tUseAuthUserFile				= isset( $_POST['fUseAuthUserFile'] )		? ( $_POST['fUseAuthUserFile'] )		: "";
			$tAuthUserFile					= isset( $_POST['fAuthUserFile'] )			? ( $_POST['fAuthUserFile'] )			: "";
			$tSvnCommand					= isset( $_POST['fSvnCommand'] )			? ( $_POST['fSvnCommand'] )				: "";
			$tSvnadminCommand				= isset( $_POST['fSvnadminCommand'] )		? ( $_POST['fSvnadminCommand'] )		: "";
			$tGrepCommand					= isset( $_POST['fGrepCommand'] )			? ( $_POST['fGrepCommand'] )			: "";
			$tViewvcConfig					= isset( $_POST['fViewvcConfig'] )			? ( $_POST['fViewvcConfig'] )			: "";
			$tViewvcConfigDir				= isset( $_POST['fViewvcConfigDir'] ) 		? ( $_POST['fViewvcConfigDir'] )		: "";
			$tViewvcAlias					= isset( $_POST['fViewvcAlias'] )			? ( $_POST['fViewvcAlias'] )			: "/viewvc";
			$tViewvcApacheReload			= isset( $_POST['fViewvcApacheReload'] )	? ( $_POST['fViewvcApacheReload'] )		: "";
			$tViewvcRealm					= isset( $_POST['fViewvcRealm'] )			? ( $_POST['fViewvcRealm'] )			: "ViewVC Access Control"; 
			$tPerRepoFiles					= isset( $_POST['fPerRepoFiles'] )			? ( $_POST['fPerRepoFiles'] )			: "";
			
			if( $tViewvcConfig == "YES" ) {
		
				if( $tViewvcConfigDir == "" ) {
					
					$tErrors[]					= _("ViewVC configuration directory is missing!");
					$error						= 1;
					
				} 
				
				if( $tViewvcAlias == "" ) {
					
					$tErrors[]					= _("ViewVC webserver alias is missing!");
					$error						= 1;
					
				} 
				
				if( $tViewvcRealm == "" ) {
					
					$tErrors[]					= _("ViewVC realm is missing!" );
					$error						= 1;
					
				}
			}
			
			if( $tSvnCommand == "" ) {
				
				$tErrors[]						= _("SVN command is missing!" );
				$error							= 1;
				
			} 
			
			if( $tSvnadminCommand == "" ) { 
			
				$tErrors[]						= _("Svnadmin command missing!" );
				$error							= 1;
				
			} 
			
			if( $tGrepCommand == "" ) {
				
				$tErrors[]						= _("Grep command is missinbg!" );
				$error							= 1;
				
			} 
			
			if( $error == 0 ) {
				
				$_SESSION['svn_inst']['useSvnAccessFile']				= $tUseSvnAccessFile;
				$_SESSION['svn_inst']['svnAccessFile']					= $tSvnAccessFile; 
				$_SESSION['svn_inst']['accessControlLevel']				= $tAccessControlLevel; 
				$_SESSION['svn_inst']['useAuthUserFile']				= $tUseAuthUserFile; 
				$_SESSION['svn_inst']['authUserFile']					= $tAuthUserFile; 
				$_SESSION['svn_inst']['svnCommand']						= $tSvnCommand; 
				$_SESSION['svn_inst']['svnadminCommand']				= $tSvnadminCommand; 
				$_SESSION['svn_inst']['grepCommand']					= $tGrepCommand; 
				$_SESSION['svn_inst']['viewvcConfig']					= $tViewvcConfig; 
				$_SESSION['svn_inst']['viewvcConfigDir']				= $tViewvcConfigDir; 
				$_SESSION['svn_inst']['viewvcAlias']					= $tViewvcAlias; 
				$_SESSION['svn_inst']['viewvcApacheReload']				= $tViewvcApacheReload; 
				$_SESSION['svn_inst']['viewvcRealm']					= $tViewvcRealm; 
				$_SESSION['svn_inst']['perRepoFiles']					= $tPerRepoFiles;  
				
				$tUsername						= isset( $_SESSION['svn_inst']['username'] ) ? $_SESSION['svn_inst']['username'] : "";
				$tPassword						= isset( $_SESSION['svn_inst']['password'] ) ? $_SESSION['svn_inst']['password'] : "";
				$tPassword2						= isset( $_SESSION['svn_inst']['password2'] ) ? $_SESSION['svn_inst']['password2'] : "";
				$tGivenname						= isset( $_SESSION['svn_inst']['givenname'] ) ? $_SESSION['svn_inst']['givenname'] : "";
				$tName							= isset( $_SESSION['svn_inst']['name'] ) ? $_SESSION['svn_inst']['name'] : "";
				$tAdminEmail					= isset( $_SESSION['svn_inst']['adminEmail'] ) ? $_SESSION['svn_inst']['adminEmail'] : "";
				
				if( $tUseAuthUserFile == "YES" ) {
					$tUseAuthUserFileYes		= "checked";
					$tUseAuthUSerFileNo			= "";	
				} else {
					$tUseAuthUserFileYes		= "";
					$tUseAuthUserFileNo			= "checked";
				}
				
				if( $tUseSvnAccessFile == "YES" ) {
					$tUseSvnAccessFileYes		= "checked";
					$tUseSvnAccessFileNo		= "";
				} else {
					$tUseSvnAccessFileYes		= "";
					$tUseSvnAccessFileNo		= "checked";
				}
				
				if( $tAccessControlLevel == "dirs" ) {
					$tAccessControlLevelDirs	= "checked";
					$tAccessControlLevelFiles	= "";
				} else {
					$tAccessControlLevelDirs	= "";
					$tAccessControlLevelFiles	= "checked";
				}
				
				if( $tPerRepoFiles == "YES" ) {
					$tPerRepoFilesYes			= "checked";
					$tPerRepoFilesNo			= "";
				} else {
					$tPerRepoFilesYes			= "";
					$tPerRepoFilesNo			= "checked";
				}
				
				if( $tViewvcConfig == "YES" ) {
					$tViewvcConfigYes			= "checked";
					$tViewvcConfigNo			= "";
				} else {
					$tViewvcConfigYes			= "";
					$tViewvcConfigNo			= "checked";
				}
				
				$_SESSION['svn_inst']['page']--;
				include ("../templates/install_page_4.tpl");
				exit;
				
			} else {
				
				if( $tUseAuthUserFile == "YES" ) {
					$tUseAuthUserFileYes		= "checked";
					$tUseAuthUSerFileNo			= "";	
				} else {
					$tUseAuthUserFileYes		= "";
					$tUseAuthUserFileNo			= "checked";
				}
				
				if( $tUseSvnAccessFile == "YES" ) {
					$tUseSvnAccessFileYes		= "checked";
					$tUseSvnAccessFileNo		= "";
				} else {
					$tUseSvnAccessFileYes		= "";
					$tUseSvnAccessFileNo		= "checked";
				}
				
				if( $tAccessControlLevel == "dirs" ) {
					$tAccessControlLevelDirs	= "checked";
					$tAccessControlLevelFiles	= "";
				} else {
					$tAccessControlLevelDirs	= "";
					$tAccessControlLevelFiles	= "checked";
				}
				
				if( $tPerRepoFiles == "YES" ) {
					$tPerRepoFilesYes			= "checked";
					$tPerRepoFilesNo			= "";
				} else {
					$tPerRepoFilesYes			= "";
					$tPerRepoFilesNo			= "checked";
				}
				
				if( $tViewvcConfig == "YES" ) {
					$tViewvcConfigYes			= "checked";
					$tViewvcConfigNo			= "";
				} else {
					$tViewvcConfigYes			= "";
					$tViewvcConfigNo			= "checked";
				}
				
				include ("../templates/install_page_5.tpl");
				exit;
				
			}
			
		} elseif( $_SESSION['svn_inst']['page'] == "6" ) {
			
			$error							= 0;
			$tLogging						= isset( $_POST['fLogging'] )				? ( $_POST['fLogging'] )				: "";
			$tJavaScript					= isset( $_POST['fJavaScript'] )			? ( $_POST['fJavaScript'] )				: "";
			$tPageSize						= isset( $_POST['fPageSize'] )				? ( $_POST['fPageSize'] )				: 30;
			$tMinAdminPwSize				= isset( $_POST['fMinAdminPwSize'] )		? ( $_POST['fMinAdminPwSize'] )			: 14;
			$tMinUserPwSize					= isset( $_POST['fMinUserPwSize'] 	)		? ( $_POST['fMinUserPwSize'] )			: 8;
			$tUseMd5						= isset( $_POST['fUseMd5'] )				? ( $_POST['fUseMd5'] ) 				: "";
			
			if( $tPageSize == "" ) {
				
				$tErrors[]						= _("Page size is missing!" );
				$error							= 1;
				
			} 
			
			if( ! is_numeric($tPageSize) ) {
				
				$tErrors[]						= _("Page size is not numeric!" );
				$error							= 1;
				
			} 
			
			if( ! is_numeric( $tMinAdminPwSize) ) {
				
				$tErrors[]						= _("Minimal administrator password length is not numeric!" );
				$error							= 1;
				
			} 
			
			if( ! is_numeric( $tMinUserPwSize ) ) {
				
				$tErrors[]						= _("Minimal user password length is not numeric!" );
				$error							= 1;
				
			}
			
			if( $error == 0 ) {
				
				$_SESSION['svn_inst']['logging']					= $tLogging;
				$_SESSION['svn_inst']['javaScript']					= $tJavaScript;
				$_SESSION['svn_inst']['pageSize']					= $tPageSize;
				$_SESSION['svn_inst']['minAdminPwSize']				= $tMinAdminPwSize;
				$_SESSION['svn_inst']['minUserPwSize']				= $tMinUserPwSize;
				$_SESSION['svn_inst']['useMd5']						= $tUseMd5;
			
				$tUseSvnAccessFile			= isset( $_SESSION['svn_inst']['useSvnAccessFile'] ) 	? $_SESSION['svn_inst']['useSvnAccessFile'] 	: "";
				$tSvnAccessFile				= isset( $_SESSION['svn_inst']['svnAccessFile'] ) 		? $_SESSION['svn_inst']['svnAccessFile'] 		: "";
				$tAccessControlLevel		= isset( $_SESSION['svn_inst']['accessControlLevel'] ) 	? $_SESSION['svn_inst']['accessControlLevel'] 	: "";
				$tUseAuthUserFile			= isset( $_SESSION['svn_inst']['useAuthUserFile'] ) 	? $_SESSION['svn_inst']['useAuthUserFile'] 		: "";
				$tAuthUserFile				= isset( $_SESSION['svn_inst']['authUserFile'] ) 		? $_SESSION['svn_inst']['authUserFile'] 		: "";
				$tSvnCommand				= isset( $_SESSION['svn_inst']['svnCommand'] ) 			? $_SESSION['svn_inst']['svnCommand'] 			: "";
				$tSvnadminCommand			= isset( $_SESSION['svn_inst']['svnadminCommand'] ) 	? $_SESSION['svn_inst']['svnadminCommand'] 		: "";
				$tGrepCommand				= isset( $_SESSION['svn_inst']['grepCommand'] ) 		? $_SESSION['svn_inst']['grepCommand'] 			: "";
				$tViewvcConfig				= isset( $_SESSION['svn_inst']['viewvcConfig'] ) 		? $_SESSION['svn_inst']['viewvcConfig'] 		: "";
				$tViewvcConfigDir			= isset( $_SESSION['svn_inst']['viewvcConfigDir'] ) 	? $_SESSION['svn_inst']['viewvcConfigDir'] 		: "";
				$tViewvcAlias				= isset( $_SESSION['svn_inst']['viewvcAlias'] ) 		? $_SESSION['svn_inst']['viewvcAlias'] 			: ""; 
				$tViewvcApacheReload		= isset( $_SESSION['svn_inst']['viewvcApacheReload'] ) 	? $_SESSION['svn_inst']['viewvcApacheReload'] 	: "";
				$tViewvcRealm				= isset( $_SESSION['svn_inst']['viewvcRealm'] ) 		? $_SESSION['svn_inst']['viewvcRealm'] 			: "";
				$tPerRepoFiles				= isset( $_SESSION['svn_inst']['perRepoFiles'] ) 		? $_SESSION['svn_inst']['perRepoFiles'] 		: "";
				
				# common locations where to find grep and svn under linux/unix
			   	$svnpath					= array('/usr/local/bin/svn', '/usr/bin/svn', '/bin/svn');
			   	$svnadminpath				= array('/usr/local/bin/svnadmin', '/usr/bin/svnadmin', '/bin/svnadmin');
			   	$greppath					= array('/usr/local/bin/grep', '/usr/bin/grep', '/bin/grep');
			   	$apachepath					= array('/etc/init.d/httpd', '/etc/init.d/apache2', '/etc/init.d/apache');
			   	
			   	for( $i = 0; $i < count($svnpath); $i++ ) {
			   		if( file_exists( $svnpath[$i] ) ) {
			   			if( $tSvnCommand == "" ) {
			   				$tSvnCommand		= $svnpath[$i];
			   			}
			   		}
			   	}
			   	
			   	for( $i = 0; $i < count($svnadminpath); $i++ ) {
			   		if( file_exists( $svnadminpath[$i] ) ) {
			   			if( $tSvnadminCommand == "" ) {
			   				$tSvnadminCommand		= $svnadminpath[$i];
			   			}
			   		}
			   	}
			   	
			   	for( $i=0; $i < count($greppath ); $i++ ) {
			   		if( file_exists( $greppath[$i] ) ) {
			   			if( $tGrepCommand == "" ) {
			   				$tGrepCommand		= $greppath[$i];
			   			}
			   		}
			   	}
			   
			   	for( $i=0; $i < count($apachepath); $i++ ) {
			   		if( file_exists($apachepath[$i] ) ) {
			   			if( $tViewvcApacheReload == "" ) {
			   				$tViewvcApacheReload= "sudo ".$apachepath[$i]." graceful";
			   			}
			   		}
			   	}
				
				if( $tUseAuthUserFile == "YES" ) {
					$tUseAuthUserFileYes		= "checked";
					$tUseAuthUSerFileNo			= "";	
				} else {
					$tUseAuthUserFileYes		= "";
					$tUseAuthUserFileNo			= "checked";
				}
				
				if( $tUseSvnAccessFile == "YES" ) {
					$tUseSvnAccessFileYes		= "checked";
					$tUseSvnAccessFileNo		= "";
				} else {
					$tUseSvnAccessFileYes		= "";
					$tUseSvnAccessFileNo		= "checked";
				}
				
				if( $tAccessControlLevel == "dirs" ) {
					$tAccessControlLevelDirs	= "checked";
					$tAccessControlLevelFiles	= "";
				} else {
					$tAccessControlLevelDirs	= "";
					$tAccessControlLevelFiles	= "checked";
				}
				
				if( $tPerRepoFiles == "YES" ) {
					$tPerRepoFilesYes			= "checked";
					$tPerRepoFilesNo			= "";
				} else {
					$tPerRepoFilesYes			= "";
					$tPerRepoFilesNo			= "checked";
				}
				
				if( $tViewvcConfig == "YES" ) {
					$tViewvcConfigYes			= "checked";
					$tViewvcConfigNo			= "";
				} else {
					$tViewvcConfigYes			= "";
					$tViewvcConfigNo			= "checked";
				}
				
				$_SESSION['svn_inst']['page']--;
				include ("../templates/install_page_5.tpl");
				exit;
			
			} else {
				
				if( $tJavaScript == "YES" ) {
					$tJavaScriptYes				= "checked";
					$tJavaScriptNo				= "";
				} else {
					$tJavaScriptYes				= "";
					$tJavaScriptNo				= "checked";
				}
				
				if( $tLogging == "YES" ) {
					$tLoggingYes				= "checked";
					$tLoggingNo					= "";
				} else {
					$tLoggingYes				= "";
					$tLoggingNo					= "checked";
				}
				
				if( $tUseMd5 == "md5" ) {
					$tMd5Yes					= "checked";
					$tMd5No						= "";
					$CONF['pwcrypt']			= "md5";
				} else {
					$tMd5Yes					= "";
					$tMd5No						= "checked";
					$CONF['pwcrypt']			= "crypt";
				}
				
				include ("../templates/install_page_6.tpl");
				exit;
			}
			
		} else {
			
		}
		
	} elseif( isset( $_POST['fSubmit_testdb'] ) ) {
		
		$tDatabaseHost						= isset( $_POST['fDatabaseHost'] )			? ( $_POST['fDatabaseHost'] )			: "";
		$tDatabaseUser						= isset( $_POST['fDatabaseUser'] )			? ( $_POST['fDatabaseUser'] )			: "";
		$tDatabasePassword					= isset( $_POST['fDatabasePassword'] )		? ( $_POST['fDatabasePassword'] )		: "";
		$tDatabaseName						= isset( $_POST['fDatabaseName'] )			? ( $_POST['fDatabaseName'] )			: "";
		$tDatabaseSchema					= isset( $_POST['fDatabaseSchema'] )		? ( $_POST['fDatabaseSchema'] )			: "";
		$tDatabaseTablespace				= isset( $_POST['fDatabaseTablespace'] )	? ( $_POST['fDatabaseTablespace'] )		: "";
		$tDatabaseCharset					= isset( $_POST['fDatabaseCharset'] )		? ( $_POST['fDatabaseCharset'] )		: "";
		$tDatabaseCollation					= isset( $_POST['fDatabaseCollation'] )		? ( $_POST['fDatabaseCollation'] )		: "";
		
		$_SESSION['svn_inst']['databaseHost']				= $tDatabaseHost;
		$_SESSION['svn_inst']['databaseUser']				= $tDatabaseUser;
		$_SESSION['svn_inst']['databasePassword']			= $tDatabasePassword;
		$_SESSION['svn_inst']['databaseName']				= $tDatabaseName;
		$_SESSION['svn_inst']['databaseSchema']				= $tDatabaseSchema;
		$_SESSION['svn_inst']['databaseTablespace']			= $tDatabaseTablespace;
		$_SESSION['svn_inst']['databaseCharset']			= $tDatabaseCharset;
		$_SESSION['svn_inst']['databaseCollation']			= $tDatabaseCollation;
		
		doDbtest();
			
	} elseif( isset( $_POST['fSubmit_testldap'] ) ) {
		
		$error							= 0;
		$tCreateDatabaseTables			= isset( $_POST['fCreateDatabaseTables'] ) 	? ( $_POST['fCreateDatabaseTables'] )	: "";
		$tDropDatabaseTables			= isset( $_POST['fDropDatabaseTables'] ) 	? ( $_POST['fDropDatabaseTables'] )		: "";
		$tDatabase						= isset( $_POST['fDatabase'] )				? ( $_POST['fDatabase'] )				: "";
		$tSessionInDatabase				= isset( $_POST['fSessionInDatabase'])		? ( $_POST['fSessionInDatabase'] )		: "";
		$tUseLdap						= isset( $_POST['fUseLdap'])				? ( $_POST['fUseLdap'] )				: "";
		$tLdapHost						= isset( $_POST['fLdapHost'])				? ( $_POST['fLdapHost'])				: "";
		$tLdapPort						= isset( $_POST['fLdapPort'])				? ( $_POST['fLdapPort'])				: "389";
		$tLdapProtocol					= isset( $_POST['fLdapProtocol'])			? ( $_POST['fLdapProtocol'])			: "3";
		$tLdapBinddn					= isset( $_POST['fLdapBinddn'])				? ( $_POST['fLdapBinddn'])				: "";
		$tLdapBindpw					= isset( $_POST['fLdapBindpw'])				? ( $_POST['fLdapBindpw'])				: "";
		$tLdapUserdn					= isset( $_POST['fLdapUserdn'])				? ( $_POST['fLdapUserdn'])				: "";
		$tLdapUserFilter				= isset( $_POST['fLdapUserFilter'])			? ( $_POST['fLdapUserFilter'])			: "";
		$tLdapUserObjectclass			= isset( $_POST['fLdapUserObjectclass'])	? ( $_POST['fLdapUserObjectclass'])		: "";
		$tLdapUserAdditionalFilter		= isset( $_POST['fLdapUserAdditionalFilter']) ? ( $_POST['fLdapUserAdditionalFilter']) : "";
		
		$_SESSION['svn_inst']['createDatabaseTables']		= $tCreateDatabaseTables;
		$_SESSION['svn_inst']['dropDatabaseTables']			= $tDropDatabaseTables;
		$_SESSION['svn_inst']['database']					= $tDatabase;
		$_SESSION['svn_inst']['sessionInDatabase']			= $tSessionInDatabase;
		$_SESSION['svn_inst']['useLdap']					= $tUseLdap;
		$_SESSION['svn_inst']['ldapHost']					= $tLdapHost;
		$_SESSION['svn_inst']['ldapPort']					= $tLdapPort;
		$_SESSION['svn_inst']['ldapProtocol']				= $tLdapProtocol;
		$_SESSION['svn_inst']['ldapBinddn']					= $tLdapBinddn;
		$_SESSION['svn_inst']['ldapBindpw']					= $tLdapBindpw;
		$_SESSION['svn_inst']['ldapUserdn']					= $tLdapUserdn;
		$_SESSION['svn_inst']['ldapUserFilter']				= $tLdapUserFilter;
		$_SESSION['svn_inst']['ldapUserObjectclass']		= $tLdapUserObjectclass;
		$_SESSION['svn_inst']['ldapUserAdditionalFilter']	= $tLdapUserAdditionalFilter;
		
		if( strtoupper( $tDatabase) == "MYSQL" ) {
			$tDatabaseCharsetDefault	= "latin1";
			$tDatabaseCollationDefault	= "latin1_german1_ci";
		} else {
			$tDatabaseCharsetDefault	= "";
			$tDatabaseCollationDefault	= "";
		}
		
		if( strtoupper($tUseLdap) == "YES" ) {
			
			if( $tLdapHost == "" ) {
				
				$tErrors[]				= _("LDAP host is missing!");
				$error					= 1;
			
			}
			
			if( $tLdapPort == "" ) {
				
				$tErrors[]				= _("LDAP port is missing!");
				$error					= 1;
				
			}
			
			if( ($tLdapProtocol != "2") and ($tLdapProtocol != "3") ) {
				
				$tErrors[]				= sprintf( _("Invalid protocol version %s!"), $tLdapProtocol );
				$error					= 1;
				
			}
			
			if( $tLdapBinddn == "" ) {
				
				$tErrors[]				= _("LDAP bind dn is missing!");
				$error					= 1;
				
			}
			
			if( $tLdapBindpw == "" ) {
				
				$tErrors[]				= _("LDAP bind password is missing!");
				$error					= 1;
				
			}
			
			if( $tLdapUserdn == "" ) {
				
				$tErrors[]				= _("LDAP user dn is missing!");
				$error					= 1;
				
			}
			
			if( $tLdapUserFilter == "" ) {
				
				$tErrors[]				= _("LDAP user filter attribute is missing!");
				$error					= 1;
				
			}
			
			if( $tLdapUserObjectclass == "" ) {
				
				$tErrors[]				= _("LDAP user object class is missing!");
				$error					= 1;
									
			}
			
		}
		
		if( $tCreateDatabaseTables == "YES" ) {
			$tCreateDatabaseTablesYes	= "checked";
			$tCreateDatabaseTablesNo	= "";
		} else {
			$tCreateDatabaseTablesYes	= "";
			$tCreateDatabaseTablesNo	= "checked";
		}
		
		if( $tDropDatabaseTables == "YES" ) {
			$tDropDatabaseTablesYes		= "checked";
			$tDropDatabaseTablesNo		= "";
		} else {
			$tDropDatabaseTablesYes		= "";
			$tDropDatabaseTablesNo		= "checked";
		}
		
		if( $tDatabase == "mysql" ) {
			$tDatabaseMySQL				= "checked";
			$tDatabasePostgreSQL		= "";
			$tDatabaseOracle			= "";
		} elseif( $tDatabase == "postgres8" ) {
			$tDatabaseMySQL				= "";
			$tDatabasePostgreSQL		= "checked";
			$tDatabaseOracle			= "";
		} elseif( $tDatabase == "oci8" ) {
			$tDatabaseMySQL				= "";
			$tDatabasePostgreSQL		= "";
			$tDatabaseOracle			= "checked";
		} else {
			$tDatabaseMySQL				= "";
			$tDatabasePostgreSQL		= "";
		}
		
		if( $tSessionInDatabase == "YES" ) {
			$tSessionInDatabaseYes		= "checked";
			$tSessionInDatabaseNo		= "";
		} else {
			$tSessionInDatabaseYes		= "";
			$tSessionInDatabaseNo		= "checked";
		}
		
		if( $tUseLdap == "YES" ) {
			$tUseLdapYes				= "checked";
			$tUseLdapNo					= "";
		} else {
			$tUseLdapYes				= "";
			$tUseLdapNo					= "checked";
		}
		
		if( $tLdapProtocol == "3" ) {
			$tLdap3						= "checked";
			$tLdap2						= "";
		} else {
			$tLdap3						= "";
			$tLdap2						= "checked";
		}
		
		if( $error == 0 ) {
				
			$_SESSION['svn_inst']['useLdap']					= $tUseLdap;
			$_SESSION['svn_inst']['ldapHost']					= $tLdapHost;
			$_SESSION['svn_inst']['ldapPort']					= $tLdapPort;
			$_SESSION['svn_inst']['ldapProtocol']				= $tLdapProtocol;
			$_SESSION['svn_inst']['ldapBinddn']					= $tLdapBinddn;
			$_SESSION['svn_inst']['ldapBindpw']					= $tLdapBindpw;
			$_SESSION['svn_inst']['ldapUserdn']					= $tLdapUserdn;
			$_SESSION['svn_inst']['ldapUserFilter']				= $tLdapUserFilter;
			$_SESSION['svn_inst']['ldapUserObjectclass']		= $tLdapUserObjectclass;
			$_SESSION['svn_inst']['ldapUserAdditionalFilter']	= $tLdapUserAdditionalFilter;
			
			$tDatabaseHost				= isset( $_SESSION['svn_inst']['databaseHost'] ) 		? $_SESSION['svn_inst']['databaseHost'] 		: "";
			$tDatabaseUser				= isset( $_SESSION['svn_inst']['databaseUser'] ) 		? $_SESSION['svn_inst']['databaseUser'] 		: "";
			$tDatabasePassword			= isset( $_SESSION['svn_inst']['databasePassword'] ) 	? $_SESSION['svn_inst']['databasePassword'] 	: ""; 
			$tDatabaseName				= isset( $_SESSION['svn_inst']['databaseName'] ) 		? $_SESSION['svn_inst']['databaseName'] 		: "";
			$tDatabaseSchema			= isset( $_SESSION['svn_inst']['databaseSchema'] ) 		? $_SESSION['svn_inst']['databaseSchema'] 		: "";
			$tDatabaseTablespace		= isset( $_SESSION['svn_inst']['databaseTablespace'] ) 	? $_SESSION['svn_inst']['databaseTablespace'] 	: "";
			$tDatabaseCharset			= isset( $_SESSION['svn_inst']['databaseCharset'] ) 	? $_SESSION['svn_inst']['databaseCharset'] 		: $tDatabaseCharsetDefault;
			$tDatabaseCollation			= isset( $_SESSION['svn_inst']['databaseCollation'] ) 	? $_SESSION['svn_inst']['databaseCollation'] 	: $tDatabaseCollationDefault;
			
			if( $_SESSION['svn_inst']['useLdap'] == "YES" ) {
			
				doLdapTest();
				
			} else {
				
				$tErrors[]				= _("Testing LDAP connection doesn't make sense if you do not use LDAP!");
				include ("../templates/install_page_1.tpl");
				exit;
			}
			
		} else {
			
			include ("../templates/install_page_1.tpl");
			exit;
		}
	
	} elseif( isset( $_POST['fSubmit_install'] ) ) {
		
		$CONF['database_type']				= "mysql";
		$error								= 0;
		$tLogging							= isset( $_POST['fLogging'] )				? ( $_POST['fLogging'] )				: "";
		$tJavaScript						= isset( $_POST['fJavaScript'] )			? ( $_POST['fJavaScript'] )				: "";
		$tPageSize							= isset( $_POST['fPageSize'] )				? ( $_POST['fPageSize'] )				: 30;
		$tMinAdminPwSize					= isset( $_POST['fMinAdminPwSize'] )		? ( $_POST['fMinAdminPwSize'] )			: 14;
		$tMinUserPwSize						= isset( $_POST['fMinUserPwSize'] 	)		? ( $_POST['fMinUserPwSize'] )			: 8;
		$tUseMd5							= isset( $_POST['fUseMd5'] )				? ( $_POST['fUseMd5'] ) 				: "";
		
		if( $tPageSize == "" ) {
			
			$tErrors[]						= _("Page size is missing!" );
			$error							= 1;
			
		} 
		
		if( ! is_numeric($tPageSize) ) {
			
			$tErrors[]						= _("Page size is not numeric!" );
			$error							= 1;
			
		} 
		
		if( ! is_numeric( $tMinAdminPwSize) ) {
			
			$tErrors[]						= _("Minimal administrator password length is not numeric!" );
			$error							= 1;
			
		} 
		
		if( ! is_numeric( $tMinUserPwSize ) ) {
			
			$tErrors[]						= _("Minimal user password length is not numeric!" );
			$error							= 1;
			
		}
	
		if( $error == 0 ) {
			
			$_SESSION['svn_inst']['logging']					= $tLogging;
			$_SESSION['svn_inst']['javaScript']					= $tJavaScript;
			$_SESSION['svn_inst']['pageSize']					= $tPageSize;
			$_SESSION['svn_inst']['minAdminPwSize']				= $tMinAdminPwSize;
			$_SESSION['svn_inst']['minUserPwSize']				= $tMinUserPwSize;
			$_SESSION['svn_inst']['useMd5']						= $tUseMd5;
			
			doInstall();
			
		} else {
			
			include ("../templates/install_page_6.tpl");
			exit;
				
		}
	
		
	}

}

?>
