--
-- PostgreSQL database dump
--

\restrict bj2cetIctRQQRTsUJJSfVjf74lgZkzaqRXN3hu0LpDc7OApYtyRPcOt2DNAH7TR

-- Dumped from database version 18.4 (Debian 18.4-1.pgdg12+1)
-- Dumped by pg_dump version 18.4

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

--
-- Name: public; Type: SCHEMA; Schema: -; Owner: restaurant_website_user
--

-- *not* creating schema, since initdb creates it


ALTER SCHEMA public OWNER TO restaurant_website_user;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: clients; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.clients (
    client_id integer NOT NULL,
    client_name character varying(50) NOT NULL,
    client_phone character varying(50) NOT NULL,
    client_email character varying(100) NOT NULL,
    user_id integer
);


ALTER TABLE public.clients OWNER TO restaurant_website_user;

--
-- Name: clients_client_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.clients_client_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.clients_client_id_seq OWNER TO restaurant_website_user;

--
-- Name: clients_client_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.clients_client_id_seq OWNED BY public.clients.client_id;


--
-- Name: image_gallery; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.image_gallery (
    image_id integer NOT NULL,
    image_name character varying(30) NOT NULL,
    image character varying(255) NOT NULL
);


ALTER TABLE public.image_gallery OWNER TO restaurant_website_user;

--
-- Name: image_gallery_image_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.image_gallery_image_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.image_gallery_image_id_seq OWNER TO restaurant_website_user;

--
-- Name: image_gallery_image_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.image_gallery_image_id_seq OWNED BY public.image_gallery.image_id;


--
-- Name: in_order; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.in_order (
    id integer NOT NULL,
    order_id integer NOT NULL,
    menu_id integer NOT NULL,
    quantity integer DEFAULT 1 NOT NULL
);


ALTER TABLE public.in_order OWNER TO restaurant_website_user;

--
-- Name: in_order_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.in_order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.in_order_id_seq OWNER TO restaurant_website_user;

--
-- Name: in_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.in_order_id_seq OWNED BY public.in_order.id;


--
-- Name: menu_categories; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.menu_categories (
    category_id integer NOT NULL,
    category_name character varying(50) NOT NULL
);


ALTER TABLE public.menu_categories OWNER TO restaurant_website_user;

--
-- Name: menu_categories_category_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.menu_categories_category_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menu_categories_category_id_seq OWNER TO restaurant_website_user;

--
-- Name: menu_categories_category_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.menu_categories_category_id_seq OWNED BY public.menu_categories.category_id;


--
-- Name: menus; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.menus (
    menu_id integer NOT NULL,
    menu_name character varying(100) NOT NULL,
    menu_description character varying(255) NOT NULL,
    menu_price numeric(6,2) NOT NULL,
    menu_image character varying(255) NOT NULL,
    category_id integer NOT NULL,
    calories numeric(7,1) DEFAULT NULL::numeric,
    proteins numeric(5,1) DEFAULT NULL::numeric,
    fats numeric(5,1) DEFAULT NULL::numeric,
    carbs numeric(5,1) DEFAULT NULL::numeric
);


ALTER TABLE public.menus OWNER TO restaurant_website_user;

--
-- Name: menus_menu_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.menus_menu_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.menus_menu_id_seq OWNER TO restaurant_website_user;

--
-- Name: menus_menu_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.menus_menu_id_seq OWNED BY public.menus.menu_id;


