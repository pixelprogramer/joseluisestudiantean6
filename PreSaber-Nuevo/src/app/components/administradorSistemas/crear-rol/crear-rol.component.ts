import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Rol} from "../../../models/seguridad/rol";
import {UsuarioService} from "../../../services/usuario.service";

@Component({
  selector: 'app-crear-rol',
  templateUrl: './crear-rol.component.html',
  styleUrls: ['./crear-rol.component.scss'],
  providers: [ElementsService, UsuarioService]
})
export class CrearRolComponent implements OnInit {
  public objRol: Rol;
  public listRol: Array<Rol>;
  public token: any;
  position = "top-right";
  constructor(private _ElementService: ElementsService,
              private _UsuarioService: UsuarioService) {
    this.objRol = new Rol('000', '',
      '000', '', '');
    this.token = localStorage.getItem('token');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearRolComponent');
    this.listarRoles();
    $("#loaderTablaRoles").hide();
  }

  listarRoles() {
    $("#loaderTablaRoles").show();
    this._UsuarioService.listarRoles(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listRol = respuesta.data;
            $("#loaderTablaRoles").hide();
          } else {
            this.listRol = [];
            $("#loaderTablaRoles").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaRoles").hide();
        }

      }, error2 => {

      }
    )
  }

  crearRol() {
    this._UsuarioService.crearRol(this.token, this.objRol).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this.listarRoles();
          this.limpiarCampos();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }
  actualizarRol() {
    this._UsuarioService.actualizarRol(this.token, this.objRol).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
          this.listarRoles();
          this.limpiarCampos();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }
  limpiarCampos(){
    this.objRol = new Rol('000', '',
      '000', '', '');
  }
  seleccionarRol(Rol){
    this.objRol = Rol;
    this._ElementService.pi_poAlertaMensaje('Se selecciono el rol: '+this.objRol.descripcion_rol,'LTE-000');
  }
}
