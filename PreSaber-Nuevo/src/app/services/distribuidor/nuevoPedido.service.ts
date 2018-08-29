import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class NuevoPedidoService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarColegios(token): Observable<any> {
    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarColegios',params,{headers: headers});
  }
  filtrarColegio(filtroColegio,token): Observable<any> {
    let params = 'filtro='+filtroColegio+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/filtrarColegio',params, {headers: headers});
  }
  filtrarCiudad(filtroColegio,token): Observable<any> {
    let params = 'filtro='+filtroColegio+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/filtrarCiudad',params, {headers: headers});
  }
  nuevoPedido(token,objOrder): Observable<any> {
    let json = JSON.stringify(objOrder);
    let params = 'json='+json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/nuevoPedido',params, {headers: headers});
  }
  actualizarPedido(token,objOrder): Observable<any> {
    let json = JSON.stringify(objOrder);
    let params = 'json='+json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/actualizarPedido',params, {headers: headers});
  }
  nuevoDetalle(token,objOrderDetail): Observable<any> {
    let json = JSON.stringify(objOrderDetail);
    let params = 'json='+json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/nuevoDetalle',params, {headers: headers});
  }
  listarOrderType(token): Observable<any> {

    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarOrderTipe',params, {headers: headers});
  }
  listarDistribuidoras(token): Observable<any> {

    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarDistribuidora',params, {headers: headers});
  }
  listarShippingType(token): Observable<any> {

    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarShippingType',params, {headers: headers});
  }
  listarCiduades(token): Observable<any> {

    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarCiudades',params, {headers: headers});
  }
  listarProductos(token): Observable<any> {

    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarProductos',params, {headers: headers});
  }

  filtrarProducto(filtroProducto,token): Observable<any> {
    let params = 'filtro='+filtroProducto+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/filtrarProducto',params, {headers: headers});
  }

  listarDetallePedido(id,token): Observable<any> {
    let params = 'id='+id+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarDetallesPedido',params, {headers: headers});
  }
  listarPedidosDistribuidor(token): Observable<any> {

    let params = 'token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/listarPedidosDistribuidor',params, {headers: headers});
  }
  recargarPedidoSeleccionado(token,id): Observable<any> {

    let params = 'token='+token+'&id='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/recargarPedidoSeleccionado',params, {headers: headers});
  }
  editarDetallePedido(token,orderDetail): Observable<any> {
    let json = JSON.stringify(orderDetail);
    let params = 'json='+json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/editarDetalle',params, {headers: headers});
  }

  eliminarDetallePedido(token,orderDetail): Observable<any> {
    let json = JSON.stringify(orderDetail);
    let params = 'json='+json+'&token='+token;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'distribuidor/eliminarDetallePedido',params, {headers: headers});
  }

  nuevoPedidoListas(token, params: Array<string>, files: Array<File>, data = null) {
    let urlF = this.url + 'distribuidor/nuevoPedido';
    return new Promise((resolve, reject) => {
      var formData: any = new FormData();
      var xhr = new XMLHttpRequest();
      if (files != undefined) {
        var name_file_input = params[0];
        for (var i = 0; i < files.length; i++) {
          formData.append('archivo[]', files[i], files[i].name);
        }
      }
      if  (token != null)
      {
        formData.append('token', token);
      }
      if (data != null)
        var json = JSON.stringify(data);
      formData.append('json', json);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            resolve(JSON.parse(xhr.response));
          } else {
            reject(xhr.response);
          }
        }
      }
      xhr.open("POST", urlF, true);
      xhr.send(formData);
    });
  }
  nuevoArchivo(token, params: Array<string>, files: Array<File>, data = null) {
    let urlF = this.url + 'distribuidor/nuevoArchivos';
    return new Promise((resolve, reject) => {
      var formData: any = new FormData();
      var xhr = new XMLHttpRequest();
      if (files != undefined) {
        var name_file_input = params[0];
        for (var i = 0; i < files.length; i++) {
          formData.append('archivo[]', files[i], files[i].name);
        }
      }
      if  (token != null)
      {
        formData.append('token', token);
      }
      if (data != null)
        var json = JSON.stringify(data);
      formData.append('json', json);
      xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
          if (xhr.status == 200) {
            resolve(JSON.parse(xhr.response));
          } else {
            reject(xhr.response);
          }
        }
      }
      xhr.open("POST", urlF, true);
      xhr.send(formData);
    });
  }
  eliminarArchivos(token,recurso,id): Observable<any>
  {

    let json = JSON.stringify(recurso);
    let parametros = 'token='+token+'&json='+json+'&id_archivo='+id;
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + '/distribuidor/eliminarArchivo',parametros,{headers: headers});
  }
}
