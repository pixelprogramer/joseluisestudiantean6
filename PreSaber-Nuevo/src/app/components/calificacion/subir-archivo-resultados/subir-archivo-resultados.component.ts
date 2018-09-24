import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Archivos} from "../../../models/sisCalificacion/archivos";
import {CalificacionService} from "../../../services/calificacion/calificacion.service";
import {GLOBAL} from "../../../services/global";


@Component({
  selector: 'app-subir-archivo-resultados',
  templateUrl: './subir-archivo-resultados.component.html',
  styleUrls: ['./subir-archivo-resultados.component.scss'],
  providers: [ElementsService, CalificacionService]
})
export class SubirArchivoResultadosComponent implements OnInit {
  public objSubirResultados: Archivos;
  public filesToUpload;
  position = "top-right";
  public token: any;
  public r: any;
  public urlFile: any;

  constructor(private _ElementService: ElementsService,
              private _CalificacionServices: CalificacionService) {
    this.objSubirResultados = new Archivos('', '', '', '', '', '', '', '');
    this.token = localStorage.getItem('token');
    this.urlFile = GLOBAL.urlFiles;
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('SubirArchivoResultadosComponent');
    this._ElementService.pi_poBontonDesabilitar('#btnActualizar');
    $("#loaderSubirArchivos").hide();

  }

  fileChangeEvent(fileinput: any) {
    this.filesToUpload = <Array<File>>fileinput.target.files;
    console.log(this.filesToUpload);
  }

  subirArchivos() {
    $("#loaderSubirArchivos").show();
    this._ElementService.pi_poBontonDesabilitar('#btnSubir');
    if (this.filesToUpload != null) {
      if (this.objSubirResultados.name.trim() != '' && this.objSubirResultados.description.trim() != '') {
        this._CalificacionServices.subirResultadosArchivo(this.token, ['upload'], this.filesToUpload, this.objSubirResultados).then(
          respuesta => {
            this.r = respuesta;
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (this.r.status == 'success') {
              if (this.r.data.tipo == '1') {
                this.limpiarCampos();
                this._ElementService.pi_poBontonDesabilitar('#btnActualizar');
                this._ElementService.pi_poAlertaSuccess(this.r.msg, this.r.code);
                $("#loaderSubirArchivos").hide();
              } else {
                this.objSubirResultados.id = this.r.data.id;
                this.objSubirResultados.name = this.r.data.name;
                this.objSubirResultados.description = this.r.data.description;
                this._ElementService.pi_poBontonDesabilitar('#btnSubir');
                this._ElementService.pi_poBotonHabilitar('#btnActualizar');
                this._ElementService.pi_poAlertaSuccess('Este repositorio ya existe, ya lo puedes editar', this.r.code);
                $("#loaderSubirArchivos").hide();
              }
              this.urlFile = GLOBAL.urlFiles;
              this.urlFile += this.r.data.ruta;
              let frame = '<iframe src="' + this.urlFile + '"style="width:100%; height:1200px;" frameborder="0"></iframe>';
              $("#seccioPdf").html(frame);
              $("#loaderSubirArchivos").hide();
            }
          }
        )
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, todos los campos son requeridos', 'LTE-000');
        $("#loaderSubirArchivos").hide();
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El archivo es requerido.');
      $("#loaderSubirArchivos").hide();
    }
  }

  actualizarArchivos() {
    $("#loaderSubirArchivos").show();
    this._ElementService.pi_poBontonDesabilitar('#btnActualizar');
    if (this.filesToUpload != null) {
      if (this.objSubirResultados.name.trim() != '' && this.objSubirResultados.description.trim() != '') {
        this._CalificacionServices.actualizarResultadosArchivo(this.token, ['upload'], this.filesToUpload, this.objSubirResultados).then(
          respuesta => {
            this.r = respuesta;
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (this.r.status == 'success') {
              this._ElementService.pi_poAlertaSuccess(this.r.msg, this.r.code);
              this.limpiarCampos();
              this._ElementService.pi_poBotonHabilitar('#btnSubir');
              this._ElementService.pi_poBontonDesabilitar('#btnActualizar');
              this.urlFile = GLOBAL.urlFiles;
              this.urlFile += this.r.data.ruta;
              let frame = '<iframe src="' + this.urlFile + '"style="width:100%; height:1200px;" frameborder="0"></iframe>';
              $("#seccioPdf").html(frame);
              $("#loaderSubirArchivos").hide();
            }
          }
        )
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, todos los campos son requeridos', 'LTE-000');
        $("#loaderSubirArchivos").hide();
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El archivo es requerido.');
      $("#loaderSubirArchivos").hide();
    }
  }

  limpiarCampos() {
    this.filesToUpload = null;
    this.objSubirResultados = new Archivos('', '', '', '', '', '', '', '');
  }
}
