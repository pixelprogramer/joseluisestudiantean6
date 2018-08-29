import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class SubCategoriasService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarsubCategoriasCategorias(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarCategoriaSubCategorias',params,{headers: headers});
  }
  añadirSubCategoria(objSubCategoria,token): Observable<any> {
    let params = 'json=' + JSON.stringify(objSubCategoria)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/nuevaSubCategoria',params, {headers: headers});
  }
  eliminarSubCategoria(objSubCategoria,token): Observable<any> {
    let params = 'json=' + JSON.stringify(objSubCategoria)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/eliminarSub',params, {headers: headers});
  }
  listarCategoria(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarCategoria',params,{headers: headers});
  }
}
