import {Component, OnInit} from '@angular/core';
import {Curso} from "../../models/curso";
import {CursoService} from "../../services/curso.service";
import {ElementsService} from "../../services/elements.service";

@Component({
  selector: 'app-crear-grupos',
  templateUrl: './crear-grupos.component.html',
  styleUrls: ['./crear-grupos.component.scss'],
  providers: [CursoService, ElementsService]
})
export class CrearGruposComponent implements OnInit {
  public objCurso: Curso;
  public objCursoHijos: Curso;
  public listCurso: Array<Curso>;
  public listCursoHijos: Array<Curso>;
  public token: any;
  public filtroGrado: any;
  position = "top-right";

  constructor(private _CursoService: CursoService,
              private _ElementService: ElementsService) {
    this.token = localStorage.getItem('token');
    this.objCurso = new Curso('', '', '');
    this.objCursoHijos = new Curso('', '', '');
    this.filtroGrado = '';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearGruposComponent');
    this.listarCursos();
    $("#loaderTablaGrado").hide();
    $("#loaderTablaHijos").hide();
  }

  crearGrupo() {
    $("#loaderTablaGrado").show();
    this._CursoService.crearCurso(this.objCurso, this.token).subscribe(
      respuesta => {

        if (respuesta.status == 'success') {
          this.objCurso.cursdescripv = '';
          this.listarCursos();
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          $("#loaderTablaGrado").hide();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaGrado").hide();
        }
      }, error2 => {

      }
    )
  }

  crearHijosGrupo() {
    $("#loaderTablaHijos").show();
    this._CursoService.crearCurso(this.objCursoHijos, this.token).subscribe(
      respuesta => {

        if (respuesta.status == 'success') {
          this.objCursoHijos.cursdescripv = '';
          this.listarCursosHijos();
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
          $("#loaderTablaHijos").hide();
        } else {
          $("#loaderTablaHijos").hide();
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  listarCursos() {
    $("#loaderTablaGrado").show();
    this._CursoService.listar().subscribe(
      respuesta => {
        if (respuesta.status == 'success') {
          this.listCurso = respuesta.data;
          $("#loaderTablaGrado").hide();
        } else {
          $("#loaderTablaGrado").hide();
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  listarCursosHijos() {
    $("#loaderTablaHijos").show();
    this._CursoService.listarHijos(this.objCursoHijos).subscribe(
      respuesta => {
        if (respuesta.status == 'success') {
          this.listCursoHijos = respuesta.data;
          $("#loaderTablaHijos").hide();
        } else {
          $("#loaderTablaHijos").hide();
          this.listCursoHijos = [];
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  seleccionarGrado(objCursoP) {
    this.objCurso = objCursoP;
    this.objCursoHijos.cursidn = this.objCurso.cursidn;
    this.objCursoHijos.cur_cursidn = this.objCurso.cursidn;
    this.listarCursosHijos();
  }

  actualizarCurso() {
    if (this.objCurso.cur_cursidn != ''){
      if (this.objCurso.cursdescripv != '') {
        this._CursoService.actualizarCurso(this.objCurso, this.token).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.objCurso.cursdescripv = '';
              this.listarCursos();
              this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
            } else {

            }
          }, error2 => {

          }
        )
      } else {
        this._ElementService.pi_poAlertaError('El campo descripcion es requerido', 'LTE-000');
      }
    }else
    {
      this._ElementService.pi_poAlertaError('Por favor seleccione un grupo', 'LTE-000');
    }

  }

  filtrarGrupo() {
    if (this.filtroGrado.trim() != '') {
      this._CursoService.filtrarCurso(this.filtroGrado, this.token).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data !=0)
            {
              this.listCurso = respuesta.data;
              this._ElementService.pi_poAlertaSuccess('Se encontraron resultados','LTE-000');
            }else
            {
              this.listCurso = [];
              this.listarCursos();
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se encontraron resultados');
            }

          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          }
        }, error2 => {

        }
      )
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El grado es requerido para filtrar');
      this.listarCursos();
    }
  }
}
