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

-- object: minutas | type: SCHEMA --
-- DROP SCHEMA IF EXISTS minutas CASCADE;
CREATE SCHEMA minutas;
-- ddl-end --
ALTER SCHEMA minutas OWNER TO postgres;
-- ddl-end --

-- object: mantenimiento | type: SCHEMA --
-- DROP SCHEMA IF EXISTS mantenimiento CASCADE;
CREATE SCHEMA mantenimiento;
-- ddl-end --
ALTER SCHEMA mantenimiento OWNER TO postgres;
-- ddl-end --

SET search_path TO pg_catalog,public,seguridad,configuracion,minutas,mantenimiento;
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
	prioridad_cabezera integer,
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
	prioridad_submenu integer,
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
	icono varchar(250),
	pagina_defecto varchar(100),
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

-- object: minutas.registro_minutas | type: TABLE --
-- DROP TABLE IF EXISTS minutas.registro_minutas CASCADE;
CREATE TABLE minutas.registro_minutas(
	id_registro_minutas serial NOT NULL,
	fecha_creacion_minuta timestamp,
	fecha_hora_inicio_minuta timestamp,
	fecha_hora_fin_minuta timestamp,
	horas_totales varchar(250),
	observacion_minuta text,
	estado_minuta varchar(150),
	id_usuario_minutas_fk integer,
	id_acciones_minutas_fk integer,
	id_pedido_minutas_fk integer,
	id_registro_minutas_fk integer,
	CONSTRAINT pk_registro_minutas_fk PRIMARY KEY (id_registro_minutas)

);
-- ddl-end --
ALTER TABLE minutas.registro_minutas OWNER TO postgres;
-- ddl-end --

-- object: mantenimiento.reporte_danos | type: TABLE --
-- DROP TABLE IF EXISTS mantenimiento.reporte_danos CASCADE;
CREATE TABLE mantenimiento.reporte_danos(
	id_reporte_dano serial NOT NULL,
	descripcion_reporte_danos text,
	fecha_creacion_reporte_danos timestamp,
	fecha_actualizacion_reporte_danos timestamp,
	id_tipo_dano_fk integer,
	id_tipo_elemento_fk integer,
	id_usuario_reporte_danos_fk integer,
	CONSTRAINT pk_reporte_danos_id PRIMARY KEY (id_reporte_dano)

);
-- ddl-end --
ALTER TABLE mantenimiento.reporte_danos OWNER TO postgres;
-- ddl-end --

-- object: mantenimiento.tipo_dano | type: TABLE --
-- DROP TABLE IF EXISTS mantenimiento.tipo_dano CASCADE;
CREATE TABLE mantenimiento.tipo_dano(
	id_tipo_dano serial NOT NULL,
	descripcion varchar(250),
	CONSTRAINT pk_tipo_dano_id PRIMARY KEY (id_tipo_dano)

);
-- ddl-end --
ALTER TABLE mantenimiento.tipo_dano OWNER TO postgres;
-- ddl-end --

-- object: mantenimiento.elemento | type: TABLE --
-- DROP TABLE IF EXISTS mantenimiento.elemento CASCADE;
CREATE TABLE mantenimiento.elemento(
	id_elemento serial NOT NULL,
	descripcion varchar(250),
	id_tipo_elemento_fk integer,
	CONSTRAINT pk_elemento_id PRIMARY KEY (id_elemento)

);
-- ddl-end --
ALTER TABLE mantenimiento.elemento OWNER TO postgres;
-- ddl-end --

-- object: mantenimiento.tipo_elemento | type: TABLE --
-- DROP TABLE IF EXISTS mantenimiento.tipo_elemento CASCADE;
CREATE TABLE mantenimiento.tipo_elemento(
	id_tipo_elemento serial NOT NULL,
	descripcion varchar(250),
	CONSTRAINT pk_tipo_elemento_id PRIMARY KEY (id_tipo_elemento)

);
-- ddl-end --
ALTER TABLE mantenimiento.tipo_elemento OWNER TO postgres;
-- ddl-end --

-- object: minutas.categoria_minutas | type: TABLE --
-- DROP TABLE IF EXISTS minutas.categoria_minutas CASCADE;
CREATE TABLE minutas.categoria_minutas(
	id_categoria_minutas serial NOT NULL,
	id_tipo_pedido_minutas_fk integer,
	descripcion_categoria_minutas varchar(250),
	CONSTRAINT pk_categoria_minutas_id PRIMARY KEY (id_categoria_minutas)

);
-- ddl-end --
ALTER TABLE minutas.categoria_minutas OWNER TO postgres;
-- ddl-end --

-- object: minutas.sub_categoria_minutas | type: TABLE --
-- DROP TABLE IF EXISTS minutas.sub_categoria_minutas CASCADE;
CREATE TABLE minutas.sub_categoria_minutas(
	id_sub_menu_minutas serial NOT NULL,
	descripcion_sub_categoria varchar(250),
	fecha_creacion_sub_categoria_minutas timestamp,
	fecha_actualizacion_sub_categoria_minutas timestamp,
	id_accion_minutas_fk integer,
	id_categoria_sub_menu_fk integer,
	id_usuario_sub_categoria_minutas_fk integer,
	CONSTRAINT pk_sub_menu_minutas_id PRIMARY KEY (id_sub_menu_minutas)

);
-- ddl-end --
ALTER TABLE minutas.sub_categoria_minutas OWNER TO postgres;
-- ddl-end --

-- object: minutas.acciones_minutas | type: TABLE --
-- DROP TABLE IF EXISTS minutas.acciones_minutas CASCADE;
CREATE TABLE minutas.acciones_minutas(
	id_acciones_minutas serial NOT NULL,
	descripcion_acciones_minutas varchar(250),
	seleccion_pedido_accion varchar(100),
	fecha_creacion_acciones_minutas timestamp,
	fecha_actualizacion_acciones_minutas timestamp,
	estado_acciones_minutas varchar(100),
	id_usuario_acciones_minutas_fk integer,
	CONSTRAINT pk_acciones_minutas_id PRIMARY KEY (id_acciones_minutas)

);
-- ddl-end --
ALTER TABLE minutas.acciones_minutas OWNER TO postgres;
-- ddl-end --

-- object: minutas.pedidos_minutas | type: TABLE --
-- DROP TABLE IF EXISTS minutas.pedidos_minutas CASCADE;
CREATE TABLE minutas.pedidos_minutas(
	id_pedido_minutas serial NOT NULL,
	codigo_pedido_minutas varchar(150),
	descripcion_pedido_minutas varchar(250),
	cantidad_calificacion_pedido_minutas varchar(250),
	cantidad_premarcado_pedido_minutas varchar(250),
	cantidad_logistica_pedido_minutas varchar(250),
	CONSTRAINT pk_pedido_minutas_id PRIMARY KEY (id_pedido_minutas)

);
-- ddl-end --
ALTER TABLE minutas.pedidos_minutas OWNER TO postgres;
-- ddl-end --

-- object: minutas.detalle_pedido_usuario | type: TABLE --
-- DROP TABLE IF EXISTS minutas.detalle_pedido_usuario CASCADE;
CREATE TABLE minutas.detalle_pedido_usuario(
	id_detalle_pedido_usuario serial NOT NULL,
	json_detalle_pedido text,
	id_pedido_minutas_fk integer,
	CONSTRAINT pk_detalle_pedido_usuario_id PRIMARY KEY (id_detalle_pedido_usuario)

);
-- ddl-end --
ALTER TABLE minutas.detalle_pedido_usuario OWNER TO postgres;
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

