SET client_encoding = 'UTF8';
SET standard_conforming_strings = off;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET escape_string_warning = off;

SET search_path = ;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: help; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE help (
    id bigint NOT NULL,
    topic character varying(255) NOT NULL,
    headline_en character varying(255) NOT NULL,
    headline_de character varying(255) NOT NULL,
    helptext_de text NOT NULL,
    helptext_en text NOT NULL
);


ALTER TABLE help OWNER TO svnam;

--
-- Name: TABLE help; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE help IS 'Table of help texts';


--
-- Name: help_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE help_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE help_id_seq OWNER TO svnam;

--
-- Name: help_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE help_id_seq OWNED BY help.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE help ALTER COLUMN id SET DEFAULT nextval('help_id_seq'::regclass);


--
-- Name: help_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY help
    ADD CONSTRAINT help_pkey PRIMARY KEY (id);


--
-- Name: help_topic_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX help_topic_idx ON help USING btree (topic);



--
-- Name: log; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE log (
    id bigint NOT NULL,
    "logtimestamp" timestamp without time zone NOT NULL,
    username character varying(255) NOT NULL,
    ipaddress character varying(15) NOT NULL,
    logmessage text NOT NULL
);


ALTER TABLE log OWNER TO svnam;

--
-- Name: TABLE log; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE log IS 'Table of log messages';


--
-- Name: log_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE log_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE log_id_seq OWNER TO svnam;

--
-- Name: log_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE log_id_seq OWNED BY log.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE log ALTER COLUMN id SET DEFAULT nextval('log_id_seq'::regclass);


--
-- Name: log_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY log
    ADD CONSTRAINT log_pkey PRIMARY KEY (id);


--
-- Name: log_timestamp_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX log_timestamp_idx ON log USING btree ("logtimestamp");



--
-- Name: preferences; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE preferences (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    page_size integer NOT NULL,
    user_sort_fields character varying(255) NOT NULL,
    user_sort_order character varying(255) NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL
);


ALTER TABLE preferences OWNER TO svnam;

--
-- Name: TABLE preferences; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE preferences IS 'Table of user preferences';


--
-- Name: preferences_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE preferences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE preferences_id_seq OWNER TO svnam;

--
-- Name: preferences_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE preferences_id_seq OWNED BY preferences.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE preferences ALTER COLUMN id SET DEFAULT nextval('preferences_id_seq'::regclass);


--
-- Name: preferences_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY preferences
    ADD CONSTRAINT preferences_pkey PRIMARY KEY (id);


--
-- Name: preferences_user_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX preferences_user_id_idx ON preferences USING btree (user_id);



--
-- Name: rights; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE rights (
    id bigint NOT NULL,
    right_name character varying(255) NOT NULL,
    description_en character varying(255) NOT NULL,
    description_de character varying(255) NOT NULL,
    allowed_action character varying DEFAULT 'none'::character varying NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT rights_allowed_action_check CHECK (((allowed_action)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'edit'::character varying, 'delete'::character varying])::text[])))
);


ALTER TABLE rights OWNER TO svnam;

--
-- Name: TABLE rights; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE rights IS 'Table of rights to grant to users';


--
-- Name: rights_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE rights_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE rights_id_seq OWNER TO svnam;

--
-- Name: rights_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE rights_id_seq OWNED BY rights.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE rights ALTER COLUMN id SET DEFAULT nextval('rights_id_seq'::regclass);


--
-- Name: rights_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY rights
    ADD CONSTRAINT rights_pkey PRIMARY KEY (id);

    
    
--
-- Name: workinfo; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE workinfo (
    id bigint NOT NULL,
    "usertimestamp" timestamp without time zone DEFAULT now() NOT NULL,
    action character varying(255) NOT NULL,
    status character varying(255) NOT NULL,
    type character varying(255) NOT NULL
);


ALTER TABLE workinfo OWNER TO svnam;

--
-- Name: TABLE workinfo; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE workinfo IS 'table of workinfo';


--
-- Name: semaphores_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE semaphores_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE semaphores_id_seq OWNER TO svnam;

--
-- Name: semaphores_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE semaphores_id_seq OWNED BY semaphores.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE semaphores ALTER COLUMN id SET DEFAULT nextval('semaphores_id_seq'::regclass);


--
-- Name: semaphores_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY semaphores
    ADD CONSTRAINT semaphores_pkey PRIMARY KEY (id);

    
    
    
