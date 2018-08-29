import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {NgbDateParserFormatter, NgbDateStruct, NgbCalendar} from '@ng-bootstrap/ng-bootstrap';
import {Registro_minutasService} from "../../../services/minutas/registro_minutas.service";
import {Usuario} from "../../../models/seguridad/usuario";
import {GLOBAL} from "../../../services/global";

const equals = (one: NgbDateStruct, two: NgbDateStruct) =>
  one && two && two.year === one.year && two.month === one.month && two.day === one.day;

const before = (one: NgbDateStruct, two: NgbDateStruct) =>
  !one || !two ? false : one.year === two.year ? one.month === two.month ? one.day === two.day
    ? false : one.day < two.day : one.month < two.month : one.year < two.year;

const after = (one: NgbDateStruct, two: NgbDateStruct) =>
  !one || !two ? false : one.year === two.year ? one.month === two.month ? one.day === two.day
    ? false : one.day > two.day : one.month > two.month : one.year > two.year;


const now = new Date();

export class Cmyk {
  constructor(public c: number, public m: number, public y: number, public k: number) {
  }
}

@Component({
  selector: 'app-generar-reporte',
  templateUrl: './generar-reporte.component.html',
  styleUrls: ['./generar-reporte.component.scss'],
  providers: [ElementsService, Registro_minutasService]
})
export class GenerarReporteComponent implements OnInit {

  navigation = 'select';

  hoveredDate: NgbDateStruct;
  fromDate: NgbDateStruct;
  toDate: NgbDateStruct;

  disabled = true;

  //@Input() testRangeDate: Date;

  toggle = false;


  public selectedColor = 'color';
  public token: any;
  public listUsuario: Array<Usuario>;
  public generarTodo: any;
  public objUsuario: Usuario;
  public urlArchivos: any;

  constructor(private _ElementService: ElementsService,
              private _RegistroMinutasService: Registro_minutasService,
              public parserFormatter: NgbDateParserFormatter,
              public calendar: NgbCalendar) {
    this.token = localStorage.getItem('token');
    this.generarTodo = false;
    this.objUsuario = new Usuario('', '', '', '', '', '', '',
      '', '', '', '', '');
    this.urlArchivos = GLOBAL.urlFiles;
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('GenerarReporteComponent');
    $("#loaderTablaUsuariosMinutas").hide();
    $("#loaderGenerarReporte").hide();

  }

  isHovered = date => this.fromDate && !this.toDate && this.hoveredDate && after(date, this.fromDate) && before(date, this.hoveredDate);
  isInside = date => after(date, this.fromDate) && before(date, this.toDate);
  isFrom = date => equals(date, this.fromDate);
  isTo = date => equals(date, this.toDate);


  onDateChange(date: NgbDateStruct) {
    if (!this.fromDate && !this.toDate) {
      this.fromDate = date;
    } else if (this.fromDate && !this.toDate && after(date, this.fromDate)) {
      this.toDate = date;

    } else {
      this.toDate = null;
      this.fromDate = date;
    }
    this.listarUsuariosRegistro();
  }

  listarUsuariosRegistro() {
    $("#loaderTablaUsuariosMinutas").show();
    this._RegistroMinutasService.listarUsuariosConMinutas(this.token, this.parserFormatter.format(this.fromDate), this.parserFormatter.format(this.toDate)).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listUsuario = respuesta.data;
            $("#loaderTablaUsuariosMinutas").hide();
          } else {
            this.listUsuario = [];
            $("#loaderTablaUsuariosMinutas").hide();
          }
        } else {

        }
      }, error2 => {

      }
    )
  }

  seleccionarUsuario(usuario) {
    this.objUsuario = usuario;
  }

  generarReporte() {
    if (this.listUsuario.length > 0) {
      if (this.toDate || this.fromDate) {
        $("#loaderGenerarReporte").show();
        this._ElementService.pi_poBontonDesabilitar('#btnGenerarReporte');
        if (this.generarTodo == true) {
          this._RegistroMinutasService.generarReporteTodo(this.token, this.parserFormatter.format(this.fromDate), this.parserFormatter.format(this.toDate)).subscribe(
            respuesta => {
              this._ElementService.pi_poValidarCodigo(respuesta);
              if (respuesta.status == 'success') {
                window.open(this.urlArchivos + respuesta.data);
                this._ElementService.pi_poBotonHabilitar('#btnGenerarReporte');
                $("#loaderGenerarReporte").hide();
              } else {
                this._ElementService.pi_poBotonHabilitar('#btnGenerarReporte');
                $("#loaderGenerarReporte").hide();
              }
            }, error2 => {

            }
          )
        } else {
          if (this.objUsuario.id_usuario != '') {
            this._RegistroMinutasService.generarReporteMinutaxUsuario(this.token, this.parserFormatter.format(this.fromDate), this.parserFormatter.format(this.toDate), this.objUsuario.id_usuario).subscribe(
              respuesta => {
                this._ElementService.pi_poValidarCodigo(respuesta);
                if (respuesta.status == 'success') {
                  window.open(this.urlArchivos + respuesta.data);
                  this._ElementService.pi_poBotonHabilitar('#btnGenerarReporte');
                  $("#loaderGenerarReporte").hide();
                } else {
                  this._ElementService.pi_poBotonHabilitar('#btnGenerarReporte');
                  $("#loaderGenerarReporte").hide();
                }
              }, error2 => {

              }
            )
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, Tienes que seleccionar un usuario.');
            $("#loaderGenerarReporte").hide();
          }

        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, Se necesita seleccionar una fecha inicial y una final para poder generar el reporte');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, Este dia no se registraron minutas');
    }

  }
}
