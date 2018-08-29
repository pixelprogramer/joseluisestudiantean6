import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {NuevoPedidoService} from "../../../services/distribuidor/nuevoPedido.service";
import {Orders} from "../../../models/distribuidor/orders";
import {Order_type} from "../../../models/distribuidor/order_type";
import {Cities} from "../../../models/distribuidor/cities";
import {Produtos} from "../../../models/distribuidor/produtos";
import {Order_detail} from "../../../models/distribuidor/order_detail";
import {Colegio} from "../../../models/distribuidor/colegio";
import {Shipping} from "../../../models/distribuidor/shipping";
import {Shipping_type} from "../../../models/distribuidor/shipping_type";
import swal from "sweetalert2";
import {NgbDateParserFormatter, NgbDatepickerConfig, NgbDateStruct} from "@ng-bootstrap/ng-bootstrap";
import {GLOBAL} from "../../../services/global";
import * as moment from 'moment';

@Component({
  selector: 'app-mis-pedidos',
  templateUrl: './mis-pedidos.component.html',
  styleUrls: ['./mis-pedidos.component.scss'],
  providers: [ElementsService, NuevoPedidoService]
})
export class MisPedidosComponent implements OnInit {
  public token: any;
  public objOrder: any;
  public listOrders: Array<any>;
  public listOrderDetails: Array<any>;


  public objColegio: Colegio;
  public listColegio: Array<Colegio>;
  public filtro: any;
  public filtroCiudad: any;
  public objOrderType: Order_type;
  public listOrderType: Array<Order_type>;
  public objShipping: Shipping;
  public listShipping: Array<Shipping>;
  public objShippingType: Shipping_type;
  public listShippingType: Array<Shipping_type>;
  public objCities: Cities;
  public listCities: Array<Cities>;
  public usuarioToken: any;
  public objProductos: Produtos;
  public listProductos: Array<Produtos>;
  public objOrderDetails: Order_detail;
  public filtroProducto: any;
  public listArchivos = [];
  public diasHabilesTexto: any;
  position = "bottom-right";
  fechaAplicacion: NgbDateStruct;
  //Archivo
  public archivos: Array<File>;
  public res: any;

  constructor(private _ElementService: ElementsService,
              private _NuevoPedidoService: NuevoPedidoService,
              public parserFormatter: NgbDateParserFormatter,
              public _config: NgbDatepickerConfig) {
    this.token = localStorage.getItem('token');
    this.objOrder = new Orders('', '', '', '', '',
      '', '', '', '', '', '', '', '',
      '', '', '', '', '', '',
      '', '', '', '', '', '', '', '', '', '', '');

    this.filtro = '';
    this.objColegio = new Colegio('', '', '', '', '', '', '', '');
    this.objOrderType = new Order_type('000', '', '');
    this.objShipping = new Shipping('000', '', '', '');
    this.objShippingType = new Shipping_type('000', '', '');
    this.objCities = new Cities('', '', '', '', '', '');
    this.objProductos = new Produtos('', '', '', '', '', '',
      '', '', '', '', '', '', '', '');
    this.objOrderDetails = new Order_detail('', '', '', '', '', '',
      '', '', '', '', '', '', '', '', '', '');
    this.usuarioToken = this._ElementService.getUserIdentity();
    this.filtroCiudad = '';
    this.filtroProducto = '';
    this.diasHabilesTexto = ''
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('MisPedidosComponent');
    this.listarOrders();
    $("#loaderTableDetallePedido").hide();
    $("#loaderTablaPedido").hide();
    $("#idSeccionEditarPedido").hide();

    $("#seccionColegios").hide();
    $("#loaderTablaColegios").hide();
    $("#seccionCiuda").hide();
    $("#loaderTablaCiudades").hide();
    $("#loaderProductos").hide();
    $("#seccionDetallePedido").hide();
    $("#loaderTableDetallePedidoModificar").hide();
    $("#seccionListas").hide();
    //$("#seccionDetallePedido").hide();
    this.listarOrderType();
    this.listarShippingType();
    this.listarShipping();
    this.listarProductos();
  }