--
-- Name: sessions; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE sessions (
    session_id character varying(255) NOT NULL,
    session_expires integer DEFAULT 0 NOT NULL,
    session_data text,
    CONSTRAINT sessions_session_expires_check CHECK ((session_expires >= 0))
);


ALTER TABLE sessions OWNER TO svnam;

--
-- Name: sessions_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (session_id);


--
-- Name: sessions_session_expires_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX sessions_session_expires_idx ON sessions USING btree (session_expires);
    
    
    
--
-- Name: svn_access_rights; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svn_access_rights (
    id bigint NOT NULL,
    project_id integer,
    user_id integer,
    group_id integer,
    path text NOT NULL,
    valid_from character varying(14) NOT NULL,
    valid_until character varying(14) NOT NULL,
    access_right character varying DEFAULT 'none'::character varying NOT NULL,
    recursive character varying DEFAULT 'yes'::character varying NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT svn_access_rights_access_right_check CHECK (((access_right)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'write'::character varying])::text[]))),
    CONSTRAINT svn_access_rights_recursive_check CHECK (((recursive)::text = ANY ((ARRAY['yes'::character varying, 'no'::character varying])::text[])))
);


ALTER TABLE svn_access_rights OWNER TO svnam;

--
-- Name: TABLE svn_access_rights; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svn_access_rights IS 'Table of user or group access rights';


--
-- Name: COLUMN svn_access_rights.valid_from; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON COLUMN svn_access_rights.valid_from IS 'JHJJMMTT';


--
-- Name: COLUMN svn_access_rights.valid_until; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON COLUMN svn_access_rights.valid_until IS 'JHJJMMTT';


--
-- Name: svn_access_rights_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svn_access_rights_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svn_access_rights_id_seq OWNER TO svnam;

--
-- Name: svn_access_rights_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svn_access_rights_id_seq OWNED BY svn_access_rights.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svn_access_rights ALTER COLUMN id SET DEFAULT nextval('svn_access_rights_id_seq'::regclass);


--
-- Name: svn_access_rights_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svn_access_rights
    ADD CONSTRAINT svn_access_rights_pkey PRIMARY KEY (id);


--
-- Name: svn_access_rights_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_access_rights_deleted_idx ON svn_access_rights USING btree (deleted);


--
-- Name: svn_access_rights_group_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_access_rights_group_id_idx ON svn_access_rights USING btree (group_id);


--
-- Name: svn_access_rights_path_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_access_rights_path_idx ON svn_access_rights USING btree (path);


--
-- Name: svn_access_rights_project_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_access_rights_project_id_idx ON svn_access_rights USING btree (project_id);


--
-- Name: svn_access_rights_user_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_access_rights_user_id_idx ON svn_access_rights USING btree (user_id);


--
-- Name: svn_access_rights_group_id_fkey; Type: FK CONSTRAINT; Schema: svnam; Owner: svnam
--

ALTER TABLE ONLY svn_access_rights
    ADD CONSTRAINT svn_access_rights_group_id_fkey FOREIGN KEY (group_id) REFERENCES svngroups(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: svn_access_rights_project_id_fkey; Type: FK CONSTRAINT; Schema: svnam; Owner: svnam
--

ALTER TABLE ONLY svn_access_rights
    ADD CONSTRAINT svn_access_rights_project_id_fkey FOREIGN KEY (project_id) REFERENCES svnprojects(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: svn_access_rights_user_id_fkey; Type: FK CONSTRAINT; Schema: svnam; Owner: svnam
--

ALTER TABLE ONLY svn_access_rights
    ADD CONSTRAINT svn_access_rights_user_id_fkey FOREIGN KEY (user_id) REFERENCES svnusers(id) ON UPDATE RESTRICT ON DELETE CASCADE;
    
    
    
 --
-- Name: svn_groups_responsible; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svn_groups_responsible (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    group_id integer NOT NULL,
    allowed character varying DEFAULT 'none'::character varying NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT svn_groups_responsible_allowed_check CHECK (((allowed)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'edit'::character varying, 'delete'::character varying])::text[]))),
    CONSTRAINT svn_groups_responsible_group_id_check CHECK ((group_id >= 0)),
    CONSTRAINT svn_groups_responsible_user_id_check CHECK ((user_id >= 0))
);


ALTER TABLE svn_groups_responsible OWNER TO svnam;

--
-- Name: svn_groups_responsible_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svn_groups_responsible_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svn_groups_responsible_id_seq OWNER TO svnam;

--
-- Name: svn_groups_responsible_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svn_groups_responsible_id_seq OWNED BY svn_groups_responsible.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svn_groups_responsible ALTER COLUMN id SET DEFAULT nextval('svn_groups_responsible_id_seq'::regclass);


