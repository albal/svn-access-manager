<?php

//
//
//
function dropOracleDatabaseTables($dbh, $schema) {

    global $DBTABLES;
    
    $error = 0;
    $tMessage = "";
    
    foreach( $DBTABLES as $dbtable) {
        
        if ($error == 0) {
            
            $query = "begin execute immediate 'drop table $schema.$dbtable cascade constraints'; exception when others then null; end;";
            db_query_install($query, $dbh);
            $seq = $dbtable . "_seq";
            $query = "begin execute immediate 'drop sequence $schema.$seq'; exception when others then null; end;";
            db_query_install($query, $dbh);
        }
    }
    
    $ret = array();
    $ret[ERROR] = $error;
    $ret[ERRORMSG] = $tMessage;
    
    return $ret;
    
}

//
//
//
function createHelpTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"HELP_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    $query = "CREATE TABLE $schema.HELP (\"ID\"  NUMBER(*,0) NOT NULL ENABLE, \"TOPIC\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"HEADLINE_EN\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"HEADLINE_DE\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"HELPTEXT_DE\" CLOB NOT NULL ENABLE,  \"HELPTEXT_EN\" CLOB NOT NULL ENABLE, CONSTRAINT \"HELP_PK\" PRIMARY KEY (\"ID\") ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.HELP IS 'Table of help texts'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.HELP_TOPIC_IDX ON $schema.HELP (\"TOPIC\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.HELP_TRG BEFORE
												  INSERT ON $schema.HELP FOR EACH ROW BEGIN IF :NEW.ID IS NULL THEN
												  SELECT HELP_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.HELP_TRG ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createLogTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"LOG_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.LOG (\"ID\" NUMBER(*,0) NOT NULL ENABLE, \"LOGTIMESTAMP\" VARCHAR2(14 BYTE) NOT NULL ENABLE, \"USERNAME\"  VARCHAR2(255 BYTE) NOT NULL ENABLE, \"IPADDRESS\" VARCHAR2(15 BYTE) NOT NULL ENABLE,  \"LOGMESSAGE\" CLOB NOT NULL ENABLE, CONSTRAINT LOG_PK PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.LOG IS 'Table of log messages'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.LOG_TIMESTAMP_IDX ON $schema.LOG (\"LOGTIMESTAMP\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.LOG_TRG BEFORE
												  INSERT ON $schema.LOG FOR EACH ROW BEGIN
												  SELECT LOG_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.LOG_TRG ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createPreferencesTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"PREFERENCES_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"PREFERENCES\" (\"ID\"               NUMBER(*,0) NOT NULL ENABLE, \"USER_ID\"          NUMBER(*,0) NOT NULL ENABLE, \"PAGE_SIZE\"        NUMBER(*,0) NOT NULL ENABLE, \"USER_SORT_FIELDS\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"USER_SORT_ORDER\"  VARCHAR2(255 BYTE) NOT NULL ENABLE, \"CREATED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"     VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"         VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\"    VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"     VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"PREFERENCES_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"PREFERENCES\" IS 'Table of user preferences'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"PREFERENCES_USER_ID_IDX\" ON $schema.\"PREFERENCES\" (\"USER_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"PREFERENCES_TRG\" BEFORE
												  INSERT ON $schema.PREFERENCES FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN
												  SELECT PREFERENCES_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"PREFERENCES_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createWorkinfoTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"WORKINFO_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createRightsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"RIGHTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"RIGHTS\" (\"ID\"             NUMBER(*,0) NOT NULL ENABLE, \"RIGHT_NAME\"     VARCHAR2(255 BYTE) NOT NULL ENABLE, \"DESCRIPTION_EN\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"DESCRIPTION_DE\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"ALLOWED_ACTION\" VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE, \"CREATED\"        VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"   VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\"  VARCHAR2(155 BYTE) DEFAULT '', \"DELETED\"        VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,  \"DELETED_USER\"   VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"RIGHTS_PK\" PRIMARY KEY (\"ID\")  ENABLE, CONSTRAINT \"RIGHTS_ALLOWED_ACTION_CHECK\" CHECK (ALLOWED_ACTION = 'none' OR ALLOWED_ACTION = 'read' OR ALLOWED_ACTION = 'edit' OR ALLOWED_ACTION = 'delete') ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"RIGHTS\" IS 'Table of rights to grant to users'";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"RIGHTS_TRG\" BEFORE
												  INSERT ON $schema.RIGHTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT RIGHTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"RIGHTS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSessionTableOracle($dbh, $schema) {

    $query = "CREATE TABLE $schema.\"SESSIONS\" (\"SESSION_ID\"      VARCHAR2(255 BYTE) NOT NULL ENABLE, \"SESSION_EXPIRES\" NUMBER(*,0) DEFAULT 0 NOT NULL ENABLE, \"SESSION_DATA\" CLOB, CONSTRAINT \"SESSIONS_SESSION_EXPIRES_CHECK\" CHECK (SESSION_EXPIRES >= 0) ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SESSIONS\" IS 'Table of session information'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SESSIONS_SESSION_EXPIRES_IDX\" ON $schema.\"SESSIONS\" (\"SESSION_EXPIRES\")";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvngroupsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVNGROUPS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVNGROUPS\" (\"ID\"            NUMBER(*,0) NOT NULL ENABLE,  \"GROUPNAME\"     VARCHAR2(255 BYTE) NOT NULL ENABLE, \"DESCRIPTION\"   VARCHAR2(255 BYTE) NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '',  CONSTRAINT \"SVNGROUPS_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVNGROUPS\" IS 'Table of svn user groups'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNGROUPS_GROUPNAME_IDX\" ON $schema.\"SVNGROUPS\" (\"GROUPNAME\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVNGROUPS_TRG\" BEFORE
												  INSERT ON $schema.SVNGROUPS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNGROUPS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVNGROUPS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnmailinglistsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVNMAILINGLISTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVNMAILINGLISTS\" (\"ID\"            NUMBER(*,0) NOT NULL ENABLE, \"MAILINGLIST\"   VARCHAR2(255 BYTE) NOT NULL ENABLE, \"EMAILADDRESS\"  VARCHAR2(255 BYTE) NOT NULL ENABLE, \"DESCRIPTION\"   VARCHAR2(255 BYTE) NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"SVNMAILINGLISTS_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVNMAILINGLISTS\" IS 'Table of available svn mailing lists'";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVNMAILINGLISTS_TRG\" BEFORE
												  INSERT ON $schema.SVNMAILINGLISTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNMAILINGLISTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVNMAILINGLISTS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createPasswordresetTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVNPASSWORDRESET_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVNPASSWORDRESET\" (\"ID\"       NUMBER(*,0) NOT NULL ENABLE, \"UNIXTIME\" NUMBER(*,0) NOT NULL ENABLE, \"USERNAME\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"TOKEN\"    VARCHAR2(255 BYTE) NOT NULL ENABLE, \"IDSTR\"    VARCHAR2(255 BYTE) NOT NULL ENABLE, CONSTRAINT \"SVNPASSWORDRESET_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVNPASSWORDRESET\" IS 'Table with password reset information'";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVNPASSWORDRESET_TRG\" BEFORE
												  INSERT ON $schema.SVNPASSWORDRESET FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNPASSWORDRESET_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVNPASSWORDRESET_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnreposTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVNREPOS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVNREPOS\" (\"ID\"                   NUMBER(*,0) NOT NULL ENABLE, \"REPONAME\"             VARCHAR2(255 BYTE) NOT NULL ENABLE, \"REPOPATH\"             VARCHAR2(255 BYTE) NOT NULL ENABLE, \"REPOUSER\"             VARCHAR2(255 BYTE) DEFAULT '', \"REPOPASSWORD\"         VARCHAR2(255 BYTE) DEFAULT '', \"DIFFERENT_AUTH_FILES\" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE, \"AUTH_USER_FILE\"       VARCHAR2(255 BYTE) DEFAULT '', \"SVN_ACCESS_FILE\"      VARCHAR2(255 BYTE) DEFAULT '', \"CREATED\"              VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"         VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"             VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\"        VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"              VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"         VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"SVNREPOS_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVNREPOS\" IS 'Table of svn repositories'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNREPOS_DELETED_IDX\" ON $schema.\"SVNREPOS\"  (\"DELETED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVNREPOS_TRG\" BEFORE
												  INSERT ON $schema.SVNREPOS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNREPOS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVNREPOS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnprojectsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVNPROJECTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVNPROJECTS\" ( \"ID\"            NUMBER(*,0) NOT NULL ENABLE, \"REPO_ID\"       NUMBER(*,0) NOT NULL ENABLE, \"SVNMODULE\"     VARCHAR2(255 BYTE) NOT NULL ENABLE, \"MODULEPATH\"    VARCHAR2(255 BYTE) NOT NULL ENABLE, \"DESCRIPTION\"   VARCHAR2(255 BYTE) DEFAULT '', \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE,  \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', ONSTRAINT \"SVNPROJECTS_PK\" PRIMARY KEY (\"ID\")  ENABLE, CONSTRAINT \"SVNPROJECTS_REPO_ID_CHECK\" CHECK (REPO_ID >= 0) ENABLE, ONSTRAINT \"SVNPROJECTS_REPO_ID_FK\" FOREIGN KEY (\"REPO_ID\") REFERENCES $schema.\"SVNREPOS\" (\"ID\") ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVNPROJECTS\" IS 'Table of svn modules'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNPROJECTS_DELETED_IDX\" ON $schema.\"SVNPROJECTS\" (\"DELETED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNPROJECTS_REPO_ID_IDX\" ON $schema.\"SVNPROJECTS\" (\"REPO_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVNPROJECTS_TRG\" BEFORE
												  INSERT ON $schema.SVNPROJECTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNPROJECTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVNPROJECTS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnusersTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVNUSERS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.SVNUSERS (\"ID\" NUMBER(*,0) NOT NULL ENABLE, \"USERID\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"NAME\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"GIVENNAME\" VARCHAR2(255 BYTE) NOT NULL ENABLE, \"PASSWORD\" VARCHAR2(255 BYTE) DEFAULT '', \"PASSWORDEXPIRES\" NUMBER(1,0) DEFAULT 1 NOT NULL ENABLE, \"LOCKED\" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE, \"EMAILADDRESS\" VARCHAR2(255 BYTE) DEFAULT '', \"ADMIN\" VARCHAR2(1 BYTE) DEFAULT 'n' NOT NULL ENABLE, \"USER_MODE\" VARCHAR2(10 BYTE) NOT NULL ENABLE, \"CREATED\" VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\" VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\" VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"PASSWORD_MODIFIED\" VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"SUPERADMIN\" NUMBER(1,0) DEFAULT 0 NOT NULL ENABLE, \"SECURITYQUESTION\" VARCHAR2(255 BYTE) DEFAULT '', \"SECURITYANSWER\" VARCHAR2(255 BYTE) DEFAULT '', \"CUSTOM1\" VARCHAR2(255 BYTE) DEFAULT '', \"CUSTOM2\" VARCHAR2(255 BYTE) DEFAULT '', \"CUSTOM3\" VARCHAR2(255 BYTE) DEFAULT '',  CONSTRAINT SVNUSERS_PK PRIMARY KEY (\"ID\")  ENABLE";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.SVNUSERS IS 'Table of all known users'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNUSERS_DELETED_IDX\" ON $schema.\"SVNUSERS\" (\"DELETED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNUSERS_LOCKED_IDX\" ON $schema.\"SVNUSERS\" (\"LOCKED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNUSERS_PASSWORDEXPIRES_IDX\" ON $schema.\"SVNUSERS\" (\"PASSWORDEXPIRES\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVNUSERS_TRG\" BEFORE
												  INSERT ON $schema.SVNUSERS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVNUSERS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVNUSERS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnAccessrightsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVN_ACCESS_RIGHTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVN_ACCESS_RIGHTS\" (\"ID\"         NUMBER(*,0) NOT NULL ENABLE, \"PROJECT_ID\" NUMBER(*,0) DEFAULT '0', \"USER_ID\"    NUMBER(*,0) DEFAULT '0', \"GROUP_ID\"   NUMBER(*,0), \"PATH\" VARCHAR2(4000) NOT NULL ENABLE, \"VALID_FROM\"    VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"VALID_UNTIL\"   VARCHAR2(14 BYTE) DEFAULT '99999999999999' NOT NULL ENABLE, \"ACCESS_RIGHT\"  VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE, \"RECURSIVE\"     VARCHAR2(255 BYTE) DEFAULT 'yes' NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"SVN_ACCESS_RIGHTS_PK\" PRIMARY KEY (\"ID\")  ENABLE, CONSTRAINT \"SVN_ACCESS_RECURSIVE_CHECK\" CHECK (RECURSIVE = 'yes' OR RECURSIVE = 'no') ENABLE, CONSTRAINT \"SVN_ACCESS_RIGHTS_ACCESS_CHECK\" CHECK (ACCESS_RIGHT = 'none' OR ACCESS_RIGHT = 'read' OR ACCESS_RIGHT  = 'write') ENABLE, CONSTRAINT \"SVN_ACCESS_RIGHTS_SVNGROU_FK1\" FOREIGN KEY (\"GROUP_ID\") REFERENCES $schema.\"SVNGROUPS\" (\"ID\") ON DELETE CASCADE ENABLE, CONSTRAINT \"SVN_ACCESS_RIGHTS_SVNPROJ_FK1\" FOREIGN KEY (\"PROJECT_ID\") REFERENCES $schema.\"SVNPROJECTS\" (\"ID\") ON DELETE CASCADE ENABLE, CONSTRAINT \"SVN_ACCESS_RIGHTS_SVNUSER_FK1\" FOREIGN KEY (\"USER_ID\") REFERENCES $schema.\"SVNUSERS\" (\"ID\") ON DELETE CASCADE ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVN_ACCESS_RIGHTS\" IS 'Table of user or group access rights'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVNACCESSRIGHTSPROJECTID_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\" (\"PROJECT_ID\"";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_ACCESS_RIGHTS_DELETED_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\" (\"DELETED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_ACCESS_RIGHTS_GROUP_ID_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\" (\"GROUP_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_ACCESS_RIGHTS_USER_ID_IDX\" ON $schema.\"SVN_ACCESS_RIGHTS\" (\"USER_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVN_ACCESS_RIGHTS_TRG\" BEFORE
												  INSERT ON $schema.SVN_ACCESS_RIGHTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_ACCESS_RIGHTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVN_ACCESS_RIGHTS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnGroupsResponsibleTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVN_GROUPS_RESPONSIBLE_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVN_GROUPS_RESPONSIBLE\" (\"ID\"            NUMBER(*,0) NOT NULL ENABLE, \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE, \"GROUP_ID\"      NUMBER(*,0) NOT NULL ENABLE, \"ALLOWED\"       VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"SVN_GROUPS_RESPONSIBLE_PK\" PRIMARY KEY (\"ID\")  ENABLE, CONSTRAINT \"ALLOWED_CHECK\" CHECK (ALLOWED = 'none' OR ALLOWED = 'read' OR ALLOWED = 'edit' OR ALLOWED = 'delete') ENABLE, CONSTRAINT \"GROUP_ID_CHECK\" CHECK (GROUP_ID >= 0) ENABLE, CONSTRAINT \"USER_ID_CHECK\" CHECK (USER_ID   >= 0) ENABLE, CONSTRAINT \"SVN_GROUPS_RESPONSIBLE_SV_FK1\" FOREIGN KEY (\"USER_ID\") REFERENCES $schema.\"SVNUSERS\" (\"ID\") ON DELETE CASCADE ENABLE, CONSTRAINT \"SVN_GROUPS_RESPONSIBLE_SV_FK2\" FOREIGN KEY (\"GROUP_ID\") REFERENCES $schema.\"SVNGROUPS\" (\"ID\") ON DELETE CASCADE ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVN_GROUPS_RESPONSIBLE\" IS 'Table of group responsible people'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"DELETED_IDX\" ON $schema.\"SVN_GROUPS_RESPONSIBLE\" (\"DELETED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_GROUPS_RESPONSIBLE_1_IDX\" ON $schema.\"SVN_GROUPS_RESPONSIBLE\" (\"USER_ID\", \"GROUP_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVN_GROUPS_RESPONSIBLE_TRG\" BEFORE
												  INSERT ON $schema.SVN_GROUPS_RESPONSIBLE FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_GROUPS_RESPONSIBLE_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVN_GROUPS_RESPONSIBLE_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnProjectsMailinglistsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVN_PROJECTS_MAILINGLISTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVN_PROJECTS_MAILINGLISTS\" (\"ID\"               NUMBER(*,0) NOT NULL ENABLE, \"PROJECT_ID\"       NUMBER(*,0) NOT NULL ENABLE, \"MAILINGLISTEN_ID\" NUMBER(*,0) NOT NULL ENABLE, \"CREATED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"     VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"         VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\"    VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"          VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETD_USER\"      VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"SVN_PROJECTS_MAILINGLISTS_PK\" PRIMARY KEY (\"ID\")  ENABLE, CONSTRAINT \"SVN_PROJECTS_MAILINGLISTS_FK1\" FOREIGN KEY (\"PROJECT_ID\") REFERENCES $schema.\"SVNPROJECTS\" (\"ID\") ON DELETE CASCADE ENABLE, CONSTRAINT \"SVN_PROJECTS_MAILINGLISTS_FK2\" FOREIGN KEY (\"MAILINGLISTEN_ID\") REFERENCES $schema.\"SVNMAILINGLISTS\" (\"ID\") ON DELETE CASCADE ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVN_PROJECTS_MAILINGLISTS\" IS 'Table of modules and mailinglist relations'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"MAILINGLISTEN_ID_IDX\" ON $schema.\"SVN_PROJECTS_MAILINGLISTS\" (\"MAILINGLISTEN_ID\"";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"MAILINGLISTS_1_IDX\" ON $schema.\"SVN_PROJECTS_MAILINGLISTS\" (\"PROJECT_ID\", \"MAILINGLISTEN_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVN_PROJECTS_MAILINGLISTS_TRG\" BEFORE
												  INSERT ON $schema.SVN_PROJECTS_MAILINGLISTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_PROJECTS_MAILINGLISTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVN_PROJECTS_MAILINGLISTS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnProjectsresponsibleTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVN_PROJECTS_RESPONSIBLE_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVN_PROJECTS_RESPONSIBLE\" (\"ID\"            NUMBER(*,0) NOT NULL ENABLE, \"PROJECT_ID\"    NUMBER(*,0) NOT NULL ENABLE, \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"SVN_PROJECTS_RESPONSIBLE_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVN_PROJECTS_RESPONSIBLE\" IS 'Table of project responsible users'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"PRJ_RESP_DELETED_IDX\" ON $schema.\"SVN_PROJECTS_RESPONSIBLE\" (\"DELETED\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"PRJ_RESP_PROJECT_ID_IDX\" ON $schema.\"SVN_PROJECTS_RESPONSIBLE\" (\"PROJECT_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVN_PROJECTS_RESPONSIBLE_TRG\" BEFORE
												  INSERT ON $schema.SVN_PROJECTS_RESPONSIBLE FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_PROJECTS_RESPONSIBLE_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVN_PROJECTS_RESPONSIBLE_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createSvnUsersGroupsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"SVN_USERS_GROUPS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"SVN_USERS_GROUPS\" (\"ID\"            NUMBER(*,0) NOT NULL ENABLE, \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE, \"GROUP_ID\"      NUMBER(*,0) NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', ONSTRAINT \"SVN_USERS_GROUPS_PK\" PRIMARY KEY (\"ID\")  ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"SVN_USERS_GROUPS\" IS 'Table of user group relations'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_USERS_GROUPS_DELETED_IDX\" ON $schema.\"SVN_USERS_GROUPS\" (\"DELETED\" )";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_USERS_GROUPS_GROUP_ID_IDX\" ON $schema.\"SVN_USERS_GROUPS\" (\"GROUP_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"SVN_USERS_GROUPS_USER_ID_IDX\" ON $schema.\"SVN_USERS_GROUPS\" (\"USER_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"SVN_USERS_GROUPS_TRG\" BEFORE
												  INSERT ON $schema.SVN_USERS_GROUPS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT SVN_USERS_GROUPS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"SVN_USERS_GROUPS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function createUserRightsTableOracle($dbh, $schema) {

    $query = "CREATE SEQUENCE $schema.\"USERS_RIGHTS_SEQ\" MINVALUE 1 MAXVALUE 999999999999999999999999999 INCREMENT BY 1 START WITH 1 CACHE 20 NOORDER NOCYCLE";
    db_query_install($query, $dbh);
    
    $query = "CREATE TABLE $schema.\"USERS_RIGHTS\" (\"ID\"            NUMBER(*,0) NOT NULL ENABLE, \"USER_ID\"       NUMBER(*,0) NOT NULL ENABLE, \"RIGHT_ID\"      NUMBER(*,0) NOT NULL ENABLE, \"ALLOWED\"       VARCHAR2(255 BYTE) DEFAULT 'none' NOT NULL ENABLE, \"CREATED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"CREATED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', \"MODIFIED\"      VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"MODIFIED_USER\" VARCHAR2(255 BYTE) DEFAULT '', \"DELETED\"       VARCHAR2(14 BYTE) DEFAULT '00000000000000' NOT NULL ENABLE, \"DELETED_USER\"  VARCHAR2(255 BYTE) DEFAULT '', CONSTRAINT \"USERS_RIGHTS_PK\" PRIMARY KEY (\"ID\")  ENABLE, CONSTRAINT \"USERS_RIGHTS_ALLOWED_CHECK\" CHECK (ALLOWED = 'none' OR ALLOWED = 'read' OR ALLOWED = 'add' OR ALLOWED = 'edit' OR ALLOWED = 'delete') ENABLE, CONSTRAINT \"USERS_RIGHTS_RIGHT_ID_FKEY\" FOREIGN KEY (\"RIGHT_ID\") REFERENCES $schema.\"RIGHTS\" (\"ID\") ON DELETE CASCADE ENABLE, CONSTRAINT \"USERS_RIGHTS_USER_ID_FKEY\" FOREIGN KEY (\"USER_ID\") REFERENCES $schema.\"SVNUSERS\" (\"ID\") ON DELETE CASCADE ENABLE)";
    db_query_install($query, $dbh);
    
    $query = "COMMENT ON TABLE $schema.\"USERS_RIGHTS\" IS 'Table of granted user rights'";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"USERS_RIGHTS_RIGHT_ID_IDX\" ON $schema.\"USERS_RIGHTS\" (\"RIGHT_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE INDEX $schema.\"USERS_RIGHTS_USER_ID_IDX\" ON $schema.\"USERS_RIGHTS\" (\"USER_ID\")";
    db_query_install($query, $dbh);
    
    $query = "CREATE OR REPLACE TRIGGER $schema.\"USERS_RIGHTS_TRG\" BEFORE
												  INSERT ON $schema.USERS_RIGHTS FOR EACH ROW BEGIN <<COLUMN_SEQUENCES>> BEGIN IF :NEW.ID IS NULL THEN
												  SELECT USERS_RIGHTS_SEQ.NEXTVAL INTO :NEW.ID FROM DUAL;
												END IF;
												END COLUMN_SEQUENCES;
												END;";
    db_query_install($query, $dbh);
    
    $query = "ALTER TRIGGER $schema.\"USERS_RIGHTS_TRG\" ENABLE";
    db_query_install($query, $dbh);
    
}

//
//
//
function loadOracleDbData($dbh, $schema) {

    db_ta(BEGIN, $dbh);
    
    $error = 0;
    $tMessage = "";
    $dbnow = db_now();
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('User admin', 'Administer users', 'Benutzer verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Group admin', 'Administer groups', 'Gruppen verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Project admin', 'Administer projects', 'Projekte verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Repository admin', 'Administer repositories', 'Repositories verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Access rights admin', 'Administer repository access rights', 'Repository Zugriffsrechte verwalten', 'delete', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ' )";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Create files', 'Create access files', 'Zugriffs-Kontroll-Dateien generieren', 'edit', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
    db_query_install($query, $dbh);
    
    $query = "INSERT INTO $schema.rights (right_name, description_en, description_de, allowed_action, created, created_user, modified, modified_user, deleted, deleted_user) " . "VALUES ('Reports', 'Show reports', 'Berichte ansehen', 'read', '$dbnow', 'install', '00000000000000', ' ', '00000000000000', ' ')";
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