  buscarColegio() {
    $("#idSeccionEditarPedido").toggle(500);
    $("#seccionColegios").toggle(600);
    this.listarColegios();
  }

  listarOrders() {
    $("#loaderTablaPedido").show();
    this._NuevoPedidoService.listarPedidosDistribuidor(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listOrders = respuesta.data;
            $("#loaderTablaPedido").hide();
          } else {
            this.listOrders = [];
            $("#loaderTablaPedido").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  seleccionarPedido(pedido) {
    this.fechaAplicacion = this.parserFormatter.parse(pedido.date_application);
    this.objOrder = pedido;
    this.objShippingType.id = this.objOrder.id_ship_type;
    this.objShipping.id = this.objOrder.id_ship;

    this.objColegio.id = this.objOrder.id_school;
    this.objColegio.description = this.objOrder.colegio;
    this.objColegio.dane = this.objOrder.dane;
    this.objColegio.direccion = this.objOrder.direccion_colegio;
    this.objColegio.phone = this.objOrder.telefono_colegio;
    this.objColegio.id_city = this.objOrder.id_ciudad_colegio;
    this.objColegio.ciudad = this.objOrder.ciudad_colegio;

    this.objCities.id = this.objOrder.id_city;
    this.objCities.description = this.objOrder.ciudad;
    this.objCities.id = this.objColegio.id_city;
    this.objCities.description = this.objColegio.ciudad;

    this.objOrderType.id = pedido.id_order_type;
    this.objOrderType.description = pedido.tipo_orden;
    this.objOrderDetails.id_order = this.objOrder.id;
    this.listarDetallesPedido();
  }

  listarDetallesPedido() {
    $("#loaderTableDetallePedido").show();
    $("#loaderTableDetallePedidoModificar").show();
    this._NuevoPedidoService.listarDetallePedido(this.objOrder.id, this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listOrderDetails = respuesta.data;
            $("#loaderTableDetallePedido").hide();
            $("#loaderTableDetallePedidoModificar").hide();
          } else {
            this.listOrderDetails = [];
            $("#loaderTableDetallePedido").hide();
            $("#loaderTableDetallePedidoModificar").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTableDetallePedido").hide();
          $("#loaderTableDetallePedidoModificar").hide();
        }
      }, error2 => {

      }
    )
  }

  seccionEditarPedido() {
    if (this.objOrder.id != '') {
      if (this.objOrder.state == 'DISTRIBUIDOR' || this.objOrder.state == 'ALISTAMIENTO') {
        $("#idSeccionPedido").toggle(500);
        $("#idSeccionEditarPedido").toggle(500);
        this.calcularFechaEnvio();
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'No se puede editar el pedido con codigo: ' + this.objOrder.code + ' por que su estado es: ' + this.objOrder.state);
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se ha seleccionado un pedido');
    }


  }

  regresarPedido() {
    $("#idSeccionEditarPedido").toggle(500);
    $("#idSeccionPedido").toggle(500);
  }

  //Editar pedido

