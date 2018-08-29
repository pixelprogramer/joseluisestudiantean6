import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {AlmacenService} from "../../../services/calificacion/almacen.service";
import {Almacen_calificacion} from "../../../models/almacen/almacen_calificacion";
import swal from "sweetalert2";

@Component({
  selector: 'app-almacen-calificacion',
  templateUrl: './almacen-calificacion.component.html',
  styleUrls: ['./almacen-calificacion.component.scss'],
  providers: [ElementsService, AlmacenService]
})
export class AlmacenCalificacionComponent implements OnInit {
  public token: any;
  public listAlamacen: Array<Almacen_calificacion>;
  public objAlamacen: Almacen_calificacion;
  public nuevaCantidad: any;
  position = "top-right";
  constructor(private _ElementService: ElementsService,
              private _AlmacenService: AlmacenService) {
    this.token = localStorage.getItem('token');
    $("#loaderTablaProductos").hide();
    this.nuevaCantidad = '0';
    this.objAlamacen = new Almacen_calificacion('', '', '','');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('AlmacenCalificacionComponent');
    this.listarAlmacen();
  }

  listarAlmacen() {
    $("#loaderTablaProductos").show();
    this._AlmacenService.listarAlmacen(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listAlamacen = respuesta.data;
          $("#loaderTablaProductos").hide();
        } else {
          $("#loaderTablaProductos").hide();
        }
      }, error2 => {

      }
    )
  }

  cargarCantidad(registros) {
    this.objAlamacen = registros;
  }

  agregarNuevaCantidad() {
    if (parseInt(this.nuevaCantidad)>0){
      this.objAlamacen.total_cantidad_almacen_calificacion = (parseInt(this.objAlamacen.total_cantidad_almacen_calificacion) + parseInt(this.nuevaCantidad));
      swal({
        title: 'Esta seguro de agregar esta nueva cantidad',
        text: this.objAlamacen.total_cantidad_almacen_calificacion,
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Si, lo estoy'
      }).then((result) => {
        $("#loaderUsuario").show();
        if (result.value) {
          this._AlmacenService.actualizarCantidadAlmacen(this.token, this.objAlamacen).subscribe(
            respuesta => {
              this._ElementService.pi_poValidarCodigo(respuesta);
              if (respuesta.status == 'success') {
                this._ElementService.pi_poAlertaSuccess(respuesta.msg,respuesta.code);
              } else {
                this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
              }
            }, error2 => {

            }
          );
          this.nuevaCantidad = 0;
        } else {
          this.objAlamacen.total_cantidad_almacen_calificacion = (parseInt(this.objAlamacen.total_cantidad_almacen_calificacion) - parseInt(this.nuevaCantidad));
          this.nuevaCantidad = 0;
        }
      });
    }else
    {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos, El valor de la nueva cantidad debe ser mayor a 0');
    }

  }
}
