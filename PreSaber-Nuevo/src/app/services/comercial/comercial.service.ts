import {Observable} from "rxjs/Rx";
import {GLOBAL} from "../global";
import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";


@Injectable()
export class ComercialService{
  url = '';
  constructor(public _http: HttpClient){
    this.url = GLOBAL.url;
  }

  generarReporteCalificadoVsPremarcado(token,fechaInicial,FechaFinal):Observable <any>{
    let parametros = 'token='+token+'&fechaInicial='+fechaInicial+'&fechaFinal='+FechaFinal;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/calificacionvspremarcado',parametros, {headers: headers});
  }
}
