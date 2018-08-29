import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/Observable";
import {GLOBAL} from "./global";

@Injectable()

export class UsuarioService {
  public url: any;

  constructor(private _Http: HttpClient) {
    this.url = GLOBAL.url;
  }

  crearUsuario(token, objUsuario): Observable<any> {
    let json = JSON.stringify(objUsuario);
    let parametros = 'token=' + token + '&json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'seguridad/crearUsuario', parametros, {headers: headers});
  }

  actualizarUsuario(token, objUsuario): Observable<any> {
    let json = JSON.stringify(objUsuario);
    let parametros = 'token=' + token + '&json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'seguridad/actualizarUsuario', parametros, {headers: headers});
  }

  actualizarRol(token, rol): Observable<any> {
    let json = JSON.stringify(rol);
    let parametros = 'token=' + token + '&json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'administrador/sistemas/actualizarRol', parametros, {headers: headers});
  }

  listarUsuarioSeguridad(token): Observable<any> {
    let parametros = 'token=' + token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'seguridad/listarUsuarios', parametros, {headers: headers});
  }
  listarRoles(token): Observable<any> {
    let parametros = 'token=' + token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'seguridad/listarRol', parametros, {headers: headers});
  }
  crearRol(token, objRol): Observable<any> {
    let json = JSON.stringify(objRol);
    let parametros = 'token=' + token + '&json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'seguridad/CrearRol', parametros, {headers: headers});
  }
  actualizarRolSitemas(token, objRol): Observable<any> {
    let json = JSON.stringify(objRol);
    let parametros = 'token=' + token + '&json=' + json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'seguridad/actualizarRol', parametros, {headers: headers});
  }
}