--
-- Name: placed_orders; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.placed_orders (
    order_id integer NOT NULL,
    order_time timestamp without time zone NOT NULL,
    client_id integer NOT NULL,
    user_id integer,
    delivery_address character varying(255) NOT NULL,
    bonuses_earned numeric(10,2) DEFAULT 0 NOT NULL,
    bonuses_spent numeric(10,2) DEFAULT 0 NOT NULL,
    discount_amount numeric(10,2) DEFAULT 0 NOT NULL,
    delivered smallint DEFAULT 0 NOT NULL,
    canceled smallint DEFAULT 0 NOT NULL,
    cancellation_reason character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.placed_orders OWNER TO restaurant_website_user;

--
-- Name: placed_orders_order_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.placed_orders_order_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.placed_orders_order_id_seq OWNER TO restaurant_website_user;

--
-- Name: placed_orders_order_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.placed_orders_order_id_seq OWNED BY public.placed_orders.order_id;


--
-- Name: reservations; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.reservations (
    reservation_id integer NOT NULL,
    date_created timestamp without time zone NOT NULL,
    client_id integer NOT NULL,
    selected_time timestamp without time zone NOT NULL,
    nbr_guests integer NOT NULL,
    table_id integer NOT NULL,
    liberated smallint DEFAULT 0 NOT NULL,
    canceled smallint DEFAULT 0 NOT NULL,
    cancellation_reason character varying(255) DEFAULT NULL::character varying
);


ALTER TABLE public.reservations OWNER TO restaurant_website_user;

--
-- Name: reservations_reservation_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.reservations_reservation_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.reservations_reservation_id_seq OWNER TO restaurant_website_user;

--
-- Name: reservations_reservation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.reservations_reservation_id_seq OWNED BY public.reservations.reservation_id;


--
-- Name: roles; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.roles (
    role_id integer NOT NULL,
    role_key character varying(50) NOT NULL,
    role_name character varying(100) NOT NULL,
    description text
);


ALTER TABLE public.roles OWNER TO restaurant_website_user;

--
-- Name: roles_role_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.roles_role_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.roles_role_id_seq OWNER TO restaurant_website_user;

--
-- Name: roles_role_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.roles_role_id_seq OWNED BY public.roles.role_id;


--
-- Name: tables; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.tables (
    table_id integer NOT NULL
);


ALTER TABLE public.tables OWNER TO restaurant_website_user;

--
-- Name: tables_table_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.tables_table_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.tables_table_id_seq OWNER TO restaurant_website_user;

--
-- Name: tables_table_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.tables_table_id_seq OWNED BY public.tables.table_id;


--
-- Name: user_addresses; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.user_addresses (
    address_id integer NOT NULL,
    user_id integer NOT NULL,
    label character varying(100) DEFAULT NULL::character varying,
    address character varying(255) NOT NULL,
    created_at timestamp without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


ALTER TABLE public.user_addresses OWNER TO restaurant_website_user;

--
-- Name: user_addresses_address_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.user_addresses_address_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.user_addresses_address_id_seq OWNER TO restaurant_website_user;

--
-- Name: user_addresses_address_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.user_addresses_address_id_seq OWNED BY public.user_addresses.address_id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.users (
    user_id integer NOT NULL,
    username character varying(20) NOT NULL,
    email character varying(30) NOT NULL,
    full_name character varying(50) NOT NULL,
    first_name character varying(50) DEFAULT NULL::character varying,
    last_name character varying(50) DEFAULT NULL::character varying,
    phone character varying(30) DEFAULT NULL::character varying,
    dob date,
    password character varying(100) NOT NULL,
    bonus_points numeric(10,2) DEFAULT 0 NOT NULL,
    role character varying(20) DEFAULT 'client'::character varying NOT NULL
);


ALTER TABLE public.users OWNER TO restaurant_website_user;

--
-- Name: users_user_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.users_user_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.users_user_id_seq OWNER TO restaurant_website_user;

--
-- Name: users_user_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.users_user_id_seq OWNED BY public.users.user_id;


--
-- Name: website_settings; Type: TABLE; Schema: public; Owner: restaurant_website_user
--

CREATE TABLE public.website_settings (
    option_id integer NOT NULL,
    option_name character varying(255) NOT NULL,
    option_value character varying(255) NOT NULL
);


ALTER TABLE public.website_settings OWNER TO restaurant_website_user;

--
-- Name: website_settings_option_id_seq; Type: SEQUENCE; Schema: public; Owner: restaurant_website_user
--

CREATE SEQUENCE public.website_settings_option_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER SEQUENCE public.website_settings_option_id_seq OWNER TO restaurant_website_user;

--
-- Name: website_settings_option_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: restaurant_website_user
--

ALTER SEQUENCE public.website_settings_option_id_seq OWNED BY public.website_settings.option_id;


--
-- Name: clients client_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.clients ALTER COLUMN client_id SET DEFAULT nextval('public.clients_client_id_seq'::regclass);


--
-- Name: image_gallery image_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.image_gallery ALTER COLUMN image_id SET DEFAULT nextval('public.image_gallery_image_id_seq'::regclass);


--
-- Name: in_order id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.in_order ALTER COLUMN id SET DEFAULT nextval('public.in_order_id_seq'::regclass);


--
-- Name: menu_categories category_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.menu_categories ALTER COLUMN category_id SET DEFAULT nextval('public.menu_categories_category_id_seq'::regclass);


--
-- Name: menus menu_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.menus ALTER COLUMN menu_id SET DEFAULT nextval('public.menus_menu_id_seq'::regclass);


--
-- Name: placed_orders order_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.placed_orders ALTER COLUMN order_id SET DEFAULT nextval('public.placed_orders_order_id_seq'::regclass);


--
-- Name: reservations reservation_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.reservations ALTER COLUMN reservation_id SET DEFAULT nextval('public.reservations_reservation_id_seq'::regclass);


--
-- Name: roles role_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.roles ALTER COLUMN role_id SET DEFAULT nextval('public.roles_role_id_seq'::regclass);


--
-- Name: tables table_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.tables ALTER COLUMN table_id SET DEFAULT nextval('public.tables_table_id_seq'::regclass);


--
-- Name: user_addresses address_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.user_addresses ALTER COLUMN address_id SET DEFAULT nextval('public.user_addresses_address_id_seq'::regclass);


--
-- Name: users user_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.users ALTER COLUMN user_id SET DEFAULT nextval('public.users_user_id_seq'::regclass);


--
-- Name: website_settings option_id; Type: DEFAULT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.website_settings ALTER COLUMN option_id SET DEFAULT nextval('public.website_settings_option_id_seq'::regclass);


--
-- Data for Name: clients; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.clients (client_id, client_name, client_phone, client_email, user_id) FROM stdin;
1	Clinet 1	02020202020	client1@gmail.com	\N
2	Client 10	0638383933	client10@gmail.com	\N
3	Client 11	06242556272	client11@yahoo.fr	\N
4	Client 12	030303030202	client1133@gmail.com	\N
5	Client 12	030303030	client14@gmail.com	\N
6	Client 14	0203203203	client14@gmail.com	\N
7	Client 17	0737373822	client17@gmail.com	\N
8	Client 12	02920320	client12@yahoo.fr	\N
9	Test	1034304300	test@gmail.com	\N
10	рома	89832569088	romanlox@mail.ru	\N
11	MIKI	89135963212	mikiyt94@gmail.com	\N
12	MIKI	89135963212	mikiyt94@gmail.com	\N
13	MIKI	89135963212	mikiyt94@gmail.com	\N
14	MIKI	89135963212	mikiyt94@gmail.com	\N
15	MIKI	89135963212	mikiyt94@gmail.com	\N
\.


--
-- Data for Name: image_gallery; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.image_gallery (image_id, image_name, image) FROM stdin;
1	Суши	sushi.jpg
2	Роллы	rolls.jpg
3	Интерьер	interior.jpg
4	Суши	sushi2.jpg
5	Роллы	rolls2.jpg
\.


--
-- Data for Name: in_order; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.in_order (id, order_id, menu_id, quantity) FROM stdin;
1	1	2	1
2	1	3	1
3	2	3	1
4	3	3	1
5	4	1	1
6	4	8	1
7	4	9	1
\.


--
-- Data for Name: menu_categories; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.menu_categories (category_id, category_name) FROM stdin;
1	Суши
2	Роллы
3	Напитки
4	Десерты
\.


--
-- Data for Name: menus; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.menus (menu_id, menu_name, menu_description, menu_price, menu_image, category_id, calories, proteins, fats, carbs) FROM stdin;
1	Филадельфия	Классические калифорнийские роллы с лососем и крабовым мясом	450.00	philadelphia.jpg	2	\N	\N	\N	\N
3	Суши Сет	Ассорти суши: лосось, тунец, краб	550.00	sushi_set.jpg	1	\N	\N	\N	\N
4	Грин Чай	Освежающий зеленый чай	150.00	greentea.jpg	3	\N	\N	\N	\N
5	Кофе	Кофе латте	180.00	coffee.jpg	3	\N	\N	\N	\N
6	Морс	Классический яблочный морс	120.00	mors.jpg	3	\N	\N	\N	\N
7	Мидии в соевом соусе	Десерт из морепродуктов	280.00	mussels.jpg	4	\N	\N	\N	\N
8	Манго Мачи	Сладкие роллы с манго	320.00	mango.jpg	2	\N	\N	\N	\N
9	Калифорния	Роллы с лососем, авокадо и икрой	420.00	california_roll.jpg	2	\N	\N	\N	\N
10	Каппа Маки	Маки с огурцом в нори	220.00	kappa_maki.jpg	2	\N	\N	\N	\N
11	Дракон Маки	Роллы с угрем, авокадо и соусом унаги	520.00	dragon_maki.jpg	2	\N	\N	\N	\N
12	Тунец Маки	Роллы с тунцом и зеленым луком	480.00	tuna_maki.jpg	2	\N	\N	\N	\N
2	Маки Креветка	Маки с креветкой и огурцом	380.00	california.jpg	2	\N	\N	\N	\N
\.


--
-- Data for Name: placed_orders; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.placed_orders (order_id, order_time, client_id, user_id, delivery_address, bonuses_earned, bonuses_spent, discount_amount, delivered, canceled, cancellation_reason) FROM stdin;
1	2026-06-21 19:32:00	10	\N	к маме твоей	46.50	0.00	0.00	0	0	\N
2	2026-06-21 19:46:00	11	\N	Солонцы	27.50	0.00	0.00	0	0	\N
3	2026-06-21 19:49:00	12	\N	Солонцы	27.50	0.00	0.00	0	0	\N
4	2026-06-21 19:51:00	13	\N	Солонцы	59.50	0.00	0.00	0	0	\N
\.


--
-- Data for Name: reservations; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.reservations (reservation_id, date_created, client_id, selected_time, nbr_guests, table_id, liberated, canceled, cancellation_reason) FROM stdin;
1	2026-06-22 20:54:27	14	2026-07-04 20:53:00	6	1	0	0	\N
2	2026-06-22 22:24:32	15	2026-06-23 22:24:00	4	1	0	0	\N
\.


--
-- Data for Name: roles; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.roles (role_id, role_key, role_name, description) FROM stdin;
1	admin	Администратор	Полный доступ: управление сайтом, пользователями и меню.
2	manager	Менеджер	Управление меню, обработка заказов, изменение изображений блюд.
3	client	Клиент	Обычный клиент сайта.
\.


--
-- Data for Name: tables; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.tables (table_id) FROM stdin;
1
\.


--
-- Data for Name: user_addresses; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.user_addresses (address_id, user_id, label, address, created_at) FROM stdin;
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.users (user_id, username, email, full_name, first_name, last_name, phone, dob, password, bonus_points, role) FROM stdin;
4	admin2	admin2@example.com	Administrator	\N	\N	\N	\N	0d107d09f5bbe40cade3de5c71e9e9b7	0.00	admin
2	client_user	client@example.com	Client User	\N	\N	\N	\N	40bd001563085fc35165329ea1ff5c5ecbdbbeef	0.00	client
3	manager_user	manager@example.com	Manager User	\N	\N	\N	\N	bdc87b9c894da5168059e00ebffb9077d815e1d	0.00	manager
1	admin_user	user_admin@gmail.com	User Admin	\N	\N	\N	\N	aaf4c61ddcc5e8a2dabede0f3b482cd9aea9434d	55.00	admin
5	admin	admin@mail.com	Admin User	\N	\N	\N	\N	240be518fabd2724ddb6f04eeb1da5967448d7e831c08c8fa822809f74c720a9	0.00	admin
\.


--
-- Data for Name: website_settings; Type: TABLE DATA; Schema: public; Owner: restaurant_website_user
--

COPY public.website_settings (option_id, option_name, option_value) FROM stdin;
1	restaurant_name	VINCENT SUSHI
2	restaurant_email	mikiyt94@gmail.com
3	admin_email	admin_email@gmail.com
4	restaurant_phonenumber	88866777555
5	restaurant_address	Москва, ул. Сушинарочная, д.1
\.


--
-- Name: clients_client_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.clients_client_id_seq', 15, true);


--
-- Name: image_gallery_image_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.image_gallery_image_id_seq', 5, true);


--
-- Name: in_order_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.in_order_id_seq', 7, true);


--
-- Name: menu_categories_category_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.menu_categories_category_id_seq', 4, true);


--
-- Name: menus_menu_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.menus_menu_id_seq', 12, true);


--
-- Name: placed_orders_order_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.placed_orders_order_id_seq', 4, true);


--
-- Name: reservations_reservation_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.reservations_reservation_id_seq', 2, true);


--
-- Name: roles_role_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.roles_role_id_seq', 3, true);


--
-- Name: tables_table_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.tables_table_id_seq', 1, false);


--
-- Name: user_addresses_address_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.user_addresses_address_id_seq', 1, false);


--
-- Name: users_user_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.users_user_id_seq', 6, true);


--
-- Name: website_settings_option_id_seq; Type: SEQUENCE SET; Schema: public; Owner: restaurant_website_user
--

SELECT pg_catalog.setval('public.website_settings_option_id_seq', 5, true);


--
-- Name: clients clients_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT clients_pkey PRIMARY KEY (client_id);


--
-- Name: image_gallery image_gallery_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.image_gallery
    ADD CONSTRAINT image_gallery_pkey PRIMARY KEY (image_id);


--
-- Name: in_order in_order_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.in_order
    ADD CONSTRAINT in_order_pkey PRIMARY KEY (id);


--
-- Name: menu_categories menu_categories_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.menu_categories
    ADD CONSTRAINT menu_categories_pkey PRIMARY KEY (category_id);


--
-- Name: menus menus_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.menus
    ADD CONSTRAINT menus_pkey PRIMARY KEY (menu_id);


--
-- Name: placed_orders placed_orders_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.placed_orders
    ADD CONSTRAINT placed_orders_pkey PRIMARY KEY (order_id);


--
-- Name: reservations reservations_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.reservations
    ADD CONSTRAINT reservations_pkey PRIMARY KEY (reservation_id);


--
-- Name: roles roles_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_pkey PRIMARY KEY (role_id);


--
-- Name: roles roles_role_key_key; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.roles
    ADD CONSTRAINT roles_role_key_key UNIQUE (role_key);


--
-- Name: tables tables_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.tables
    ADD CONSTRAINT tables_pkey PRIMARY KEY (table_id);


--
-- Name: user_addresses user_addresses_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.user_addresses
    ADD CONSTRAINT user_addresses_pkey PRIMARY KEY (address_id);


--
-- Name: users users_email_key; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (user_id);


--
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: website_settings website_settings_pkey; Type: CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.website_settings
    ADD CONSTRAINT website_settings_pkey PRIMARY KEY (option_id);


--
-- Name: clients fk_clients_user; Type: FK CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.clients
    ADD CONSTRAINT fk_clients_user FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- Name: in_order fk_in_order_menu; Type: FK CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.in_order
    ADD CONSTRAINT fk_in_order_menu FOREIGN KEY (menu_id) REFERENCES public.menus(menu_id);


--
-- Name: in_order fk_in_order_order; Type: FK CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.in_order
    ADD CONSTRAINT fk_in_order_order FOREIGN KEY (order_id) REFERENCES public.placed_orders(order_id);


--
-- Name: menus fk_menu_category; Type: FK CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.menus
    ADD CONSTRAINT fk_menu_category FOREIGN KEY (category_id) REFERENCES public.menu_categories(category_id);


--
-- Name: placed_orders fk_order_client; Type: FK CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.placed_orders
    ADD CONSTRAINT fk_order_client FOREIGN KEY (client_id) REFERENCES public.clients(client_id);


--
-- Name: placed_orders fk_orders_user; Type: FK CONSTRAINT; Schema: public; Owner: restaurant_website_user
--

ALTER TABLE ONLY public.placed_orders
    ADD CONSTRAINT fk_orders_user FOREIGN KEY (user_id) REFERENCES public.users(user_id);


--
-- Name: DEFAULT PRIVILEGES FOR SEQUENCES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON SEQUENCES TO restaurant_website_user;


--
-- Name: DEFAULT PRIVILEGES FOR TYPES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TYPES TO restaurant_website_user;


--
-- Name: DEFAULT PRIVILEGES FOR FUNCTIONS; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON FUNCTIONS TO restaurant_website_user;


--
-- Name: DEFAULT PRIVILEGES FOR TABLES; Type: DEFAULT ACL; Schema: -; Owner: postgres
--

ALTER DEFAULT PRIVILEGES FOR ROLE postgres GRANT ALL ON TABLES TO restaurant_website_user;


--
-- PostgreSQL database dump complete
--

\unrestrict bj2cetIctRQQRTsUJJSfVjf74lgZkzaqRXN3hu0LpDc7OApYtyRPcOt2DNAH7TR

