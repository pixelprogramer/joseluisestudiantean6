import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class ColegioService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarLugares(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/siscalificacion/listarLugar',params,{headers: headers});
  }
  listarColegios(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/siscalificacion/listarColegio',params, {headers: headers});
  }
  CrearColegio(token,objColegio): Observable<any> {

    let json = JSON.stringify(objColegio);
    let params = 'json=' + json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/siscalificacion/crearColegio',params, {headers: headers});
  }
  ActualizarColegio(token,objColegio): Observable<any> {
    let json = JSON.stringify(objColegio);
    let params = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/siscalificacion/actualizarColegio',params, {headers: headers});
  }
  filtrarColegio(token,dane): Observable<any> {
    let params = 'token='+token+'&dane='+dane;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/siscalificacion/filtrar',params,{headers: headers});
  }
}
