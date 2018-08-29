import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Rol} from "../../../models/seguridad/rol";
import {RolService} from "../../../services/administradorSistemas/rol.service";

@Component({
  selector: 'app-crear-permisos',
  templateUrl: './crear-permisos.component.html',
  styleUrls: ['./crear-permisos.component.scss'],
  providers: [ElementsService, RolService]
})
export class CrearPermisosComponent implements OnInit {
  public token: any;
  public objRol: Rol;
  public listRol: Array<Rol>;
  position = "top-right";
  constructor(private _ElementService: ElementsService,
              private _RolService: RolService) {
    this.token = localStorage.getItem('token');
    this.objRol = new Rol('', '', '000', '', '');

  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearPermisosComponent');
    this._ElementService.pi_poBontonDesabilitar('#btnActualizarRol');
    $("#loaderTablaPermisos").hide();
    this.listarRol();
  }

  crearRol() {
    this._RolService.crearRol(this.token, this.objRol).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listarRol();
          this.limpiarRol();
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  listarRol() {
    $("#loaderTablaPermisos").show();
    this._RolService.listarRol(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listRol = respuesta.data;
          $("#loaderTablaPermisos").hide();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaPermisos").hide();
        }
      }, error2 => {

      }
    )
  }

  actualizarRol() {
    this._ElementService.pi_poBontonDesabilitar('#btnActualizarRol');
    if (this.objRol.descripcion_rol.trim() == '' || this.objRol.estado_rol == '000') {
      this._ElementService.pi_poAlertaError('Lo sentimos, El campo descripcion y el estado son requeridos','LTE-000');
      this._ElementService.pi_poBotonHabilitar('#btnActualizarRol');
    }else
    {
      this._RolService.actualizarRol(this.token, this.objRol).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            this._ElementService.pi_poBontonDesabilitar('#btnActualizarRol');
            this._ElementService.pi_poBotonHabilitar('#btnCrearRol');
            this.listarRol();
            this.limpiarRol();
            this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          }
        }, error2 => {

        }
      )
    }
  }

  seleccionarRol(rol) {
    this.objRol = rol;
    this._ElementService.pi_poBontonDesabilitar('#btnCrearRol');
    this._ElementService.pi_poBotonHabilitar('#btnActualizarRol');
    this._ElementService.pi_poAlertaWarning('Se slecciono el rol con codigo: '+rol.id_rol+', descripcion: '+rol.descripcion_rol, 'LTE-000');
  }

  limpiarRol() {
    this.objRol = new Rol('', '', '000', '', '');
  }
}
