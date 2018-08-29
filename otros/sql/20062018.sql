-- Database generated with pgModeler (PostgreSQL Database Modeler).
-- pgModeler  version: 0.8.2
-- PostgreSQL version: 9.5
-- Project Site: pgmodeler.com.br
-- Model Author: ---


-- Database creation must be done outside an multicommand file.
-- These commands were put in this file only for convenience.
-- -- object: new_database | type: DATABASE --
-- -- DROP DATABASE IF EXISTS new_database;
-- CREATE DATABASE new_database
-- ;
-- -- ddl-end --
-- 

-- object: seguridad | type: SCHEMA --
-- DROP SCHEMA IF EXISTS seguridad CASCADE;
CREATE SCHEMA seguridad;
-- ddl-end --
ALTER SCHEMA seguridad OWNER TO postgres;
-- ddl-end --

-- object: configuracion | type: SCHEMA --
-- DROP SCHEMA IF EXISTS configuracion CASCADE;
CREATE SCHEMA configuracion;
-- ddl-end --
ALTER SCHEMA configuracion OWNER TO postgres;
-- ddl-end --

SET search_path TO pg_catalog,public,seguridad,configuracion;
-- ddl-end --

-- object: seguridad.usuario | type: TABLE --
-- DROP TABLE IF EXISTS seguridad.usuario CASCADE;
CREATE TABLE seguridad.usuario(
	id_usuario serial NOT NULL,
	documento_usuario varchar(250),
	nombre_usuario varchar(250),
	apellido_usuario varchar(250),
	telefono_usuario varchar(250),
	correo_usuario varchar(250),
	id_rol_fk_usuario integer,
	estado_usuario varchar(100),
	contrasena_usuario varchar(250),
	fecha_creacion_usuario timestamp,
	fecha_actualizacion_usuario timestamp,
	fecha_ultima_ingreso_usuario timestamp,
	CONSTRAINT pk_usuario_id PRIMARY KEY (id_usuario)

);
-- ddl-end --
ALTER TABLE seguridad.usuario OWNER TO postgres;
-- ddl-end --

-- object: seguridad.rol | type: TABLE --
-- DROP TABLE IF EXISTS seguridad.rol CASCADE;
CREATE TABLE seguridad.rol(
	id_rol serial NOT NULL,
	descripcion_rol varchar(150),
	estado_rol varchar(100),
	fecha_creacion_rol timestamp,
	fecha_actualizacion_rol timestamp,
	CONSTRAINT pk_rol_id PRIMARY KEY (id_rol)

);
-- ddl-end --
ALTER TABLE seguridad.rol OWNER TO postgres;
-- ddl-end --

-- object: seguridad.cabezera | type: TABLE --
-- DROP TABLE IF EXISTS seguridad.cabezera CASCADE;
CREATE TABLE seguridad.cabezera(
	id_cabezera serial NOT NULL,
	descripcion_cabezera varchar(250),
	estado_cabezera varchar(100),
	fecha_creacion_cabezera timestamp,
	fecha_actualizacion_cabezera timestamp,
	id_rol_fk_cabezera integer,
	CONSTRAINT pk_cabezera_id PRIMARY KEY (id_cabezera)

);
-- ddl-end --
ALTER TABLE seguridad.cabezera OWNER TO postgres;
-- ddl-end --

-- object: seguridad.submenu | type: TABLE --
-- DROP TABLE IF EXISTS seguridad.submenu CASCADE;
CREATE TABLE seguridad.submenu(
	id_submenu serial NOT NULL,
	id_cabezera_fk_submenu integer,
	id_menu_fk_submenu integer,
	estado_submenu varchar(250),
	CONSTRAINT pk_submenu PRIMARY KEY (id_submenu)

);
-- ddl-end --
ALTER TABLE seguridad.submenu OWNER TO postgres;
-- ddl-end --

-- object: seguridad.menu | type: TABLE --
-- DROP TABLE IF EXISTS seguridad.menu CASCADE;
CREATE TABLE seguridad.menu(
	id_menu serial NOT NULL,
	descripcion_menu varchar(250),
	nombre_componente_menu varchar(250),
	ruta_menu varchar(250),
	estado_menu varchar(100),
	fecha_creacion_menu timestamp,
	fecha_actualizacion_menu timestamp,
	CONSTRAINT pk_id_menu PRIMARY KEY (id_menu)

);
-- ddl-end --
ALTER TABLE seguridad.menu OWNER TO postgres;
-- ddl-end --

-- object: configuracion.log_lte | type: TABLE --
-- DROP TABLE IF EXISTS configuracion.log_lte CASCADE;
CREATE TABLE configuracion.log_lte(
	id_log serial NOT NULL,
	fecha_creacion_log timestamp,
	descripcion_log text,
	id_accion_log varchar(250),
	id_usuario_fk_log integer,
	CONSTRAINT pk_id_log PRIMARY KEY (id_log)

);
-- ddl-end --
ALTER TABLE configuracion.log_lte OWNER TO postgres;
-- ddl-end --

-- object: fk_usuario_rol_id | type: CONSTRAINT --
-- ALTER TABLE seguridad.usuario DROP CONSTRAINT IF EXISTS fk_usuario_rol_id CASCADE;
ALTER TABLE seguridad.usuario ADD CONSTRAINT fk_usuario_rol_id FOREIGN KEY (id_rol_fk_usuario)
REFERENCES seguridad.rol (id_rol) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_cabezera_rol_id | type: CONSTRAINT --
-- ALTER TABLE seguridad.cabezera DROP CONSTRAINT IF EXISTS fk_cabezera_rol_id CASCADE;
ALTER TABLE seguridad.cabezera ADD CONSTRAINT fk_cabezera_rol_id FOREIGN KEY (id_rol_fk_cabezera)
REFERENCES seguridad.rol (id_rol) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_submenu_menu_id | type: CONSTRAINT --
-- ALTER TABLE seguridad.submenu DROP CONSTRAINT IF EXISTS fk_submenu_menu_id CASCADE;
ALTER TABLE seguridad.submenu ADD CONSTRAINT fk_submenu_menu_id FOREIGN KEY (id_menu_fk_submenu)
REFERENCES seguridad.menu (id_menu) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_cabezera_submenu_id | type: CONSTRAINT --
-- ALTER TABLE seguridad.submenu DROP CONSTRAINT IF EXISTS fk_cabezera_submenu_id CASCADE;
ALTER TABLE seguridad.submenu ADD CONSTRAINT fk_cabezera_submenu_id FOREIGN KEY (id_cabezera_fk_submenu)
REFERENCES seguridad.cabezera (id_cabezera) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_usuario_log_id | type: CONSTRAINT --
-- ALTER TABLE configuracion.log_lte DROP CONSTRAINT IF EXISTS fk_usuario_log_id CASCADE;
ALTER TABLE configuracion.log_lte ADD CONSTRAINT fk_usuario_log_id FOREIGN KEY (id_usuario_fk_log)
REFERENCES seguridad.usuario (id_usuario) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --


