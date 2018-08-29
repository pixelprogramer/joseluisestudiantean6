export class Usuario{
  constructor(
    public id_usuario: any,
    public documento_usuario: any,
    public nombre_usuario: any,
    public apellido_usuario: any,
    public telefono_usuario: any,
    public correo_usuario: any,
    public id_rol_fk_usuario: any,
    public estado_usuario: any,
    public contrasena_usuario: any,
    public fecha_creacion_usuario: any,
    public fecha_actualizacion_usuario: any,
    public fecha_ultima_ingreso_usuario: any,
  ){}
}
