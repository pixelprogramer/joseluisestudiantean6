import {Component, OnInit} from '@angular/core';
import {CalificacionService} from "../../../services/calificacion/calificacion.service";
import {ElementsService} from "../../../services/elements.service";
import {AlmacenService} from "../../../services/calificacion/almacen.service";
import {Usuario} from "../../../models/seguridad/usuario";
import {Almacen_calificacion} from "../../../models/almacen/almacen_calificacion";
import swal from "sweetalert2";
import {Stock_usuario_almacen_calificacion} from "../../../models/almacen/stock_usuario_almacen_calificacion";

@Component({
  selector: 'app-stock-usuario',
  templateUrl: './stock-usuario.component.html',
  styleUrls: ['./stock-usuario.component.scss'],
  providers: [AlmacenService, ElementsService]
})
export class StockUsuarioComponent implements OnInit {
  public token: any;
  public objUsuario: Usuario;
  public listUsuario: Array<Usuario>;
  public listAlamacen: Array<Almacen_calificacion>;
  public listStockUsuario = [];
  public objAlamacen: Almacen_calificacion;
  public nuevaCantidad: any;
  public stockUsuario: Stock_usuario_almacen_calificacion;
  position = "top-right";
  constructor(private _ElementService: ElementsService,
              private _AlmacenService: AlmacenService) {
    this.token = localStorage.getItem('token');
    this.objUsuario = new Usuario('', '', '', '', '',
      '', '', '', '', '', '', '');
    this.stockUsuario = new Stock_usuario_almacen_calificacion('', '', '', '');
    $("#loaderTablaUsuarios").hide();
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('StockUsuarioComponent');
    this.listarUsuarioAlmacen();
  }

  listarUsuarioAlmacen() {
    $("#loaderTablaUsuarios").show();
    this._AlmacenService.listarUsuariosAlmacen(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listUsuario = respuesta.data;
          $("#loaderTablaUsuarios").hide();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaUsuarios").hide();
        }
      }, error2 => {

      }
    )
  }

  listarAlmacen(usuario) {
    this.objUsuario = usuario;
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

  agregarNuevaCantidad(almacen) {
    this.objAlamacen = almacen;
    this.stockUsuario.fk_id_almacen_calificacion = this.objAlamacen.id_almacen_calificacion;
    this.stockUsuario.fk_stock_usuario_almacen_usuario_id = this.objUsuario.id_usuario;
    if (parseInt(this.objAlamacen.cantidad_menos_stock) > 0) {
      if (parseInt(this.objAlamacen.cantidad_menos_stock) <= parseInt(this.objAlamacen.total_cantidad_almacen_calificacion)) {
        this.objAlamacen.total_cantidad_almacen_calificacion = (parseInt(this.objAlamacen.total_cantidad_almacen_calificacion) - parseInt(this.objAlamacen.cantidad_menos_stock));
        swal({
          title: 'Esta seguro de agregar esta nueva cantidad',
          text: this.objAlamacen.cantidad_menos_stock,
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, lo estoy'
        }).then((result) => {
          $("#loaderUsuario").show();
          if (result.value) {
            this._AlmacenService.nuevaCantidadUsuario(this.token, this.objAlamacen, this.stockUsuario).subscribe(
              respuesta => {
                this._ElementService.pi_poValidarCodigo(respuesta);
                if (respuesta.status == 'success'){
                  this.listarAlmacen(this.objUsuario);
                  this.listarStockUsuario();
                }else
                {
                  this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
                }
              }, error2 => {

              }
            )

          } else {
            this.objAlamacen.total_cantidad_almacen_calificacion = (parseInt(this.objAlamacen.total_cantidad_almacen_calificacion) + parseInt(this.objAlamacen.cantidad_menos_stock));
            this.nuevaCantidad = 0;
          }
        });
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El valor de la nueva cantidad supera la cantidad actual');
      }

    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El valor de la nueva cantidad debe ser mayor a 0');
    }

  }
  cargarStockUsuario(usuario){
    this.objUsuario= usuario;
  this.listarStockUsuario()
  }
  listarStockUsuario(){
    this._AlmacenService.listarStockUsuario(this.token,this.objUsuario.id_usuario).subscribe(
      respuesta=>{
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success'){
          if (respuesta.data != 0){
            this.listStockUsuario = respuesta.data;
          }else
          {
            this.listStockUsuario = [];
          }
        }else
        {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
        }
      },error2 => {

      }
    )
  }
}
