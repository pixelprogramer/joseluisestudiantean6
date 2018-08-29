import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {RolService} from "../../../services/administradorSistemas/rol.service";
import {Rol} from "../../../models/seguridad/rol";
import {MenuService} from "../../../services/administradorSistemas/menu.service";
import {Menu} from "../../../models/seguridad/menu";
import {Submenu} from "../../../models/seguridad/submenu";
import swal from "sweetalert2";
import {Cabezera} from "../../../models/seguridad/cabezera";

@Component({
  selector: 'app-administrar-menu',
  templateUrl: './administrar-menu.component.html',
  styleUrls: ['./administrar-menu.component.scss'],
  providers: [ElementsService, RolService, MenuService]
})
export class AdministrarMenuComponent implements OnInit {
  public token: any;
  public listRol: Array<Rol>;
  public listMenuTodo: Array<any>;
  public objRol: Rol;
  public estadoFlechas: any;
  public listMenu: Array<Menu>;
  public seleccionCabezera: any;
  public objSubMenu: Submenu;
  public nombreCabezera: any;
  public nuevaCabezera: any;
  public objCabezera: Cabezera;
  position = "top-right";

  constructor(private _ElementService: ElementsService,
              private _RolService: RolService,
              private _MenuService: MenuService) {
    this.objRol = new Rol('', '', '000', '', '');
    this.objSubMenu = new Submenu('', '', '', '000', '');
    this.objCabezera = new Cabezera('', '', '000', '', '', '', '');
    this.token = localStorage.getItem('token');
    this.estadoFlechas = false;
    this.nuevaCabezera = false;
    this.seleccionCabezera = null;
    this.nombreCabezera = '';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('AdministrarMenuComponent');
    this.listarRoles();
    this.listarItemsMenu();
  }

