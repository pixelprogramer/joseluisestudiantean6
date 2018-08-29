import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class AccionesService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarCategoriaCrear(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarCategoriaCrear',params,{headers: headers});
  }
  crearAccion(objAccion,token): Observable<any> {
    let params = 'json=' + JSON.stringify(objAccion)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/nuevaAccion',params, {headers: headers});
  }
  actualizarAccion(objAccion,token): Observable<any> {
    let params = 'json=' + JSON.stringify(objAccion)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/actualizarAccion',params, {headers: headers});
  }
  listarAcciones(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarAccion',params,{headers: headers});
  }
}