-- object: fk_minutas_usuario_id | type: CONSTRAINT --
-- ALTER TABLE minutas.registro_minutas DROP CONSTRAINT IF EXISTS fk_minutas_usuario_id CASCADE;
ALTER TABLE minutas.registro_minutas ADD CONSTRAINT fk_minutas_usuario_id FOREIGN KEY (id_usuario_minutas_fk)
REFERENCES seguridad.usuario (id_usuario) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_registro_minutas_acciones_minutas_id | type: CONSTRAINT --
-- ALTER TABLE minutas.registro_minutas DROP CONSTRAINT IF EXISTS fk_registro_minutas_acciones_minutas_id CASCADE;
ALTER TABLE minutas.registro_minutas ADD CONSTRAINT fk_registro_minutas_acciones_minutas_id FOREIGN KEY (id_acciones_minutas_fk)
REFERENCES minutas.acciones_minutas (id_acciones_minutas) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_registro_minutas_pedidos_minutas_id | type: CONSTRAINT --
-- ALTER TABLE minutas.registro_minutas DROP CONSTRAINT IF EXISTS fk_registro_minutas_pedidos_minutas_id CASCADE;
ALTER TABLE minutas.registro_minutas ADD CONSTRAINT fk_registro_minutas_pedidos_minutas_id FOREIGN KEY (id_pedido_minutas_fk)
REFERENCES minutas.pedidos_minutas (id_pedido_minutas) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_registro_minutas_id | type: CONSTRAINT --
-- ALTER TABLE minutas.registro_minutas DROP CONSTRAINT IF EXISTS fk_registro_minutas_id CASCADE;
ALTER TABLE minutas.registro_minutas ADD CONSTRAINT fk_registro_minutas_id FOREIGN KEY (id_registro_minutas_fk)
REFERENCES minutas.registro_minutas (id_registro_minutas) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_reporte_dano_tipo_dano | type: CONSTRAINT --
-- ALTER TABLE mantenimiento.reporte_danos DROP CONSTRAINT IF EXISTS fk_reporte_dano_tipo_dano CASCADE;
ALTER TABLE mantenimiento.reporte_danos ADD CONSTRAINT fk_reporte_dano_tipo_dano FOREIGN KEY (id_tipo_dano_fk)
REFERENCES mantenimiento.tipo_dano (id_tipo_dano) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_reporte_dano_elemento_id | type: CONSTRAINT --
-- ALTER TABLE mantenimiento.reporte_danos DROP CONSTRAINT IF EXISTS fk_reporte_dano_elemento_id CASCADE;
ALTER TABLE mantenimiento.reporte_danos ADD CONSTRAINT fk_reporte_dano_elemento_id FOREIGN KEY (id_tipo_elemento_fk)
REFERENCES mantenimiento.elemento (id_elemento) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: pk_usuario_reporte_danos_fk | type: CONSTRAINT --
-- ALTER TABLE mantenimiento.reporte_danos DROP CONSTRAINT IF EXISTS pk_usuario_reporte_danos_fk CASCADE;
ALTER TABLE mantenimiento.reporte_danos ADD CONSTRAINT pk_usuario_reporte_danos_fk FOREIGN KEY (id_usuario_reporte_danos_fk)
REFERENCES seguridad.usuario (id_usuario) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_elemento_tipo_elemento | type: CONSTRAINT --
-- ALTER TABLE mantenimiento.elemento DROP CONSTRAINT IF EXISTS fk_elemento_tipo_elemento CASCADE;
ALTER TABLE mantenimiento.elemento ADD CONSTRAINT fk_elemento_tipo_elemento FOREIGN KEY (id_tipo_elemento_fk)
REFERENCES mantenimiento.tipo_elemento (id_tipo_elemento) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_sub_categoria_categoria_id | type: CONSTRAINT --
-- ALTER TABLE minutas.sub_categoria_minutas DROP CONSTRAINT IF EXISTS fk_sub_categoria_categoria_id CASCADE;
ALTER TABLE minutas.sub_categoria_minutas ADD CONSTRAINT fk_sub_categoria_categoria_id FOREIGN KEY (id_categoria_sub_menu_fk)
REFERENCES minutas.categoria_minutas (id_categoria_minutas) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_sub_categoria_acciones_id | type: CONSTRAINT --
-- ALTER TABLE minutas.sub_categoria_minutas DROP CONSTRAINT IF EXISTS fk_sub_categoria_acciones_id CASCADE;
ALTER TABLE minutas.sub_categoria_minutas ADD CONSTRAINT fk_sub_categoria_acciones_id FOREIGN KEY (id_accion_minutas_fk)
REFERENCES minutas.acciones_minutas (id_acciones_minutas) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_sub_categoria_usuario_id | type: CONSTRAINT --
-- ALTER TABLE minutas.sub_categoria_minutas DROP CONSTRAINT IF EXISTS fk_sub_categoria_usuario_id CASCADE;
ALTER TABLE minutas.sub_categoria_minutas ADD CONSTRAINT fk_sub_categoria_usuario_id FOREIGN KEY (id_usuario_sub_categoria_minutas_fk)
REFERENCES seguridad.usuario (id_usuario) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_acciones_minutas_usuarios_id | type: CONSTRAINT --
-- ALTER TABLE minutas.acciones_minutas DROP CONSTRAINT IF EXISTS fk_acciones_minutas_usuarios_id CASCADE;
ALTER TABLE minutas.acciones_minutas ADD CONSTRAINT fk_acciones_minutas_usuarios_id FOREIGN KEY (id_usuario_acciones_minutas_fk)
REFERENCES seguridad.usuario (id_usuario) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --

-- object: fk_detalle_pedido_minutas_pedidos_minutas_id | type: CONSTRAINT --
-- ALTER TABLE minutas.detalle_pedido_usuario DROP CONSTRAINT IF EXISTS fk_detalle_pedido_minutas_pedidos_minutas_id CASCADE;
ALTER TABLE minutas.detalle_pedido_usuario ADD CONSTRAINT fk_detalle_pedido_minutas_pedidos_minutas_id FOREIGN KEY (id_pedido_minutas_fk)
REFERENCES minutas.pedidos_minutas (id_pedido_minutas) MATCH FULL
ON DELETE RESTRICT ON UPDATE CASCADE;
-- ddl-end --


