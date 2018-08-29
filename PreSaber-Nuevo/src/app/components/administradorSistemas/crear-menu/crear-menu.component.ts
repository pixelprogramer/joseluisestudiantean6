import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Menu} from "../../../models/seguridad/menu";
import {MenuService} from "../../../services/administradorSistemas/menu.service";

@Component({
  selector: 'app-crear-menu',
  templateUrl: './crear-menu.component.html',
  styleUrls: ['./crear-menu.component.scss'],
  providers:[ElementsService,MenuService]
})
export class CrearMenuComponent implements OnInit {
  public objMenu: Menu;
  public token: any;
  public listMenu: Array<Menu>;
  position = "top-right";
  constructor(private _Elementservice: ElementsService, private _MenuService: MenuService) {
    this.objMenu = new Menu('','','','',
      '000','','','','');
    this.token = localStorage.getItem('token');
    this._Elementservice.pi_poBontonDesabilitar('#btnActualizarMenu');
  }

  ngOnInit() {
    this._Elementservice.pi_poValidarUsuario('CrearMenuComponent');
    this.listarMenu();
    $("#seccionIconos").hide();
    $("#loaderTablaMenus").hide();
  }
  mostrarIconos()
  {
    $("#seccionPrincipal").toggle(100);
    $("#seccionIconos").toggle(200);
  }

  seleccionarIcono(event)
  {
    this.objMenu.icono = event;
    $("#seccionIconos").toggle(100);
    $("#seccionPrincipal").toggle(200);
  }
  crearMenu()
  {
    this._MenuService.crearMenu(this.token,this.objMenu).subscribe(
      respuesta =>{
        this._Elementservice.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success')
        {
          this.listarMenu();
          this.limpiarCampos();
          this._Elementservice.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
        }else
        {
          this._Elementservice.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
        }
      },error2 => {

      }
    )
  }
  limpiarCampos()
  {
    this.objMenu = new Menu('','','','',
      '000','','','','');
  }
  listarMenu()
  {
    $("#loaderTablaMenus").show();
    this._MenuService.listarMenu(this.token).subscribe(
      respuesta=>{
        this._Elementservice.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success')
        {
          this.listMenu = respuesta.data;
          $("#loaderTablaMenus").hide();
        }else
        {
          this._Elementservice.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
          $("#loaderTablaMenus").hide();
        }
      },error2 => {

      }
    )
  }
  actualizarMenu()
  {
    if (this.objMenu.estado_menu == '000' || this.objMenu.descripcion_menu.trim() == '')
    this._Elementservice.pi_poBontonDesabilitar('#btnActualizarMenu');
    this._MenuService.actualizarMenu(this.token,this.objMenu).subscribe(
      respuesta=>{
        this._Elementservice.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success')
        {
          this._Elementservice.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this._Elementservice.pi_poBontonDesabilitar('#btnActualizarMenu');
          this._Elementservice.pi_poBotonHabilitar('#btnCrearMenu');
        }else
        {
          this._Elementservice.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
          this._Elementservice.pi_poBotonHabilitar('#btnActualizarMenu');
        }
      },error2 => {

      }
    )
  }
  seleccionarMenu(Menu)
  {
    this.objMenu = Menu;
    this._Elementservice.pi_poBotonHabilitar('#btnActualizarMenu');
    this._Elementservice.pi_poBontonDesabilitar('#btnCrearMenu');
  }
}
