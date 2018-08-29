import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "./global";

@Injectable()

export class CursoService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listar(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/listarCursos', {headers: headers});
  }
  listarHijos(objCurso): Observable<any> {
    let json = JSON.stringify(objCurso);
    let params = 'json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/listarCursosHijos',params, {headers: headers});
  }
  actualizarCurso(objCurso,token): Observable<any> {
    let json = JSON.stringify(objCurso);
    let params = 'json=' + json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/actualizarGrupo',params, {headers: headers});
  }
  crearCurso(objCurso,token): Observable<any> {
    let json = JSON.stringify(objCurso);
    let params = 'json=' + json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/crearGrupo', params, {headers: headers});
  }
  filtrarCurso(filtro,token): Observable<any> {
    let params = 'filtro=' + filtro+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/filtrarGrupo', params, {headers: headers});
  }
}
