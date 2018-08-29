import {HttpClient, HttpHeaders} from "@angular/common/http";
import  {Observable} from "rxjs/Observable";
import {Injectable} from "@angular/core";
import {GLOBAL} from "../global";

@Injectable()

export class PantallasService{
  public url: any;
  constructor(public _http: HttpClient) {
    this.url = GLOBAL.url;
  }

  listarPantallaComercial(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'pantallas/calificacion', {headers: headers});
  }
  listarPantallaLogistica(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'pantallas/logistica', {headers: headers});
  }
  listarPantallaCalificacion(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'pantallas/calificacion', {headers: headers});
  }
  listarPantallaPremarcado(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'pantallas/premarcado', {headers: headers});
  }
  //Cantidades Colegio y estudiantes
  cantidadColegioEstudianteComercial(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/datosOrders', {headers: headers});
  }
  cantidadColegioEstudianteLogistica(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'logistica/datosOrders', {headers: headers});
  }
  cantidadColegioEstudianteCalificacion(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'calificacion/datosOrders', {headers: headers});
  }
  cantidadColegioEstudiantePremarcado(): Observable<any> {
    let headers = new HttpHeaders().set('Content-Type', 'application/x-www-form-urlencoded');
    return this._http.post(this.url + 'premarcado/datosOrders', {headers: headers});
  }

}
