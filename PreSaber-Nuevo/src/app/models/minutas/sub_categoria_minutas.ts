export class Sub_categoria_minutas{
  constructor(
    public id_sub_menu_minutas: any,
    public descripcion_sub_categoria: any,
    public fecha_creacion_sub_categoria_minutas: any,
    public fecha_actualizacion_sub_categoria_minutas: any,
    public id_accion_minutas_fk: any,
    public id_categoria_sub_menu_fk: any,
    public id_usuario_sub_categoria_minutas_fk: any
  ){}
}