--
-- Name: svn_groups_responsible_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svn_groups_responsible
    ADD CONSTRAINT svn_groups_responsible_pkey PRIMARY KEY (id);


--
-- Name: svn_groups_responsible_1_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_groups_responsible_1_idx ON svn_groups_responsible USING btree (user_id, group_id);


--
-- Name: svn_groups_responsible_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_groups_responsible_deleted_idx ON svn_groups_responsible USING btree (deleted);
 
 
 
--
-- Name: svn_projects_mailinglists; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svn_projects_mailinglists (
    id bigint NOT NULL,
    project_id integer NOT NULL,
    mailinglisten_id integer NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT svn_projects_mailinglists_mailinglisten_id_check CHECK ((mailinglisten_id >= 0)),
    CONSTRAINT svn_projects_mailinglists_project_id_check CHECK ((project_id >= 0))
);


ALTER TABLE svn_projects_mailinglists OWNER TO svnam;

--
-- Name: TABLE svn_projects_mailinglists; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svn_projects_mailinglists IS 'Table of modules and mailinglist relations';


--
-- Name: svn_projects_mailinglists_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svn_projects_mailinglists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svn_projects_mailinglists_id_seq OWNER TO svnam;

--
-- Name: svn_projects_mailinglists_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svn_projects_mailinglists_id_seq OWNED BY svn_projects_mailinglists.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svn_projects_mailinglists ALTER COLUMN id SET DEFAULT nextval('svn_projects_mailinglists_id_seq'::regclass);


--
-- Name: svn_projects_mailinglists_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svn_projects_mailinglists
    ADD CONSTRAINT svn_projects_mailinglists_pkey PRIMARY KEY (id);


--
-- Name: svn_projects_mailinglists_1_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_projects_mailinglists_1_idx ON svn_projects_mailinglists USING btree (project_id, mailinglisten_id);


--
-- Name: svn_projects_mailinglists_mailinglisten_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_projects_mailinglists_mailinglisten_id_idx ON svn_projects_mailinglists USING btree (mailinglisten_id);
 
 
 
--
-- Name: svn_projects_responsible; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svn_projects_responsible (
    id integer DEFAULT nextval('svn_projects_responsible_id_seq'::regclass) NOT NULL,
    project_id integer NOT NULL,
    user_id integer NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL
);


ALTER TABLE svn_projects_responsible OWNER TO svnam;

--
-- Name: TABLE svn_projects_responsible; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svn_projects_responsible IS 'Table of project responsible users';


--
-- Name: svn_projects_responsible_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svn_projects_responsible
    ADD CONSTRAINT svn_projects_responsible_pkey PRIMARY KEY (id);


--
-- Name: svn_projects_responsible_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_projects_responsible_deleted_idx ON svn_projects_responsible USING btree (deleted);


--
-- Name: svn_projects_responsible_project_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_projects_responsible_project_id_idx ON svn_projects_responsible USING btree (project_id);

 
 
 
--
-- Name: svn_users_groups; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svn_users_groups (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    group_id integer NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT svn_users_groups_group_id_check CHECK ((group_id >= 0)),
    CONSTRAINT svn_users_groups_user_id_check CHECK ((user_id >= 0))
);


ALTER TABLE svn_users_groups OWNER TO svnam;

--
-- Name: TABLE svn_users_groups; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svn_users_groups IS 'Table of user group relations';


--
-- Name: svn_users_groups_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svn_users_groups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svn_users_groups_id_seq OWNER TO svnam;

--
-- Name: svn_users_groups_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svn_users_groups_id_seq OWNED BY svn_users_groups.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svn_users_groups ALTER COLUMN id SET DEFAULT nextval('svn_users_groups_id_seq'::regclass);


--
-- Name: svn_users_groups_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svn_users_groups
    ADD CONSTRAINT svn_users_groups_pkey PRIMARY KEY (id);


--
-- Name: svn_users_groups_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_users_groups_deleted_idx ON svn_users_groups USING btree (deleted);


--
-- Name: svn_users_groups_group_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_users_groups_group_id_idx ON svn_users_groups USING btree (group_id);


--
-- Name: svn_users_groups_user_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svn_users_groups_user_id_idx ON svn_users_groups USING btree (user_id);
 
 
 
--
-- Name: svngroups; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svngroups (
    id bigint NOT NULL,
    groupname character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL
);


ALTER TABLE svngroups OWNER TO svnam;

--
-- Name: TABLE svngroups; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svngroups IS 'Table of svn user groups';


