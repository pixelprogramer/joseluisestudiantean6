import {Injectable} from "@angular/core";
import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Observable} from "rxjs/Observable";
import {GLOBAL} from "./global";

@Injectable()
export class TrasladoUsuarioService {
  public url: string;

  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listar(variable): Observable<any> {
    let json = JSON.stringify(variable);
    let params = 'json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'listarUsuario', params, {headers: headers});
  }
  filtrarUsuario(filtro,variable): Observable<any> {

    let json = JSON.stringify(variable);
    let params = 'filtro=' + filtro+'&json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'traslado/filtroEstudiante', params, {headers: headers});
  }
  actualizar(variable,estudiantes,token): Observable<any> {
    let json = JSON.stringify(variable);
    let params = 'json=' + json+'&estudiantes='+JSON.stringify(estudiantes)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'actualizar', params, {headers: headers});
  }




}
