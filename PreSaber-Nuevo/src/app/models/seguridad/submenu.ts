export class Submenu{
  constructor(
    public id_submenu: any,
    public id_cabezera_fk_submenu: any,
    public id_menu_fk_submenu: any,
    public estado_submenu: any,
    public prioridad_submenu: any
  ){}
}
