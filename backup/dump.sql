--
-- PostgreSQL database dump
--

-- Dumped from database version 10.12 (Ubuntu 10.12-0ubuntu0.18.04.1)
-- Dumped by pg_dump version 10.12 (Ubuntu 10.12-0ubuntu0.18.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: book; Type: TABLE; Schema: public; Owner: librarian
--

CREATE TABLE public.book (
    id integer NOT NULL,
    name_book character varying(255) NOT NULL,
    author_book character varying(255) NOT NULL,
    image_location character varying(32) NOT NULL,
    book_location character varying(32) NOT NULL,
    reading_date date NOT NULL
);


ALTER TABLE public.book OWNER TO librarian;

--
-- Name: book_id_seq; Type: SEQUENCE; Schema: public; Owner: librarian
--

CREATE SEQUENCE public.book_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.book_id_seq OWNER TO librarian;

--
-- Name: doctrine_migration_versions; Type: TABLE; Schema: public; Owner: librarian
--

CREATE TABLE public.doctrine_migration_versions (
    version character varying(191) NOT NULL,
    executed_at timestamp(0) without time zone DEFAULT NULL::timestamp without time zone,
    execution_time integer
);


ALTER TABLE public.doctrine_migration_versions OWNER TO librarian;

--
-- Name: user; Type: TABLE; Schema: public; Owner: librarian
--

CREATE TABLE public."user" (
    id integer NOT NULL,
    username character varying(180) NOT NULL,
    roles json NOT NULL,
    password character varying(255) NOT NULL
);


ALTER TABLE public."user" OWNER TO librarian;

--
-- Name: user_id_seq; Type: SEQUENCE; Schema: public; Owner: librarian
--

CREATE SEQUENCE public.user_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.user_id_seq OWNER TO librarian;

--
-- Data for Name: book; Type: TABLE DATA; Schema: public; Owner: librarian
--

COPY public.book (id, name_book, author_book, image_location, book_location, reading_date) FROM stdin;
7	simple script	me	b52e2bd6d906fb479877d22352da47ca	7E766F4FCD50982CCBDDD3920F71E9D7	2020-12-02
8	UNIX History	Wiki	b52e2bd6d906fb479877d22352da47ca	2C84F92A672D769B2D8E0842CA6FEFA2	2020-12-01
13	test	me	7e18fb565e4586c7612bcc782022b0d9	7e766f4fcd50982ccbddd3920f71e9d7	2021-01-01
16	111111113213	3213111111	13aed7612a58cc215a1719caf097ed23	4cdd68c062bb2d93c670766072fec3f3	2016-11-01
\.


--
-- Data for Name: doctrine_migration_versions; Type: TABLE DATA; Schema: public; Owner: librarian
--

COPY public.doctrine_migration_versions (version, executed_at, execution_time) FROM stdin;
DoctrineMigrations\\Version20200616113826	2020-06-16 14:38:39	38
\.


--
-- Data for Name: user; Type: TABLE DATA; Schema: public; Owner: librarian
--

COPY public."user" (id, username, roles, password) FROM stdin;
4	booklover	["ROLE_ADMIN"]	$argon2id$v=19$m=65536,t=4,p=1$7S+mniTSgJGHoAppdJNzYw$YXXY/O6rY5llSkVsGDIxHPWcmLWf8UuPREjB3aK3Lt4
\.


--
-- Name: book_id_seq; Type: SEQUENCE SET; Schema: public; Owner: librarian
--

SELECT pg_catalog.setval('public.book_id_seq', 16, true);


--
-- Name: user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: librarian
--

SELECT pg_catalog.setval('public.user_id_seq', 4, true);


--
-- Name: book book_pkey; Type: CONSTRAINT; Schema: public; Owner: librarian
--

ALTER TABLE ONLY public.book
    ADD CONSTRAINT book_pkey PRIMARY KEY (id);


--
-- Name: doctrine_migration_versions doctrine_migration_versions_pkey; Type: CONSTRAINT; Schema: public; Owner: librarian
--

ALTER TABLE ONLY public.doctrine_migration_versions
    ADD CONSTRAINT doctrine_migration_versions_pkey PRIMARY KEY (version);


--
-- Name: user user_pkey; Type: CONSTRAINT; Schema: public; Owner: librarian
--

ALTER TABLE ONLY public."user"
    ADD CONSTRAINT user_pkey PRIMARY KEY (id);


--
-- Name: uniq_8d93d649f85e0677; Type: INDEX; Schema: public; Owner: librarian
--

CREATE UNIQUE INDEX uniq_8d93d649f85e0677 ON public."user" USING btree (username);


--
-- PostgreSQL database dump complete
--