--
-- Name: svngroups_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svngroups_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svngroups_id_seq OWNER TO svnam;

--
-- Name: svngroups_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svngroups_id_seq OWNED BY svngroups.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svngroups ALTER COLUMN id SET DEFAULT nextval('svngroups_id_seq'::regclass);


--
-- Name: svngroups_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svngroups
    ADD CONSTRAINT svngroups_pkey PRIMARY KEY (id);


--
-- Name: svngroups_groupname_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svngroups_groupname_idx ON svngroups USING btree (groupname);
 
 
 
--
-- Name: svnmailinglists; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svnmailinglists (
    id bigint NOT NULL,
    mailinglist character varying(255) NOT NULL,
    emailaddress character varying(255) NOT NULL,
    description text NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL
);


ALTER TABLE svnmailinglists OWNER TO svnam;

--
-- Name: TABLE svnmailinglists; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svnmailinglists IS 'Table of available svn mailing lists';


--
-- Name: svnmailinglists_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svnmailinglists_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svnmailinglists_id_seq OWNER TO svnam;

--
-- Name: svnmailinglists_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svnmailinglists_id_seq OWNED BY svnmailinglists.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svnmailinglists ALTER COLUMN id SET DEFAULT nextval('svnmailinglists_id_seq'::regclass);


--
-- Name: svnmailinglists_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svnmailinglists
    ADD CONSTRAINT svnmailinglists_pkey PRIMARY KEY (id);

 
 
 
--
-- Name: svnpasswordreset; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svnpasswordreset (
    id bigint NOT NULL,
    unixtime integer NOT NULL,
    username character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    idstr character varying(255) NOT NULL
);


ALTER TABLE svnpasswordreset OWNER TO svnam;

--
-- Name: svnpasswordreset_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svnpasswordreset_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svnpasswordreset_id_seq OWNER TO svnam;

--
-- Name: svnpasswordreset_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svnpasswordreset_id_seq OWNED BY svnpasswordreset.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svnpasswordreset ALTER COLUMN id SET DEFAULT nextval('svnpasswordreset_id_seq'::regclass);


--
-- Name: svnpasswordreset_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svnpasswordreset
    ADD CONSTRAINT svnpasswordreset_pkey PRIMARY KEY (id);


 
 
 
--
-- Name: svnprojects; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svnprojects (
    id bigint NOT NULL,
    repo_id integer NOT NULL,
    svnmodule character varying(255) NOT NULL,
    modulepath character varying(255) NOT NULL,
    description character varying(255) NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT svnprojects_repo_id_check CHECK ((repo_id >= 0))
);


ALTER TABLE svnprojects OWNER TO svnam;

--
-- Name: TABLE svnprojects; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svnprojects IS 'Table of svn modules';


--
-- Name: svnprojects_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svnprojects_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svnprojects_id_seq OWNER TO svnam;

--
-- Name: svnprojects_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svnprojects_id_seq OWNED BY svnprojects.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svnprojects ALTER COLUMN id SET DEFAULT nextval('svnprojects_id_seq'::regclass);


--
-- Name: svnprojects_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svnprojects
    ADD CONSTRAINT svnprojects_pkey PRIMARY KEY (id);


--
-- Name: svnprojects_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svnprojects_deleted_idx ON svnprojects USING btree (deleted);


--
-- Name: svnprojects_repo_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svnprojects_repo_id_idx ON svnprojects USING btree (repo_id);
 
 
 
--
-- Name: svnrepos; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svnrepos (
    id bigint NOT NULL,
    reponame character varying(255) NOT NULL,
    repopath character varying(255) NOT NULL,
    repouser character varying(255) NOT NULL,
    repopassword character varying(255) NOT NULL,
    different_auth_files smallint DEFAULT 0::smallint NOT NULL,
    auth_user_file character varying(255) NOT NULL,
    svn_access_file character varying(255) NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL
);


ALTER TABLE svnrepos OWNER TO svnam;

--
-- Name: TABLE svnrepos; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svnrepos IS 'Table of svn repositories';


--
-- Name: svnrepos_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svnrepos_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svnrepos_id_seq OWNER TO svnam;

--
-- Name: svnrepos_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svnrepos_id_seq OWNED BY svnrepos.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svnrepos ALTER COLUMN id SET DEFAULT nextval('svnrepos_id_seq'::regclass);


--
-- Name: svnrepos_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svnrepos
    ADD CONSTRAINT svnrepos_pkey PRIMARY KEY (id);


