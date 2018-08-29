import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Colegio} from "../../../models/sisCalificacion/colegio";
import {Lugar} from "../../../models/sisCalificacion/lugar";
import {ColegioService} from "../../../services/siscalificacion/colegio.service";

@Component({
  selector: 'app-editar-colegio',
  templateUrl: './editar-colegio.component.html',
  styleUrls: ['./editar-colegio.component.scss'],
  providers: [ElementsService, ColegioService]
})
export class EditarColegioComponent implements OnInit {
  public objColegio: Colegio;
  public listColegio: Array<any>;
  public listLugar: Array<Lugar>;
  public objLugar: Lugar;
  public token: any;
  public daneFiltro: any;
  position = "top-right";

  constructor(private _ElementService: ElementsService,
              private _ColegioService: ColegioService) {
    this.objColegio = new Colegio('', '', '', '');
    this.objLugar = new Lugar('000', '', '', '000');
    this.token = localStorage.getItem('token');
    this.daneFiltro = '';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('EditarColegioComponent');
    this.listarLugar();
    this.listarColegios();
    $("#loaderTablaColegios").hide();
  }

  listarLugar() {
    this._ColegioService.listarLugares(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listLugar = respuesta.data;
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            this.listLugar = [];
          }
        } else {

        }
      }, error2 => {

      }
    )
  }


  crearColegio() {
    this._ElementService.pi_poBontonDesabilitar('#btnCrearColegio');
    this.objColegio.lugaidn = this.objLugar.lugacoddanen;
    this._ColegioService.CrearColegio(this.token, this.objColegio).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          this.limpiarCampos();
          this.listarColegios();
          this._ElementService.pi_poBotonHabilitar('#btnCrearColegio');
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this._ElementService.pi_poBotonHabilitar('#btnCrearColegio');
        }
      }, error2 => {

      }
    )
  }

  actualizarColegio() {
    this._ElementService.pi_poBontonDesabilitar('#btnActualizarColegio');
    this.objColegio.lugaidn = this.objLugar.lugacoddanen;
    this._ColegioService.ActualizarColegio(this.token, this.objColegio).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          this.limpiarCampos();
          this.listarColegios();
          this._ElementService.pi_poBotonHabilitar('#btnActualizarColegio');
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          this._ElementService.pi_poBotonHabilitar('#btnActualizarColegio');
        }
      }, error2 => {

      }
    )
  }

  listarColegios() {
    $("#loaderTablaColegios").show();
    this._ColegioService.listarColegios(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listColegio = respuesta.data;
            $("#loaderTablaColegios").hide();
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            this.listColegio = [];
            $("#loaderTablaColegios").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  filtrar() {
    if (this.daneFiltro.trim() != '')
    {
      this._ElementService.pi_poBontonDesabilitar('#btnFiltrarColegio');
      $("#loaderTablaColegios").show();
      this._ColegioService.filtrarColegio(this.token, this.daneFiltro).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listColegio = respuesta.data;
              $("#loaderTablaColegios").hide();
              this._ElementService.pi_poBotonHabilitar('#btnFiltrarColegio');
            } else {
              this.listarColegios();
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se encontraron resultados');
              $("#loaderTablaColegios").hide();
              this._ElementService.pi_poBotonHabilitar('#btnFiltrarColegio');
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            this._ElementService.pi_poBotonHabilitar('#btnFiltrarColegio');
          }
        }, error2 => {

        }
      )
    }else
    {
      this._ElementService.pi_poAlertaError('Lo sentimos, El dane es requerido para filtrar','LTE-000');
    }

  }

  limpiarCampos() {
    this.objColegio = new Colegio('', '', '', '');
    this.objLugar = new Lugar('000', '', '', '000');
  }

  seleccionarColegio(colegio) {
    this.objColegio = colegio;
    this.objLugar.lugacoddanen = this.objColegio.lugaidn;
    this._ElementService.pi_poAlertaMensaje('Se selecciono el colegio con codigo dane: ' + this.objColegio.colecoddanen, 'LTE-000');
  }
}