  listarRoles() {
    this._RolService.listarRol(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listRol = respuesta.data;
          } else {
            this.listRol = [];
          }
        } else {

        }
      }, error2 => {

      }
    )
  }

  motrarMenu() {
    this.listarMenu();
    this.seleccionCabezera = null;
    this.nombreCabezera = '';

  }

  listarMenu() {
    if (this.objRol.id_rol != '' || this.objRol.id_rol != '000') {
      this._MenuService.listarMenuTodo(this.objRol).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listMenuTodo = respuesta.data;
            } else {
              this.listMenuTodo = [];
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            this.listMenuTodo = [];
          }
        }
      )
    }

  }

  cambiarPrioridadCabezera(tipo, objCabezera) {

    var nuevaPrioridad;
    if (tipo == 'arriba') {
      nuevaPrioridad = objCabezera.prioridad_cabezera - 1;
      if (nuevaPrioridad > 0) {
        for (var i = 0; i < this.listMenuTodo.length; i++) {
          if (this.listMenuTodo[i].prioridad_cabezera == nuevaPrioridad) {
            this.listMenuTodo[i].prioridad_cabezera = objCabezera.prioridad_cabezera;
          }
        }
        for (var i = 0; i < this.listMenuTodo.length; i++) {
          if (this.listMenuTodo[i].id_cabezera == objCabezera.id_cabezera) {
            this.listMenuTodo[i].prioridad_cabezera = nuevaPrioridad;
          }
        }
        this.listMenuTodo.sort(function (a, b) {
          return (a.prioridad_cabezera - b.prioridad_cabezera)
        })
        this.actualizarPrioridad();
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, este item ya alcanzo su maxima prioridad', 'LTE-000');
      }

    } else if (tipo == 'abajo') {
      nuevaPrioridad = (parseInt(objCabezera.prioridad_cabezera) + parseInt('1'));
      if (nuevaPrioridad <= this.listMenuTodo.length) {
        for (var i = 0; i < this.listMenuTodo.length; i++) {
          if (this.listMenuTodo[i].prioridad_cabezera == nuevaPrioridad) {
            this.listMenuTodo[i].prioridad_cabezera = objCabezera.prioridad_cabezera;
          }
        }
        for (var i = 0; i < this.listMenuTodo.length; i++) {
          if (this.listMenuTodo[i].id_cabezera == objCabezera.id_cabezera) {
            this.listMenuTodo[i].prioridad_cabezera = nuevaPrioridad;
          }
        }
        this.listMenuTodo.sort(function (a, b) {
          return (a.prioridad_cabezera - b.prioridad_cabezera)
        })
        this.actualizarPrioridad();
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, este item ya alcanzo su minima prioridad', 'LTE-000');
      }
    }
  }

  cambiarPrioridadSubMenu(tipo, objSubmenu, cabezera) {

    var id_cabezera;
    for (let i = 0; i < this.listMenuTodo.length; i++) {
      if (this.listMenuTodo[i].id_cabezera == cabezera.id_cabezera) {
        id_cabezera = i
      }
    }
    var nuevaPrioridad;
    if (tipo == 'arriba') {
      nuevaPrioridad = objSubmenu.prioridad_submenu - 1;
      if (nuevaPrioridad > 0) {
        for (var i = 0; i < this.listMenuTodo[id_cabezera].subMenus.length; i++) {
          if (this.listMenuTodo[id_cabezera].subMenus[i].prioridad_submenu == nuevaPrioridad) {
            this.listMenuTodo[id_cabezera].subMenus[i].prioridad_submenu = objSubmenu.prioridad_submenu;
          }
        }
        for (var i = 0; i < this.listMenuTodo[id_cabezera].subMenus.length; i++) {
          if (this.listMenuTodo[id_cabezera].subMenus[i].id_menu == objSubmenu.id_menu) {
            this.listMenuTodo[id_cabezera].subMenus[i].prioridad_submenu = nuevaPrioridad;
          }
        }
        this.listMenuTodo[id_cabezera].subMenus.sort(function (a, b) {
          return (a.prioridad_submenu - b.prioridad_submenu)
        })
        this.actualizarPrioridad();
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, este item ya alcanzo su maxima prioridad', 'LTE-000');
      }

    } else if (tipo == 'abajo') {
      nuevaPrioridad = (parseInt(objSubmenu.prioridad_submenu) + parseInt('1'));
      if (nuevaPrioridad <= this.listMenuTodo[id_cabezera].subMenus.length) {
        for (var i = 0; i < this.listMenuTodo[id_cabezera].subMenus.length; i++) {
          if (this.listMenuTodo[id_cabezera].subMenus[i].prioridad_submenu == nuevaPrioridad) {
            this.listMenuTodo[id_cabezera].subMenus[i].prioridad_submenu = objSubmenu.prioridad_submenu;
          }
        }
        for (var i = 0; i < this.listMenuTodo[id_cabezera].subMenus.length; i++) {
          if (this.listMenuTodo[id_cabezera].subMenus[i].id_menu == objSubmenu.id_menu) {
            this.listMenuTodo[id_cabezera].subMenus[i].prioridad_submenu = nuevaPrioridad;
          }
        }
        this.listMenuTodo[id_cabezera].subMenus.sort(function (a, b) {
          return (a.prioridad_submenu - b.prioridad_submenu)
        })
        this.actualizarPrioridad();
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, este item ya alcanzo su minima prioridad', 'LTE-000');
      }
    }
  }

  actualizarPrioridad() {
    this._MenuService.actualizarMenuPrioridad(this.token, this.listMenuTodo).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
        } else {

        }
      }, error2 => {

      }
    )
  }

  listarItemsMenu() {
    $("#loaderTablaMenus").show();
    this._MenuService.listarMenu(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listMenu = respuesta.data;
          $("#loaderTablaMenus").hide();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaMenus").hide();
        }
      }, error2 => {

      }
    )
  }

  seleccionarEncabezado(encabezado) {
    this.seleccionCabezera = encabezado;
    this.nombreCabezera = this.seleccionCabezera.descripcion_cabezera;
  }

  seleccionSubMenu(sub) {
    if (this.seleccionCabezera != null) {
      this.objSubMenu.id_cabezera_fk_submenu = this.seleccionCabezera.id_cabezera;
      this.objSubMenu.id_menu_fk_submenu = sub.id_menu;
      this.objSubMenu.estado_submenu = 'ACTIVO';
      if (this.validarNuevoSub(this.objSubMenu) == false) {
        this._MenuService.nuevoSubMenu(this.token, this.objSubMenu).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarMenu();
            }
          }, error2 => {

          }
        )
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, este seubMenu ya esta agregado en esta cabezera');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se ha seleccionado una cabezera');
    }
  }

  validarNuevoSub(objSub) {
    for (var i = 0; i < this.listMenuTodo.length; i++) {
      if (this.listMenuTodo[i].id_cabezera == objSub.id_cabezera_fk_submenu) {
        for (var j = 0; j < this.listMenuTodo[i].subMenus.length; j++) {
          if (this.listMenuTodo[i].subMenus[j].id_menu == objSub.id_menu_fk_submenu) {
            return true;
          }
        }
      }
    }
    return false;
  }

  eliminarSubMenuMenu(subMenu) {
    swal({
      title: 'LTE-000',
      text: 'Esta seguro de eliminar este submenu',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar'
    }).then((result) => {
      $("#loaderUsuario").show();
      if (result.value) {
        console.log(subMenu);
        this._MenuService.eliminarSubMenuMneu(this.token, subMenu).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarMenu();
            } else {

            }
          }, error2 => {

          }
        )
      }
    });
  }

  crearCabezera() {
    if (this.objRol.id_rol != '') {
      if (this.objCabezera.descripcion_cabezera != '') {
        this.objCabezera.id_rol_fk_cabezera = this.objRol.id_rol;
        this.objCabezera.estado_cabezera = 'ACTIVO';
        this._MenuService.crearCabezera(this.token, this.objCabezera).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.objCabezera.descripcion_cabezera = '';
              this.listarMenu();
            } else {

            }
          }, error2 => {

          }
        )
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, el nombre de la cabezera es requerido', 'LTE-000');
      }

    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se ha seleccionado un permiso');
    }
  }
}
