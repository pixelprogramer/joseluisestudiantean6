import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class Registro_minutasService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarMinutasxUsuario(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarMinutasUsuario',params,{headers: headers});
  }
  listarPedidosDetalles(pedidos,token): Observable<any> {
    let params = 'json={"pedidos":"'+pedidos+'"} '+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/listarPedidosDetalles',params, {headers: headers});
  }
  crearRegistroMinuta(token,objPedido,objRegistroMinuta,arregloDetalles,id): Observable<any> {

    let objCompleto = Object.assign(objPedido,objRegistroMinuta);
    let jsonDetalles = JSON.stringify(arregloDetalles);
    let json = JSON.stringify(objCompleto);
    let params = 'json=' + json+'&token='+token+'&detalle='+jsonDetalles+'&idAccion='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/crearRegistroMinutas',params, {headers: headers});
  }
  crearRegistroMinutaSimple(token,id,descripcion): Observable<any> {

    let params = 'token='+token+'&idAccion='+id+'&descripcion='+descripcion;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/crearRegistroMinutasSimple',params, {headers: headers});
  }
  detenerMinuta(token,objRegistroMinuta): Observable<any> {
    let json = JSON.stringify(objRegistroMinuta);
    let params = 'json=' + json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/detenerMinuta',params, {headers: headers});
  }
  terminarMinuta(token,objRegistroMinuta): Observable<any> {
    let json = JSON.stringify(objRegistroMinuta);
    let params = 'json=' + json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/terminarMinuta',params, {headers: headers});
  }
  continuarMinuta(token,objRegistroMinuta): Observable<any> {
    let json = JSON.stringify(objRegistroMinuta);
    let params = 'json=' + json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/unix/continuarMinuta',params, {headers: headers});
  }
  listarNoConformida(id): Observable<any> {
    let params = 'id=' + id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'noConformes/listarNoConformes',params, {headers: headers});
  }
  listarCategoriaNoConformida(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'noConformes/listarCategoria', {headers: headers});
  }
  calcularTiempoMuerto(objTiempoMuerto,token): Observable<any> {
    let json = JSON.stringify(objTiempoMuerto);
    let parametros = 'json='+json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'minutas/calcularTiempoMuerto',parametros, {headers: headers});
  }
  listarUsuariosConMinutas(token,fecha_inicio,fecha_final): Observable<any> {
    let parametros = 'token='+token+'&fecha_inicial='+fecha_inicio+'&fecha_final='+fecha_final;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/minutas/listarUsuariosConMinutas',parametros, {headers: headers});
  }
  generarReporteTodo(token,fecha_inicio,fecha_final): Observable<any> {
    let parametros = 'token='+token+'&fecha_inicial='+fecha_inicio+'&fecha_final='+fecha_final;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/minutas/reportes/todos',parametros, {headers: headers});
  }
  generarReporteMinutaxUsuario(token,fecha_inicio,fecha_final,id_usuario): Observable<any> {
    let parametros = 'token='+token+'&fecha_inicial='+fecha_inicio+'&fecha_final='+fecha_final+'&id_usuario='+id_usuario;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/minutas/reportes/minutaXusuario',parametros, {headers: headers});
  }
}
