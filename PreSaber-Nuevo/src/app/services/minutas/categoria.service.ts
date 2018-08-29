import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class CategoriaService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarCategoriaCrear(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarCategoriaCrear',params,{headers: headers});
  }
  crearCategoria(objCategoria,token): Observable<any> {
    let params = 'json=' + JSON.stringify(objCategoria)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/nuevaCategoria',params, {headers: headers});
  }
  listarCategoria(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarCategoria',params,{headers: headers});
  }
}
