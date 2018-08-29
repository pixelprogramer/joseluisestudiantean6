import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/Observable";
import {GLOBAL} from "../global";

@Injectable()

export class RolService{
  public url: any;
  constructor(private _Http: HttpClient)
  {
    this.url = GLOBAL.url;
  }

  crearRol(token,rol): Observable<any>
  {
    let json = JSON.stringify(rol);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/crearRol',parametros,{headers:headers});
  }
  actualizarRol(token,rol): Observable<any>
  {
    let json = JSON.stringify(rol);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/actualizarRol',parametros,{headers:headers});
  }
  listarRol(token): Observable<any>
  {
    let parametros = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/listarRol',parametros,{headers:headers});
  }
}
