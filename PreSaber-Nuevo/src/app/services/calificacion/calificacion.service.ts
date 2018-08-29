import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class CalificacionService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarEstudianteUnificacion(cadenaPaquetes): Observable<any> {
    let params = 'cadenaPaquetes=' + cadenaPaquetes;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/listarEstudiantesPaquetes',params, {headers: headers});
  }
  unificarEstudiantes(cadenaEstudiantes,token): Observable<any> {
    let params = 'cadenaEstudiantes=' + cadenaEstudiantes+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/unificarEstudiantes',params, {headers: headers});
  }
  filtrarEstudiantes(cadenaPaquetes,filtro): Observable<any> {
    let params = 'cadenaPaquetes=' + cadenaPaquetes+'&filtro='+filtro;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/filtrarEstudianteUnificar',params, {headers: headers});
  }
  generarReporteIndicadores(fecha_inicial,fecha_final): Observable<any> {
    let params = 'fecha_inicial=' + fecha_inicial+'&fecha_final='+fecha_final;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/generarIndicadores',params, {headers: headers});
  }
}