--
-- Name: svnrepos_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svnrepos_deleted_idx ON svnrepos USING btree (deleted);
 
 
 
--
-- Name: svnusers; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE svnusers (
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
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    password_modified character varying(14) NOT NULL,
    superadmin smallint DEFAULT 0::smallint NOT NULL,
    securityquestion character varying(255) DEFAULT ''::character varying,
    securityanswer character varying(255) DEFAULT ''::character varying
);


ALTER TABLE svnusers OWNER TO svnam;

--
-- Name: TABLE svnusers; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE svnusers IS 'Table of all known users';


--
-- Name: svnusers_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE svnusers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE svnusers_id_seq OWNER TO svnam;

--
-- Name: svnusers_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE svnusers_id_seq OWNED BY svnusers.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE svnusers ALTER COLUMN id SET DEFAULT nextval('svnusers_id_seq'::regclass);


--
-- Name: svnusers_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svnusers
    ADD CONSTRAINT svnusers_pkey PRIMARY KEY (id);


--
-- Name: svnusers_userid_key; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY svnusers
    ADD CONSTRAINT svnusers_userid_key UNIQUE (userid, deleted);


--
-- Name: svnusers_deleted_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svnusers_deleted_idx ON svnusers USING btree (deleted);


--
-- Name: svnusers_locked_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svnusers_locked_idx ON svnusers USING btree (locked);


--
-- Name: svnusers_passwordexpires_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX svnusers_passwordexpires_idx ON svnusers USING btree (passwordexpires);
 
 
 
--
-- Name: users_rights; Type: TABLE; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE TABLE users_rights (
    id bigint NOT NULL,
    user_id integer NOT NULL,
    right_id integer NOT NULL,
    allowed character varying DEFAULT 'none'::character varying NOT NULL,
    created character varying(14) NOT NULL,
    created_user character varying(255) NOT NULL,
    modified character varying(14) NOT NULL,
    modified_user character varying(255) NOT NULL,
    deleted character varying(14) NOT NULL,
    deleted_user character varying(255) NOT NULL,
    CONSTRAINT users_rights_allowed_check CHECK (((allowed)::text = ANY ((ARRAY['none'::character varying, 'read'::character varying, 'add'::character varying, 'edit'::character varying, 'delete'::character varying])::text[])))
);


ALTER TABLE users_rights OWNER TO svnam;

--
-- Name: TABLE users_rights; Type: COMMENT; Schema: svnam; Owner: svnam
--

COMMENT ON TABLE users_rights IS 'Table of granted user rights';


--
-- Name: users_rights_id_seq; Type: SEQUENCE; Schema: svnam; Owner: svnam
--

CREATE SEQUENCE users_rights_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MAXVALUE
    NO MINVALUE
    CACHE 1;


ALTER TABLE users_rights_id_seq OWNER TO svnam;

--
-- Name: users_rights_id_seq; Type: SEQUENCE OWNED BY; Schema: svnam; Owner: svnam
--

ALTER SEQUENCE users_rights_id_seq OWNED BY users_rights.id;


--
-- Name: id; Type: DEFAULT; Schema: svnam; Owner: svnam
--

ALTER TABLE users_rights ALTER COLUMN id SET DEFAULT nextval('users_rights_id_seq'::regclass);


--
-- Name: users_rights_pkey; Type: CONSTRAINT; Schema: svnam; Owner: svnam; Tablespace: 
--

ALTER TABLE ONLY users_rights
    ADD CONSTRAINT users_rights_pkey PRIMARY KEY (id);


--
-- Name: users_rights_right_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX users_rights_right_id_idx ON users_rights USING btree (right_id);


--
-- Name: users_rights_user_id_idx; Type: INDEX; Schema: svnam; Owner: svnam; Tablespace: 
--

CREATE INDEX users_rights_user_id_idx ON users_rights USING btree (user_id);


--
-- Name: users_rights_right_id_fkey; Type: FK CONSTRAINT; Schema: svnam; Owner: svnam
--

ALTER TABLE ONLY users_rights
    ADD CONSTRAINT users_rights_right_id_fkey FOREIGN KEY (right_id) REFERENCES rights(id) ON UPDATE RESTRICT ON DELETE CASCADE;


--
-- Name: users_rights_user_id_fkey; Type: FK CONSTRAINT; Schema: svnam; Owner: svnam
--

ALTER TABLE ONLY users_rights
    ADD CONSTRAINT users_rights_user_id_fkey FOREIGN KEY (user_id) REFERENCES svnusers(id) ON UPDATE RESTRICT ON DELETE CASCADE;
 