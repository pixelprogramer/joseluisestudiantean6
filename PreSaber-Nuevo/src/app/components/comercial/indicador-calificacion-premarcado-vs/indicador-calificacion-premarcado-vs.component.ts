import { Component, OnInit } from '@angular/core';
import {CalificacionService} from "../../../services/calificacion/calificacion.service";
import {NgbCalendar, NgbDateParserFormatter, NgbDateStruct} from "@ng-bootstrap/ng-bootstrap";
import {GLOBAL} from "../../../services/global";
import {ElementsService} from "../../../services/elements.service";



const equals = (one: NgbDateStruct, two: NgbDateStruct) =>
  one && two && two.year === one.year && two.month === one.month && two.day === one.day;

const before = (one: NgbDateStruct, two: NgbDateStruct) =>
  !one || !two ? false : one.year === two.year ? one.month === two.month ? one.day === two.day
    ? false : one.day < two.day : one.month < two.month : one.year < two.year;

const after = (one: NgbDateStruct, two: NgbDateStruct) =>
  !one || !two ? false : one.year === two.year ? one.month === two.month ? one.day === two.day
    ? false : one.day > two.day : one.month > two.month : one.year > two.year;

@Component({
  selector: 'app-indicador-calificacion-premarcado-vs',
  templateUrl: './indicador-calificacion-premarcado-vs.component.html',
  styleUrls: ['./indicador-calificacion-premarcado-vs.component.scss'],
  providers: [ElementsService]
})
export class IndicadorCalificacionPremarcadoVsComponent implements OnInit {

  navigation = 'select';

  hoveredDate: NgbDateStruct;
  fromDate: NgbDateStruct;
  toDate: NgbDateStruct;

  disabled = true;

  //@Input() testRangeDate: Date;

  toggle = false;
  urlFile: any;
  constructor(private _ElementService: ElementsService,public parserFormatter: NgbDateParserFormatter,
              public calendar: NgbCalendar, ) {
    this.urlFile = GLOBAL.urlFiles;
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('IndicadorCalificacionPremarcadoVsComponent');
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
  }
  generarReporte(){
    this._ElementService.pi_poBontonDesabilitar('#btnGenerarReporte');
    if (this.toDate || this.fromDate) {

    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, Se necesita seleccionar una fecha inicial y una final para poder generar el reporte');
      this._ElementService.pi_poBotonHabilitar('#btnGenerarReporte');
    }

  }
}
