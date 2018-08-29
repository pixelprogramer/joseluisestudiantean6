export class Registro_minutas {
  constructor(public id_registro_minutas: any,
              public fecha_creacion_minuta: any,
              public fecha_hora_inicio_minuta: any,
              public fecha_hora_fin_minuta: any,
              public horas_totales: any,
              public observacion_minuta: any,
              public estado_minuta: any,
              public id_usuario_minutas_fk: any,
              public id_acciones_minutas_fk: any,
              public id_pedido_minutas_fk: any,
              public id_registro_minutas_fk: any,
              public id_inicial_registro_minutas: any,
              public descripcion_minuta: any) {
  }
}
