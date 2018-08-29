import {Observable} from "rxjs/Observable";
import {HttpClient,HttpHeaders} from "@angular/common/http";
import {GLOBAL} from "../global";
import {Injectable} from "@angular/core";


@Injectable()

export class AlmacenService{
  public url: any;
  constructor(private _Http: HttpClient){
    this.url = GLOBAL.url;
  }

  listarAlmacen(token): Observable<any>{
    let parametros = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'calificacion/almacen/listar',parametros, {headers: headers});
  }
  listarStockUsuario(token,id): Observable<any>{
    let parametros = 'token='+token+'&id='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'calificacion/almacen/listarStockUsuario',parametros, {headers: headers});
  }
  listarUsuariosAlmacen(token): Observable<any>{
    let parametros = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'calificacion/almacen/listarUsuariosAlmacen',parametros, {headers: headers});
  }
  actualizarCantidadAlmacen(token,obj): Observable<any>{
    let json = JSON.stringify(obj);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'calificacion/almacen/actualizarCantidadAlmacen',parametros, {headers: headers});
  }
  nuevaCantidadUsuario(token,objAlmacen,objStockUsuario): Observable<any>{
    let objCompleto = Object.assign(objAlmacen,objStockUsuario);
    let json = JSON.stringify(objCompleto);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url + 'calificacion/almacen/agregarStockUsuario',parametros, {headers: headers});
  }
}
