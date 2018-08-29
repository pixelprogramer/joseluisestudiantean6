export class Registro_gasto_usuario{
  constructor(
    public id_registro_usuario: any,
    public cantidad: any,
    public fecha_creacion_registro_gasto: any,
    public codigo_pedido: any,
    public id_usuario_registro_gasto_fk: any,
    public id_alamacen_calificacion_fk: any,
  ){}
}
