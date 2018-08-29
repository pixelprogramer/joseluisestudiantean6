import {HttpClient, HttpHeaders} from "@angular/common/http";
import {Injectable} from "@angular/core";
import {Observable} from "rxjs/Observable";
import {GLOBAL} from "../global";

@Injectable()

export class MenuService{
  public url: any;
  constructor(private _Http: HttpClient)
  {
    this.url = GLOBAL.url;
  }

  crearMenu(token,menu): Observable<any>
  {
    let json = JSON.stringify(menu);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/crearMenu',parametros,{headers:headers});
  }
  actualizarMenuPrioridad(token,menu): Observable<any>
  {
    let json = JSON.stringify(menu);
    let parametros = 'token='+token+'&menu='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/actualizarPrioridad',parametros,{headers:headers});
  }
  actualizarMenu(token,menu): Observable<any>
  {
    let json = JSON.stringify(menu);
    let parametros = 'token='+token+'&json='+json;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/actualizarMenu',parametros,{headers:headers});
  }
  listarMenu(token): Observable<any>
  {
    let parametros = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/listarMenu',parametros,{headers:headers});
  }
  listarMenuTodo(objRol): Observable<any>
  {
    let parametros = 'json='+JSON.stringify(objRol);
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/todoMenu',parametros,{headers:headers});
  }
  nuevoSubMenu(token,objsubMenu): Observable<any>
  {
    let parametros = 'json='+JSON.stringify(objsubMenu)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/nuevoSubMenu',parametros,{headers:headers});
  }
  eliminarSubMenuMneu(token,objsubMenu): Observable<any>
  {
    let parametros = 'json='+JSON.stringify(objsubMenu)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/eliminarSubmenuMenu',parametros,{headers:headers});
  }
  crearCabezera(token,objCabezera): Observable<any>
  {
    let parametros = 'json='+JSON.stringify(objCabezera)+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._Http.post(this.url+'administrador/sistemas/nuevaCabezera',parametros,{headers:headers});
  }
}
