import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Acciones_minutas} from "../../../models/minutas/acciones_minutas";
import {AccionesService} from "../../../services/minutas/acciones.service";

@Component({
  selector: 'app-crear-accion',
  templateUrl: './crear-accion.component.html',
  styleUrls: ['./crear-accion.component.scss'],
  providers: [ElementsService, AccionesService]
})
export class CrearAccionComponent implements OnInit {
  public token: any;
  public objAcciones: Acciones_minutas;
  public listAcciones: Array<Acciones_minutas>;
  position = "top-right";

  constructor(private _ElementService: ElementsService,
              private _AccioneService: AccionesService) {
    this.token = localStorage.getItem('token');
    this.objAcciones = new Acciones_minutas('', '', false,
      '', '', '000', '');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearAccionComponent');
    this.listarAcciones();
    $("#loaderTablaCategoriaAcciones").hide();
    this._ElementService.pi_poBontonDesabilitar('#btnActualizarAccion');
  }

  crearAccion() {

    this._AccioneService.crearAccion(this.objAcciones, this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.limpiarCampos();
          this.listarAcciones();
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
        }
      }, error2 => {

      }
    )

  }

  limpiarCampos() {
    this.objAcciones = new Acciones_minutas('', '', false,
      '', '', '000', '');
  }
  listarAcciones()
  {
    $("#loaderTablaCategoriaAcciones").show();
    this._AccioneService.listarAcciones(this.token).subscribe(
      respuesta=>{
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          $("#loaderTablaCategoriaAcciones").hide();
          this.listAcciones = respuesta.data;
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
        }
      },error2 => {

      }
    )
  }
  seleccionarAccion(acciones){
    this.objAcciones=acciones;
    this._ElementService.pi_poAlertaWarning('Se selecciono la accion: '+this.objAcciones.descripcion_acciones_minutas,'LTE-000');
    this._ElementService.pi_poBontonDesabilitar('#btnCrearAccion');
    this._ElementService.pi_poBotonHabilitar('#btnActualizarAccion');
  }
  actualizarAccion()
  {
    if (this.objAcciones.descripcion_acciones_minutas != '' || this.objAcciones.estado_acciones_minutas != '000'){
      this._AccioneService.actualizarAccion(this.objAcciones,this.token).subscribe(
        respuesta=>{
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            this.limpiarCampos();
            this.listarAcciones();
            this._ElementService.pi_poBotonHabilitar('#btnCrearAccion');
            this._ElementService.pi_poBontonDesabilitar('#btnActualizarAccion');
            this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
          }
        },error2 => {

        }
      )
    }else
    {
      this._ElementService.pi_poAlertaError('Lo sentimos, La descripcion y el estado son requeridos','LTE-000');
    }
  }
}