  listarColegios() {
    $("#loaderTablaColegios").show();
    this._NuevoPedidoService.listarColegios(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listColegio = respuesta.data;
            $("#loaderTablaColegios").hide();
          } else {
            this.listColegio = [];
            $("#loaderTablaColegios").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaColegios").hide();
        }
      }, error2 => {

      }
    )
  }

  filtrarColegio() {
    if (this.filtro.trim() != '') {
      this._NuevoPedidoService.filtrarColegio(this.filtro, this.token).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listColegio = respuesta.data;
              $("#loaderTablaColegios").hide();
            } else {
              this.listarColegios();
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se encontraron resultados');
              $("#loaderTablaColegios").hide();
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            $("#loaderTablaColegios").hide();
          }
        }, error2 => {

        }
      )
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El campo filtro es requerido');
    }
  }

  seleccionarColegio(colegios) {
    this.objColegio = colegios;
    this.objOrder.id_school = this.objColegio.id;
    $("#seccionColegios").toggle(500);
    $("#idSeccionEditarPedido").toggle(600);
    this._ElementService.pi_poAlertaMensaje('Se selecciono el colegio con codigo dane: ' + this.objColegio.dane, 'LTE-000');
  }

  listarOrderType() {
    this._NuevoPedidoService.listarOrderType(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listOrderType = respuesta.data;
            $("#loaderTablaColegios").hide();
          } else {
            this.listOrderType = [];
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaColegios").hide();
        }
      }, error2 => {

      }
    )
  }

  listarShipping() {
    this._NuevoPedidoService.listarDistribuidoras(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listShipping = respuesta.data;
            $("#loaderTablaColegios").hide();
          } else {
            this.listShipping = [];
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaColegios").hide();
        }
      }, error2 => {

      }
    )
  }

  listarShippingType() {
    this._NuevoPedidoService.listarShippingType(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listShippingType = respuesta.data;
            $("#loaderTablaColegios").hide();
          } else {
            this.listShippingType = [];
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaColegios").hide();
        }
      }, error2 => {

      }
    )
  }

  listarCiudades() {
    $("#loaderTablaCiudades").show();
    this._NuevoPedidoService.listarCiduades(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listCities = respuesta.data;
            $("#loaderTablaCiudades").hide();
          } else {
            this.listCities = [];
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderTablaCiudades").hide();
        }
      }, error2 => {

      }
    )
  }

  cargarEnvio(tipo) {
    this.objOrder.id_userperorder = this.usuarioToken.id_usuario_joseluis;
    if (tipo == 1) {
      this.objOrder.ship_name = this.usuarioToken.nombre + ' ' + this.usuarioToken.apellido;
      this.objOrder.ship_to = this.usuarioToken.nombre + ' ' + this.usuarioToken.apellido;
      this.objOrder.ship_phone = this.usuarioToken.telefono;
      this.objOrder.ship_address = this.usuarioToken.direccion;
      this.objCities.id = '';
      this.objCities.description = '';
      this.objOrder.id_city = '';
      this._ElementService.pi_poBotonHabilitar('#camCitis');
      this._ElementService.pi_poBotonHabilitar('#btnBuscarCitis');
    } else if (tipo == 2) {
      if (this.objColegio.id != '') {
        this.objOrder.id_city = this.objColegio.id_city;
        this.objOrder.ship_name = this.objColegio.description;
        this.objOrder.ship_to = this.objColegio.description;
        this.objOrder.ship_address = this.objColegio.direccion;
        this.objOrder.ship_phone = this.objColegio.phone;
        this.objCities.id = this.objColegio.id_city;
        this.objCities.description = this.objColegio.ciudad;
        this._ElementService.pi_poBontonDesabilitar('#camCitis');
        this._ElementService.pi_poBontonDesabilitar('#btnBuscarCitis');
      } else {
        this._ElementService.pi_poAlertaError('Lo sentimos, no se ha seleccionado un colegio', 'LTE-000');
      }

    } else {
      this.objOrder.ship_name = 'Otros';
      this.objOrder.ship_address = '';
      this.objOrder.ship_phone = '';
      this.objCities.id = '';
      this.objCities.description = '';
      this.objOrder.ship_to = '';
      this.objOrder.id_city = '';
      this._ElementService.pi_poBotonHabilitar('#camCitis');
      this._ElementService.pi_poBotonHabilitar('#btnBuscarCitis');
    }
  }

  buscarCiudad() {
    this.listarCiudades();
    $("#idSeccionEditarPedido").toggle(500);
    $("#seccionCiuda").toggle(600);
  }

  regresarPedidoCiudad() {
    $("#seccionCiuda").toggle(500);
    $("#idSeccionEditarPedido").toggle(600);
  }

  regresarPedidoEditar() {
    $("#seccionColegios").toggle(500);
    $("#idSeccionEditarPedido").toggle(600);
  }

  seleccionarCiudad(ciudad) {
    this.objCities = ciudad;
    this.objOrder.id_city = this.objCities.id;
    this._ElementService.pi_poAlertaMensaje('Se selecciono la ciudad: ' + this.objCities.description, 'LTE-000');
    $("#seccionCiuda").toggle(500);
    $("#idSeccionEditarPedido").toggle(600);
  }

  filtrarCiudad() {
    if (this.filtroCiudad.trim() != '') {
      this._NuevoPedidoService.filtrarCiudad(this.filtroCiudad, this.token).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listCities = respuesta.data;
              $("#loaderTablaCiudades").hide();
            } else {
              this.listarCiudades();
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se encontraron resultados');
              $("#loaderTablaCiudades").hide();
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            $("#loaderTablaCiudades").hide();
          }
        }, error2 => {

        }
      )
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El campo filtro es requerido');
    }
  }

  actualizarPedido() {
    this.objOrder.date_application = this.parserFormatter.format(this.fechaAplicacion);
    this.objOrder.id_order_type = this.objOrderType.id;
    this.objOrder.id_ship = this.objShipping.id;
    this.objOrder.id_ship_type = this.objShippingType.id;
    if (this.objOrder.id_school != '') {
      if (this.objOrder.id_order_type != '000') {
        if (this.objOrder.material != '000') {
          if (this.objOrder.id_ship != '000') {
            if (this.objOrder.id_ship_type != '000') {
              if (this.fechaAplicacion != undefined) {
                if (this.objOrder.ship_name != '') {
                  if (this.objOrder.ship_address != '') {
                    if (this.objOrder.ship_phone != '') {
                      if (this.objOrder.ship_to != '') {
                        if (this.objOrder.id_city != '') {
                          swal({
                            title: 'Esta seguro de hacer el pedido',
                            text: 'Dane: ' + this.objColegio.dane + ', Colegio: ' + this.objColegio.description + ', Telefono: ' + this.objColegio.phone,
                            type: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Si, lo estoy realizar'
                          }).then((result) => {
                            $("#loaderUsuario").show();
                            if (result.value) {
                              this._NuevoPedidoService.actualizarPedido(this.token, this.objOrder).subscribe(
                                respuesta => {
                                  this._ElementService.pi_poValidarCodigo(respuesta);
                                  if (respuesta.status == 'success') {
                                    this.recargarPedidoSeleccionado();
                                    this.limpiarCampos();
                                    this.listarOrders();
                                    $("#idSeccionPedido").toggle(500);
                                    $("#idSeccionEditarPedido").toggle(600);
                                    this._ElementService.pi_poVentanaAlerta(respuesta.code, respuesta.msg);
                                  } else {
                                    this.limpiarCampos();
                                    this.listarOrders();
                                    $("#idSeccionPedido").toggle(500);
                                    $("#idSeccionEditarPedido").toggle(600);
                                    this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
                                  }
                                }, error2 => {

                                }
                              )
                            }
                          });
                        } else {
                          this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se selecciono la ciudad');
                        }
                      } else {
                        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, Enviado a, es requerido');
                      }
                    } else {
                      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, el telefono es requerido');
                    }
                  } else {
                    this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, la direccion es requerida');
                  }
                } else {
                  this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El envio es requerido');
                }
              } else {
                this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, La fecha de aplicacion es requerida');
              }
            } else {
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se ha seleccionado el tipo de envio');
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se ha seleccionado la transportadora');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se ha seleccionado el material');
        }
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El tipo no a sido seleccionado');
      }
    } else
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se ha seleccionado el colegio');
  }

  listarProductos() {
    $("#loaderProductos").show();
    this._NuevoPedidoService.listarProductos(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listProductos = respuesta.data;
            $("#loaderProductos").hide();
          } else {
            this.listProductos = [];
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
          $("#loaderProductos").hide();
        }
      }, error2 => {

      }
    )
  }

  limpiarCampos() {
    this.filtro = '';
    this.objColegio = new Colegio('', '', '', '', '', '', '', '');
    this.objOrderType = new Order_type('000', '', '');
    this.objShipping = new Shipping('000', '', '', '');
    this.objShippingType = new Shipping_type('000', '', '');
    this.objOrder = [];
    this.objOrder = new Orders('', '', '', '', '', '', '', '',
      '', '', '', '', '', '', '',
      '', '', '', '', '', '', '',
      '', '', '', '', '000', '', '', '');
    this.objCities = new Cities('', '', '', '', '', '');

    this.filtroCiudad = '';
    this.listOrderDetails = [];
  }

  agregarDetallePedidoSeccion() {
    $("#idSeccionPedido").toggle(500);
    $("#seccionDetallePedido").toggle(600);
  }

  regresarPedidoDetalle() {
    $("#seccionDetallePedido").toggle(500);
    $("#idSeccionPedido").toggle(600);
  }

  seleccionarProducto(productos) {
    this.objProductos = productos;
    this.objOrderDetails.id_product = this.objProductos.id;
    this._ElementService.pi_poAlertaMensaje('Se selecciono el producto: ' + this.objProductos.description, 'LTE-000');
    this._ElementService.pi_poBotonHabilitar('#btnAgregarDetallePedido');
    this._ElementService.pi_poBontonDesabilitar('#btnEditarDetallePedido');
    this._ElementService.pi_poBontonDesabilitar('#btnEliminarDetallePedido');
  }

  agregarDetalle() {
    if (this.objOrderDetails.description != '') {
      if (this.objOrderDetails.quantity_order != '') {
        this._NuevoPedidoService.nuevoDetalle(this.token, this.objOrderDetails).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarDetallesPedido();
              this.listarOrders();
              this.recargarPedidoSeleccionado();
              this._ElementService.pi_poVentanaAlerta(respuesta.code, respuesta.msg);
            } else {
              this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            }
          }, error2 => {

          }
        );
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, La cantidad es requerida');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El grado es requerido');
    }
  }

  filtrarProductos() {
    if (this.filtroProducto.trim() != '') {
      this._NuevoPedidoService.filtrarProducto(this.filtroProducto, this.token).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            if (respuesta.data != 0) {
              this.listProductos = respuesta.data;
              $("#loaderProductos").hide();
            } else {
              this.listarProductos();
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, no se encontraron resultados');
              $("#loaderProductos").hide();
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            $("#loaderProductos").hide();
          }
        }, error2 => {

        }
      )
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El campo filtro es requerido');
    }
  }

  seleccionarDetalleEditar(orderDetails) {
    this.objOrderDetails = orderDetails;
    this.objProductos.description = orderDetails.descripcion_producto;
    this._ElementService.pi_poAlertaMensaje('Se selecciono el detalle: ' + this.objOrderDetails.description, 'LTE-000');
    this._ElementService.pi_poBotonHabilitar('#btnEditarDetallePedido');
    this._ElementService.pi_poBotonHabilitar('#btnEliminarDetallePedido');
    this._ElementService.pi_poBontonDesabilitar('#btnAgregarDetallePedido');
  }

  editarDetallePedido() {
    if (this.objOrderDetails.description != '') {
      if (this.objOrderDetails.quantity_order != '') {
        this._NuevoPedidoService.editarDetallePedido(this.token, this.objOrderDetails).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarDetallesPedido();
              this._ElementService.pi_poVentanaAlerta(respuesta.code, respuesta.msg);
            } else {
              this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            }
          }, error2 => {

          }
        );
      } else {
        this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, La cantidad es requerida');
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El grado es requerido');
    }
  }

  eliminarDetallePedido() {
    swal({
      title: 'Esta seguro de eliminar el detalle',
      text: 'Se eliminara el detalle: ' + this.objProductos.description + ' con una cantidad total de: ' + this.objOrderDetails.quantity_order,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar ahora'
    }).then((result) => {
      $("#loaderUsuario").show();
      if (result.value) {
        this._NuevoPedidoService.eliminarDetallePedido(this.token, this.objOrderDetails).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarDetallesPedido();
              this.listarOrders();

              this._ElementService.pi_poVentanaAlerta(respuesta.code, respuesta.msg);
            } else {
              this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            }
          }, error2 => {

          }
        )
      }
    });
  }

  misLista() {
    $("#idSeccionPedido").toggle(500);
    $("#seccionListas").toggle(600);
    this.listArchivos = JSON.parse(this.objOrder.archivo_listas);
  }

  regresarPedidoLista() {
    $("#seccionListas").toggle(500);
    $("#idSeccionPedido").toggle(600);
  }

  descargarArchivo(archivo) {
    window.open(GLOBAL.urlFiles + archivo.rute, 'download');
  }

  nuevosArchivos() {
    this._NuevoPedidoService.nuevoArchivo(this.token, ['archivo'], this.archivos, this.objOrder).then(
      (resultado) => {
        this._ElementService.pi_poValidarCodigo(resultado);
        this.res = resultado;
        if (this.res.status == 'success') {
          this.archivos = new Array<File>();
          this.recargarPedidoSeleccionado();
          this._ElementService.pi_poVentanaAlerta(this.res.code, this.res.msg);
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(this.res.code, this.res.msg);
        }
      },
      (error) => {
        console.log(error);
      }
    )
  }

  cargarArchivos($event) {
    this.archivos = <Array<File>>$event.target.files;
    //this.archivosp.push(this.archivos[0]);
    for (var i = 0; i < this.archivos.length; i++) {
      let tipo = this.archivos[i].name.split('.');
      let extension = tipo.pop().toLowerCase();
      if (extension == 'png' || extension == 'jpg' || extension == 'gif') {
        this.archivos[i]['tipo'] = 'imagen';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'false';
        this.archivos[i]['id'] = i;
      } else if (extension == 'xls' || extension == 'xlsx') {
        this.archivos[i]['tipo'] = 'excel';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'true';
        this.archivos[i]['id'] = i;
      } else if (extension == 'docx' || extension == 'docm') {
        this.archivos[i]['tipo'] = 'word';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'true';
        this.archivos[i]['id'] = i;
      } else if (extension == 'pptx' || extension == 'ppt') {
        this.archivos[i]['tipo'] = 'powerpoint';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'false';
        this.archivos[i]['id'] = i;
      } else if (extension == 'pdf') {
        this.archivos[i]['tipo'] = 'pdf';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'true';
        this.archivos[i]['id'] = i;
      } else if (extension == 'txt') {
        this.archivos[i]['tipo'] = 'texto';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'false';
        this.archivos[i]['id'] = i;
      } else if (extension == 'rar') {
        this.archivos[i]['tipo'] = 'rar';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'false';
        this.archivos[i]['id'] = i;
      } else if (extension == 'exe') {
        this.archivos[i]['tipo'] = 'exe';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'false';
        this.archivos[i]['id'] = i;
      } else {
        this.archivos[i]['tipo'] = 'desconocido';
        this.archivos[i]['extension'] = extension;
        this.archivos[i]['estado'] = 'false';
        this.archivos[i]['id'] = i;
      }
    }
  }

  recargarPedidoSeleccionado() {
    this._NuevoPedidoService.recargarPedidoSeleccionado(this.token, this.objOrder.id).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.objOrder = respuesta.data[0];
            this.listArchivos = JSON.parse(this.objOrder.archivo_listas);
            console.log(this.listArchivos);
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, algo salio mal');
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }

  calcularFechaEnvio() {
    //var fecha = new Date(this.parserFormatter.format(this.fechaAplicacion))
    this.fechaAplicacion = null;
    this.objOrder.date_application = '';
    var fecha = new Date();
    fecha.setTime(fecha.getTime() + 24 * 60 * 60 * 1000);
    var i = 1;
    var diasHabiles = 0;
    if (this.objOrderType.id == 3) {
      diasHabiles = 5;
    } else if (this.objOrderType.id == 1 || this.objOrderType.id == 2) {
      diasHabiles = 2;
    } else if (this.objOrderType.id == 4) {
      diasHabiles = 10;
    } else {
      diasHabiles = 2;
    }
    this.diasHabilesTexto = ' - Dias habiles: ' + diasHabiles;
    while (i < diasHabiles) {
      fecha.setTime(fecha.getTime() + 24 * 60 * 60 * 1000);
      if (fecha.getDay() == 0 || fecha.getDay() == 6) {
        fecha.setTime(fecha.getTime() + 24 * 60 * 60 * 1000);
      } else {
        i++;
        //fecha.setTime(fecha.getTime() + 24 * 60 * 60 * 1000);
      }
      var fechaFormateada = moment(fecha).format('Y-MM-DD');
      var anoActual = fechaFormateada.split('-');
      var festivos = Array({
          'año': '2018',
          'mes': '01',
          'dia': '01'
        }, {
          'año': '2018',
          'mes': '01',
          'dia': '08'
        }, {
          'año': '2018',
          'mes': '03',
          'dia': '19'
        }, {
          'año': '2018',
          'mes': '03',
          'dia': '29'
        }, {
          'año': '2018',
          'mes': '03',
          'dia': '30'
        }, {
          'año': '2018',
          'mes': '05',
          'dia': '01'
        }, {
          'año': '2018',
          'mes': '05',
          'dia': '14'
        }, {
          'año': '2018',
          'mes': '06',
          'dia': '04'
        }, {
          'año': '2018',
          'mes': '06',
          'dia': '11'
        }, {
          'año': '2018',
          'mes': '07',
          'dia': '02'
        }, {
          'año': '2018',
          'mes': '07',
          'dia': '20'
        }, {
          'año': '2018',
          'mes': '08',
          'dia': '07'
        }, {
          'año': '2018',
          'mes': '08',
          'dia': '20'
        }, {
          'año': '2018',
          'mes': '10',
          'dia': '15'
        }, {
          'año': '2018',
          'mes': '11',
          'dia': '05'
        }, {
          'año': '2018',
          'mes': '11',
          'dia': '12'
        }, {
          'año': '2018',
          'mes': '12',
          'dia': '08'
        }, {
          'año': '2018',
          'mes': '12',
          'dia': '25'
        },


        {
          'año': '2019',
          'mes': '01',
          'dia': '01'
        }, {
          'año': '2019',
          'mes': '01',
          'dia': '07'
        }, {
          'año': '2019',
          'mes': '03',
          'dia': '25'
        }, {
          'año': '2019',
          'mes': '04',
          'dia': '18'
        }, {
          'año': '2019',
          'mes': '04',
          'dia': '19'
        }, {
          'año': '2019',
          'mes': '05',
          'dia': '01'
        }, {
          'año': '2019',
          'mes': '06',
          'dia': '03'
        }, {
          'año': '2019',
          'mes': '06',
          'dia': '24'
        }, {
          'año': '2019',
          'mes': '07',
          'dia': '01'
        }, {
          'año': '2019',
          'mes': '07',
          'dia': '20'
        }, {
          'año': '2019',
          'mes': '08',
          'dia': '07'
        }, {
          'año': '2019',
          'mes': '08',
          'dia': '19'
        }, {
          'año': '2019',
          'mes': '10',
          'dia': '14'
        }, {
          'año': '2019',
          'mes': '11',
          'dia': '04'
        }, {
          'año': '2019',
          'mes': '11',
          'dia': '11'
        }, {
          'año': '2019',
          'mes': '12',
          'dia': '08'
        }, {
          'año': '2019',
          'mes': '12',
          'dia': '25'
        });
      for (let j = 0; j < festivos.length; j++) {
        if (festivos[j]['año'] == anoActual[0]) {
          if (anoActual[1] == festivos[j]['mes']) {
            if (anoActual[2] == festivos[j]['dia']) {
              fecha.setTime(fecha.getTime() + 24 * 60 * 60 * 1000);
            }
          }
        }
      }
    }
    var fechaFinalFormateada = moment(fecha).format('Y-MM-DD');
    fecha.setTime(fecha.getTime() + 24 * 60 * 60 * 1000);
    var fechaCalendario = moment(fecha).format('Y-MM-DD');
    var arregloFechaFinal = fechaCalendario.split('-');
    this._config.minDate = {
      "year": parseInt(arregloFechaFinal[0]),
      "month": parseInt(arregloFechaFinal[1]),
      "day": parseInt(arregloFechaFinal[2])
    };
    this.objOrder.date_ship = fechaFinalFormateada;
  }

  validarFecha() {
    console.log('entro');
    var fechaActual = new Date();
    var fechaFormateada = moment(fechaActual).format('Y-MM-DD');
    var arregloFechaActual = fechaFormateada.split('-');
    var fechasSeleccionada = new Date(this.parserFormatter.format(this.fechaAplicacion));
    fechasSeleccionada.setTime(fechasSeleccionada.getTime() + 24 * 60 * 60 * 1000);
    var fechaSeleccionadaFormateada = moment(fechasSeleccionada).format('Y-MM-DD');
    var arregloFechaSeleccionada = fechaSeleccionadaFormateada.split('-');

    //Validar fecha de aplicacion
    var arregloFechaEnvio = this.objOrder.date_ship.split('-');
    if (arregloFechaSeleccionada[0] >= arregloFechaEnvio[0]) {
      if (arregloFechaActual[0] == arregloFechaEnvio[0]) {
        if (arregloFechaSeleccionada[1] >= arregloFechaEnvio[1]) {
          if (arregloFechaSeleccionada[2] > arregloFechaEnvio[2]) {
            if (arregloFechaSeleccionada[0] >= arregloFechaActual[0]) {
              if (arregloFechaActual[0] == arregloFechaSeleccionada[0]) {
                if (arregloFechaSeleccionada[1] >= arregloFechaActual[1]) {
                  if (arregloFechaSeleccionada[2] >= arregloFechaActual[2]) {


                  } else {
                    this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El dia debe ser supero o igual a: ' + arregloFechaActual[2]);
                    this.fechaAplicacion = null;
                  }
                } else {
                  this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El mes debe ser supero o igual a: ' + arregloFechaActual[1]);
                  this.fechaAplicacion = null;
                }
              }
            } else {
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, El año debe ser supero o igual a: ' + arregloFechaActual[0]);
              this.fechaAplicacion = null;
            }
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, la fecha de aplicacion: ' +
              this.parserFormatter.format(this.fechaAplicacion) + ' Debe ser mayor a la fecha de envio: ' + this.objOrder.date_ship + ' ' + this.diasHabilesTexto);
            this.fechaAplicacion = null;
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, la fecha de aplicacion: ' +
            this.parserFormatter.format(this.fechaAplicacion) + ' Debe ser mayor a la fecha de envio: ' + this.objOrder.date_ship + ' ' + this.diasHabilesTexto);
          this.fechaAplicacion = null;
        }
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, la fecha de aplicacion: ' +
        this.parserFormatter.format(this.fechaAplicacion) + ' Debe ser mayor a la fecha de envio: ' + this.objOrder.date_ship + ' ' + this.diasHabilesTexto);
      this.fechaAplicacion = null;
    }
  }

  eliminarArchivo(archivo, recurso) {
    console.log(archivo);
    swal({
      title: 'LTE-000',
      text: 'Esta seguro que desea eliminar el archivo: ' + archivo.name,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, Eliminar'
    }).then((result) => {
      if (result.value) {
        console.log(archivo.id);
        this._NuevoPedidoService.eliminarArchivos(this.token, this.objOrder, archivo.id).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.recargarPedidoSeleccionado();
            } else {
              this._ElementService.pi_poVentanaAlertaWarning(respuesta.msg, respuesta.code);
            }
          }, error2 => {

          }
        )
      }
    });
  }

}


