--
-- PostgreSQL database dump
--

-- Dumped from database version 17.5
-- Dumped by pg_dump version 17.5

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: bodega; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bodega (
    codigo character varying(5) NOT NULL,
    nombre character varying(100) NOT NULL,
    direccion text,
    dotacion integer DEFAULT 0 NOT NULL,
    estado character varying(11) DEFAULT 'Activada'::character varying NOT NULL,
    creado_en timestamp without time zone DEFAULT now() NOT NULL,
    CONSTRAINT bodega_dotacion_check CHECK ((dotacion >= 0))
);


ALTER TABLE public.bodega OWNER TO postgres;

--
-- Name: bodega_encargado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.bodega_encargado (
    id integer NOT NULL,
    bodega_codigo character varying(5) NOT NULL,
    encargado_id integer NOT NULL
);


ALTER TABLE public.bodega_encargado OWNER TO postgres;

--
-- Name: bodega_encargado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.bodega_encargado_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.bodega_encargado_id_seq OWNER TO postgres;

--
-- Name: bodega_encargado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.bodega_encargado_id_seq OWNED BY public.bodega_encargado.id;


--
-- Name: encargado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.encargado (
    id integer NOT NULL,
    run character varying(12) NOT NULL,
    nombre character varying(100) NOT NULL,
    apellido_paterno character varying(100) NOT NULL,
    apellido_materno character varying(100),
    direccion text,
    telefono character varying(20)
);


ALTER TABLE public.encargado OWNER TO postgres;

--
-- Name: encargado_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.encargado_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.encargado_id_seq OWNER TO postgres;

--
-- Name: encargado_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.encargado_id_seq OWNED BY public.encargado.id;


--
-- Name: bodega_encargado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bodega_encargado ALTER COLUMN id SET DEFAULT nextval('public.bodega_encargado_id_seq'::regclass);


--
-- Name: encargado id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.encargado ALTER COLUMN id SET DEFAULT nextval('public.encargado_id_seq'::regclass);


--
-- Data for Name: bodega; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.bodega VALUES ('B0001', 'test1', 'test1', 1, 'Activada', '2025-07-03 01:20:10.944728');
INSERT INTO public.bodega VALUES ('B0002', 'test2', 'test2', 2, 'Activada', '2025-07-03 01:20:20.04837');
INSERT INTO public.bodega VALUES ('B0003', 'test3', 'test3', 3, 'Activada', '2025-07-03 01:20:31.818031');
INSERT INTO public.bodega VALUES ('B0004', 'testeliminar1', 'testeliminar1', 1, 'Activada', '2025-07-03 01:20:50.70501');
INSERT INTO public.bodega VALUES ('B0005', 'testeliminar2', 'testeliminar2', 2, 'Activada', '2025-07-03 01:21:02.662736');
INSERT INTO public.bodega VALUES ('B0006', 'testeliminar3', 'testeliminar3', 3, 'Activada', '2025-07-03 01:21:09.874038');
INSERT INTO public.bodega VALUES ('B0009', 'teseditar33', 'teseditar3', 3, 'Activada', '2025-07-03 01:21:39.079813');
INSERT INTO public.bodega VALUES ('B0008', 'teseditar22', 'teseditar222', 7, 'Activada', '2025-07-03 01:21:31.614033');
INSERT INTO public.bodega VALUES ('B0007', 'teseditar111', 'teseditar11', 0, 'Activada', '2025-07-03 01:21:24.556901');


--
-- Data for Name: bodega_encargado; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.bodega_encargado VALUES (33, 'B0001', 1);
INSERT INTO public.bodega_encargado VALUES (34, 'B0002', 3);
INSERT INTO public.bodega_encargado VALUES (35, 'B0003', 1);
INSERT INTO public.bodega_encargado VALUES (36, 'B0004', 1);
INSERT INTO public.bodega_encargado VALUES (37, 'B0005', 3);
INSERT INTO public.bodega_encargado VALUES (38, 'B0006', 2);
INSERT INTO public.bodega_encargado VALUES (41, 'B0009', 1);
INSERT INTO public.bodega_encargado VALUES (40, 'B0008', 3);
INSERT INTO public.bodega_encargado VALUES (39, 'B0007', 1);


--
-- Data for Name: encargado; Type: TABLE DATA; Schema: public; Owner: postgres
--

INSERT INTO public.encargado VALUES (1, '12.345.678-9', 'Carlos', 'González', 'Pérez', 'Av. Los Leones 1234, Santiago', '+56912345678');
INSERT INTO public.encargado VALUES (2, '15.789.321-0', 'María', 'Rodríguez', 'Fernández', 'Calle O’Higgins 456, Viña del Mar', '+56987654321');
INSERT INTO public.encargado VALUES (3, '18.456.789-1', 'Javiera', 'Muñoz', 'López', 'Pasaje Las Rosas 789, Concepción', '+56911223344');


--
-- Name: bodega_encargado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.bodega_encargado_id_seq', 41, true);


--
-- Name: encargado_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.encargado_id_seq', 3, true);


--
-- Name: bodega_encargado bodega_encargado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bodega_encargado
    ADD CONSTRAINT bodega_encargado_pkey PRIMARY KEY (id);


--
-- Name: bodega bodega_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bodega
    ADD CONSTRAINT bodega_pkey PRIMARY KEY (codigo);


--
-- Name: encargado encargado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.encargado
    ADD CONSTRAINT encargado_pkey PRIMARY KEY (id);


--
-- Name: encargado encargado_run_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.encargado
    ADD CONSTRAINT encargado_run_key UNIQUE (run);


--
-- Name: bodega_encargado bodega_encargado_bodega_codigo_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bodega_encargado
    ADD CONSTRAINT bodega_encargado_bodega_codigo_fkey FOREIGN KEY (bodega_codigo) REFERENCES public.bodega(codigo) ON DELETE CASCADE;


--
-- Name: bodega_encargado bodega_encargado_encargado_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.bodega_encargado
    ADD CONSTRAINT bodega_encargado_encargado_id_fkey FOREIGN KEY (encargado_id) REFERENCES public.encargado(id) ON DELETE CASCADE;


--
-- PostgreSQL database dump complete
--

