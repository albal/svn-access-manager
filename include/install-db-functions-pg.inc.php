<?php

/**
 * Database functions arround PostgrSQL for installer.
 *
 * @author Thomas Krieger
 * @copyright 2018 Thomas Krieger. All righhts reserved.
 *           
 *            SVN Access Manager - a subversion access rights management tool
 *            Copyright (C) 2008-2018 Thomas Krieger <tom@svn-access-manager.org>
 *           
 *            This program is free software; you can redistribute it and/or modify
 *            it under the terms of the GNU General Public License as published by
 *            the Free Software Foundation; either version 2 of the License, or
 *            (at your option) any later version.
 *           
 *            This program is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU General Public License for more details.
 *           
 *            You should have received a copy of the GNU General Public License
 *            along with this program; if not, write to the Free Software
 *            Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *           
 * @filesource
 */

/**
 * drop PostgreSQL database tables during installation
 *
 * @param resource $dbh
 * @return number[]|string[]
 */
function dropPostgresDatabaseTables($dbh) {

    global $DBTABLES;
    
    $error = 0;
    $tMessage = "";
    
    foreach( $DBTABLES as $dbtable ) {
        
        if ($error == 0) {
            
            $query = "DROP TABLE IF EXISTS $dbtable CASCADE";
            db_query_install($query, $dbh);
            $seq = $dbtable . "_id_seq";
            $query = "DROP SEQUENCE IF EXISTS $seq CASCADE";
            db_query_install($query, $dbh);
        }
    }
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

/**
 * create help text table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createHelpTablePostgresql($dbh, $schema, $dbuser) {

    // Table help
    $query = "CREATE TABLE help (id bigint NOT NULL, topic character varying(255) NOT NULL, headline_en character varying(255) NOT NULL,  headline_de character varying(255) NOT NULL, helptext_de text NOT NULL, helptext_en text NOT NULL);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.help OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE help IS 'Table of help texts';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE help_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.help_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE help_id_seq OWNED BY help.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE help ALTER COLUMN id SET DEFAULT nextval('help_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY help ADD CONSTRAINT help_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX help_topic_idx ON help USING btree (topic);";
    db_query_install($query, $dbh);
    
}

/**
 * create PostgreSQL tables
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createLogTableProstgresql($dbh, $schema, $dbuser) {

    // Table log
    $query = "CREATE TABLE log (id bigint NOT NULL, \"logtimestamp\" character varying(14) NOT NULL, username character varying(255) NOT NULL, ipaddress character varying(15) NOT NULL, logmessage text NOT NULL);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.log OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE log IS 'Table of log messages';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE log_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.log_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE log_id_seq OWNED BY log.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY log ADD CONSTRAINT log_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX log_timestamp_idx ON log USING btree (\"logtimestamp\");";
    db_query_install($query, $dbh);
    
}

/**
 * create preferences table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createPreferencesTableProstgresql($dbh, $schema, $dbuser) {

    // Table preferences
    $query = "CREATE TABLE preferences (id bigint NOT NULL, user_id integer NOT NULL, page_size integer NOT NULL, user_sort_fields character varying(255) NOT NULL, user_sort_order character varying(255) NOT NULL, created character varying(14) NOT NULL  DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ');";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.preferences OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE preferences IS 'Table of user preferences';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE preferences_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE  NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.preferences_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE preferences_id_seq OWNED BY preferences.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE preferences ALTER COLUMN id SET DEFAULT nextval('preferences_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY preferences ADD CONSTRAINT preferences_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX preferences_user_id_idx ON preferences USING btree (user_id);";
    db_query_install($query, $dbh);
    
}

/**
 * create rights table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createRightsTableProstgresql($dbh, $schema, $dbuser) {

    // Table rights
    $query = "CREATE TABLE rights (id bigint NOT NULL, right_name character varying(255) NOT NULL, description_en character varying(255) NOT NULL, description_de character varying(255) NOT NULL,allowed_action character varying DEFAULT 'none'::character varying NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT rights_allowed_action_check CHECK (((allowed_action)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'edit'::character varying, 'delete'::character varying])::text[]))));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.rights OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE rights IS 'Table of rights to grant to users';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE rights_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.rights_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE rights_id_seq OWNED BY rights.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE rights ALTER COLUMN id SET DEFAULT nextval('rights_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY rights ADD CONSTRAINT rights_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    
}

/**
 * create workinfo table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createWorkinfoTableProstgresql($dbh, $schema, $dbuser) {

    // Table workinfo
    $query = "CREATE TABLE workinfo (id bigint NOT NULL, \"usertimestamp\" timestamp without time zone DEFAULT now() NOT NULL, action character varying(255) NOT NULL, status character varying(255) NOT NULL, type character varying(255) NOT NULL);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.workinfo OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE workinfo IS 'table of workinfos';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE workinfo_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.workinfo_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE workinfo_id_seq OWNED BY workinfo.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE workinfo ALTER COLUMN id SET DEFAULT nextval('workinfo_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY workinfo ADD CONSTRAINT workinfo_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    
}

/**
 * create sessions table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSessionsTableProstgresql($dbh, $schema, $dbuser) {

    // Table sessions
    $query = "CREATE TABLE sessions (session_id character varying(255) NOT NULL, session_expires integer DEFAULT 0 NOT NULL, session_data text, CONSTRAINT sessions_session_expires_check CHECK ((session_expires >= 0)));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.sessions OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY sessions ADD CONSTRAINT sessions_pkey PRIMARY KEY (session_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX sessions_session_expires_idx ON sessions USING btree (session_expires);";
    db_query_install($query, $dbh);
    
}

/**
 * create svngoups table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvngroupsTableProstgresql($dbh, $schema, $dbuser) {

    // Table svngroups
    $query = "CREATE TABLE svngroups (id bigint NOT NULL, groupname character varying(255) NOT NULL, description character varying(255) NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ');";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svngroups OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svngroups IS 'Table of svn user groups';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svngroups_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svngroups_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svngroups_id_seq OWNED BY svngroups.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svngroups ALTER COLUMN id SET DEFAULT nextval('svngroups_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svngroups ADD CONSTRAINT svngroups_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svngroups_groupname_idx ON svngroups USING btree (groupname);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnprojects table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnprojectsTableProstgresql($dbh, $schema, $dbuser) {

    // Table svnprojects
    $query = "CREATE TABLE svnprojects (id bigint NOT NULL, repo_id integer NOT NULL, svnmodule character varying(255) NOT NULL, modulepath character varying(255) NOT NULL, description character varying(255) DEFAULT ' ', created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT svnprojects_repo_id_check CHECK ((repo_id >= 0)));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnprojects OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svnprojects IS 'Table of svn modules';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svnprojects_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnprojects_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svnprojects_id_seq OWNED BY svnprojects.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svnprojects ALTER COLUMN id SET DEFAULT nextval('svnprojects_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svnprojects ADD CONSTRAINT svnprojects_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svnprojects_deleted_idx ON svnprojects USING btree (deleted);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svnprojects_repo_id_idx ON svnprojects USING btree (repo_id);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnusers table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnusersTableProstgresql($dbh, $schema, $dbuser) {

    // Table svnusers
    $query = "CREATE TABLE svnusers (id bigint NOT NULL, userid character varying(255) NOT NULL, name character varying(255) NOT NULL, givenname character varying(255) NOT NULL, password character varying(255) DEFAULT ''::character varying NOT NULL, passwordexpires smallint DEFAULT 1::smallint NOT NULL, locked smallint DEFAULT 0::smallint NOT NULL, emailaddress character varying(255) DEFAULT ''::character varying NOT NULL, admin character varying(1) DEFAULT 'n'::character varying NOT NULL, user_mode character varying(10) NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', password_modified character varying(14) NOT NULL DEFAULT '00000000000000', superadmin smallint DEFAULT 0::smallint NOT NULL, securityquestion character varying(255) DEFAULT ''::character varying, securityanswer character varying(255) DEFAULT ''::character varying, custom1 character varying(255) DEFAULT''::character varying, custom2 character varying(255) DEFAULT''::character varying, custom3 character varying(255) DEFAULT''::character varying);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnusers OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svnusers IS 'Table of all known users';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svnusers_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnusers_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svnusers_id_seq OWNED BY svnusers.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svnusers ALTER COLUMN id SET DEFAULT nextval('svnusers_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svnusers ADD CONSTRAINT svnusers_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svnusers ADD CONSTRAINT svnusers_userid_key UNIQUE (userid, deleted);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svnusers_deleted_idx ON svnusers USING btree (deleted);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svnusers_locked_idx ON svnusers USING btree (locked);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svnusers_passwordexpires_idx ON svnusers USING btree (passwordexpires);";
    db_query_install($query, $dbh);
    
}

/**
 * cfreate svnaccessrights table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnAccessRightsTableProstgresql($dbh, $schema, $dbuser) {

    // Table svn_access_rights
    $query = "CREATE TABLE svn_access_rights (id bigint NOT NULL, project_id integer, user_id integer, group_id integer, path text NOT NULL, valid_from character varying(14) NOT NULL DEFAULT '00000000000000', valid_until character varying(14) NOT NULL DEFAULT '99999999999999', access_right character varying DEFAULT 'none'::character varying NOT NULL, recursive character varying DEFAULT 'yes'::character varying NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT svn_access_rights_access_right_check CHECK (((access_right)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'write'::character varying])::text[]))), CONSTRAINT svn_access_rights_recursive_check CHECK (((recursive)::text = ANY ((ARRAY['yes'::character varying, 'no'::character varying])::text[]))));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_access_rights OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svn_access_rights IS 'Table of user or group access rights';";
    db_query_install($query, $dbh);
    $query = "COMMENT ON COLUMN svn_access_rights.valid_from IS 'JHJJMMTT';";
    db_query_install($query, $dbh);
    $query = "COMMENT ON COLUMN svn_access_rights.valid_until IS 'JHJJMMTT';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svn_access_rights_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_access_rights_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svn_access_rights_id_seq OWNED BY svn_access_rights.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_access_rights ALTER COLUMN id SET DEFAULT nextval('svn_access_rights_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_access_rights ALTER COLUMN user_id SET DEFAULT 0;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_access_rights ALTER COLUMN group_id SET DEFAULT 0;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_access_rights_deleted_idx ON svn_access_rights USING btree (deleted);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_access_rights_group_id_idx ON svn_access_rights USING btree (group_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_access_rights_path_idx ON svn_access_rights USING btree (path);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_access_rights_project_id_idx ON svn_access_rights USING btree (project_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_access_rights_user_id_idx ON svn_access_rights USING btree (user_id);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_group_id_fkey FOREIGN KEY (group_id) REFERENCES svngroups(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_project_id_fkey FOREIGN KEY (project_id) REFERENCES svnprojects(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_access_rights ADD CONSTRAINT svn_access_rights_user_id_fkey FOREIGN KEY (user_id) REFERENCES svnusers(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
    db_query_install($query, $dbh);
    
}

/**
 * create svngroupsresponsible table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnGroupsResponsibleTableProstgresql($dbh, $schema, $dbuser) {

    // Table svn_groups_responsible
    $query = "CREATE TABLE svn_groups_responsible (id bigint NOT NULL, user_id integer NOT NULL, group_id integer NOT NULL, allowed character varying DEFAULT 'none'::character varying NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT svn_groups_responsible_allowed_check CHECK (((allowed)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'edit'::character varying, 'delete'::character varying])::text[]))), CONSTRAINT svn_groups_responsible_group_id_check CHECK ((group_id >= 0)), CONSTRAINT svn_groups_responsible_user_id_check CHECK ((user_id >= 0)));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_groups_responsible OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svn_groups_responsible_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_groups_responsible_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svn_groups_responsible_id_seq OWNED BY svn_groups_responsible.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_groups_responsible ALTER COLUMN id SET DEFAULT nextval('svn_groups_responsible_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_groups_responsible ADD CONSTRAINT svn_groups_responsible_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_groups_responsible_1_idx ON svn_groups_responsible USING btree (user_id, group_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_groups_responsible_deleted_idx ON svn_groups_responsible USING btree (deleted);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnprojectsmailinglists table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnProjectsMailinglisteTableProstgresql($dbh, $schema, $dbuser) {

    // Table svn_projects_mailinglists
    $query = "CREATE TABLE svn_projects_mailinglists (id bigint NOT NULL, project_id integer NOT NULL, mailinglisten_id integer NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT svn_projects_mailinglists_mailinglisten_id_check CHECK ((mailinglisten_id >= 0)), CONSTRAINT svn_projects_mailinglists_project_id_check CHECK ((project_id >= 0)));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_projects_mailinglists OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svn_projects_mailinglists IS 'Table of modules and mailinglist relations';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svn_projects_mailinglists_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_projects_mailinglists_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svn_projects_mailinglists_id_seq OWNED BY svn_projects_mailinglists.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_projects_mailinglists ALTER COLUMN id SET DEFAULT nextval('svn_projects_mailinglists_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_projects_mailinglists ADD CONSTRAINT svn_projects_mailinglists_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_projects_mailinglists_1_idx ON svn_projects_mailinglists USING btree (project_id, mailinglisten_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_projects_mailinglists_mailinglisten_id_idx ON svn_projects_mailinglists USING btree (mailinglisten_id);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnprojectsresponsible table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnProjectsResponsibleTableProstgresql($dbh, $schema, $dbuser) {

    // Table svn_projects_responsible
    $query = "CREATE TABLE svn_projects_responsible (id bigint NOT NULL, project_id integer NOT NULL, user_id integer NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ');";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_projects_responsible OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svn_projects_responsible IS 'Table of project responsible users';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svn_projects_responsible_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_projects_responsible_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svn_projects_responsible_id_seq OWNED BY svn_projects_responsible.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_projects_responsible ALTER COLUMN id SET DEFAULT nextval('svn_projects_responsible_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_projects_responsible ADD CONSTRAINT svn_projects_responsible_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_projects_responsible_deleted_idx ON svn_projects_responsible USING btree (deleted);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_projects_responsible_project_id_idx ON svn_projects_responsible USING btree (project_id);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnusersgroups table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnUsersGroupsTableProstgresql($dbh, $schema, $dbuser) {

    // Table svn_users_groups
    $query = "CREATE TABLE svn_users_groups (id bigint NOT NULL, user_id integer NOT NULL, group_id integer NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT svn_users_groups_group_id_check CHECK ((group_id >= 0)), CONSTRAINT svn_users_groups_user_id_check CHECK ((user_id >= 0)));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_users_groups OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svn_users_groups IS 'Table of user group relations';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svn_users_groups_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svn_users_groups_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svn_users_groups_id_seq OWNED BY svn_users_groups.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svn_users_groups ALTER COLUMN id SET DEFAULT nextval('svn_users_groups_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svn_users_groups ADD CONSTRAINT svn_users_groups_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_users_groups_deleted_idx ON svn_users_groups USING btree (deleted);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_users_groups_group_id_idx ON svn_users_groups USING btree (group_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svn_users_groups_user_id_idx ON svn_users_groups USING btree (user_id);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnmailinglists table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnmailinglistsTableProstgresql($dbh, $schema, $dbuser) {

    // Table svnmailinglists
    $query = "CREATE TABLE svnmailinglists (id bigint NOT NULL, mailinglist character varying(255) NOT NULL, emailaddress character varying(255) NOT NULL, description text NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ');";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnmailinglists OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svnmailinglists IS 'Table of available svn mailing lists';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svnmailinglists_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnmailinglists_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svnmailinglists_id_seq OWNED BY svnmailinglists.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svnmailinglists ALTER COLUMN id SET DEFAULT nextval('svnmailinglists_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svnmailinglists ADD CONSTRAINT svnmailinglists_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnpasswordreset table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnpasswordresetTableProstgresql($dbh, $schema, $dbuser) {

    // Table svnpasswordreset
    $query = "CREATE TABLE svnpasswordreset (id bigint NOT NULL, unixtime integer NOT NULL, username character varying(255) NOT NULL, token character varying(255) NOT NULL, idstr character varying(255) NOT NULL);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnpasswordreset OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svnpasswordreset_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnpasswordreset_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svnpasswordreset_id_seq OWNED BY svnpasswordreset.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svnpasswordreset ALTER COLUMN id SET DEFAULT nextval('svnpasswordreset_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svnpasswordreset ADD CONSTRAINT svnpasswordreset_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    
}

/**
 * create svnrepos table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createSvnreposTableProstgresql($dbh, $schema, $dbuser) {

    // Table svnrepos
    $query = "CREATE TABLE svnrepos (id bigint NOT NULL, reponame character varying(255) NOT NULL, repopath character varying(255) NOT NULL, repouser character varying(255) NOT NULL, repopassword character varying(255) NOT NULL, different_auth_files smallint DEFAULT 0::smallint NOT NULL, auth_user_file character varying(255) NOT NULL, svn_access_file character varying(255) NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ');";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnrepos OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE svnrepos IS 'Table of svn repositories';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE svnrepos_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.svnrepos_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE svnrepos_id_seq OWNED BY svnrepos.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE svnrepos ALTER COLUMN id SET DEFAULT nextval('svnrepos_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY svnrepos ADD CONSTRAINT svnrepos_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX svnrepos_deleted_idx ON svnrepos USING btree (deleted);";
    db_query_install($query, $dbh);
    
}

/**
 * create userrights table
 *
 * @param resource $dbh
 * @param string $schema
 * @param string $dbuser
 */
function createUserrightsTableProstgresql($dbh, $schema, $dbuser) {

    // Table users_rights
    $query = "CREATE TABLE users_rights (id bigint NOT NULL, user_id integer NOT NULL, right_id integer NOT NULL, allowed character varying DEFAULT 'none'::character varying NOT NULL, created character varying(14) NOT NULL DEFAULT '00000000000000', created_user character varying(255) DEFAULT ' ', modified character varying(14) NOT NULL DEFAULT '00000000000000', modified_user character varying(255) DEFAULT ' ', deleted character varying(14) NOT NULL DEFAULT '00000000000000', deleted_user character varying(255) DEFAULT ' ', CONSTRAINT users_rights_allowed_check CHECK (((allowed)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'add'::character varying, 'edit'::character varying, 'delete'::character varying])::text[]))));";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.users_rights OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "COMMENT ON TABLE users_rights IS 'Table of granted user rights';";
    db_query_install($query, $dbh);
    $query = "CREATE SEQUENCE users_rights_id_seq START WITH 1 INCREMENT BY 1 NO MAXVALUE NO MINVALUE CACHE 1;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE $schema.users_rights_id_seq OWNER TO $dbuser;";
    db_query_install($query, $dbh);
    $query = "ALTER SEQUENCE users_rights_id_seq OWNED BY users_rights.id;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE users_rights ALTER COLUMN id SET DEFAULT nextval('users_rights_id_seq'::regclass);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY users_rights ADD CONSTRAINT users_rights_pkey PRIMARY KEY (id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX users_rights_right_id_idx ON users_rights USING btree (right_id);";
    db_query_install($query, $dbh);
    $query = "CREATE INDEX users_rights_user_id_idx ON users_rights USING btree (user_id);";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY users_rights ADD CONSTRAINT users_rights_right_id_fkey FOREIGN KEY (right_id) REFERENCES rights(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
    db_query_install($query, $dbh);
    $query = "ALTER TABLE ONLY users_rights ADD CONSTRAINT users_rights_user_id_fkey FOREIGN KEY (user_id) REFERENCES svnusers(id) ON UPDATE RESTRICT ON DELETE CASCADE;";
    db_query_install($query, $dbh);
    
}

/**
 * load data into PostgreSQL database
 *
 * @param resource $dbh
 * @param string $schema
 * @return integer[]|string[]
 */
function loadPostgresDbData($dbh, $schema) {

    if ($schema != "") {
        $query = "SET search_path = '$schema'";
    }
    else {
        $query = "SET search_path = ";
    }
    db_query_install($query, $dbh);
    
    db_ta(BEGIN, $dbh);
    
    $error = 0;
    $tMessage = "";
    $dbnow = db_now();
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('User admin', 'Administer users', 'Benutzer verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Group admin', 'Administer groups', 'Gruppen verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Project admin', 'Administer projects', 'Projekte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Repository admin', 'Administer repositories', 'Repositories verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Access rights admin', 'Administer repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', '$dbnow', 'install', '00000000000000', '', '00000000000000', '')";
    db_query_install($query, $dbh);
    
    if ($error == 0) {
        db_ta(COMMIT, $dbh);
    }
    else {
        db_ta(ROLLBACK, $dbh);
    }
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

?>