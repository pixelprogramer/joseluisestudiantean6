import {Component, OnInit} from '@angular/core';
import {Categoria_minutas} from "../../../models/minutas/categoria_minutas";
import {Acciones_minutas} from "../../../models/minutas/acciones_minutas";
import {Sub_categoria_minutas} from "../../../models/minutas/sub_categoria_minutas";
import {SubCategoriasService} from "../../../services/minutas/subCategorias.service";
import {AccionesService} from "../../../services/minutas/acciones.service";
import {ElementsService} from "../../../services/elements.service";
import {Registro_minutasService} from "../../../services/minutas/registro_minutas.service";
import {Detalle} from "../../../models/minutas/detalle";
import {Pedido} from "../../../models/minutas/pedido";
import swal from "sweetalert2";
import {Registro_minutas} from "../../../models/minutas/registro_minutas";
import {Pedido_minutas} from "../../../models/minutas/pedido_minutas";
import {Categoria_no_conformes} from "../../../models/noConformes/categoria_no_conformes";
import {No_conformes} from "../../../models/noConformes/no_conformes";
import * as moment from 'moment';
import {NgbDateParserFormatter} from "@ng-bootstrap/ng-bootstrap";


@Component({
  selector: 'app-registrar-minuta',
  templateUrl: './registrar-minuta.component.html',
  styleUrls: ['./registrar-minuta.component.scss'],
  providers: [SubCategoriasService, ElementsService, AccionesService, Registro_minutasService]
})
export class RegistrarMinutaComponent implements OnInit {
  public token: any;
  public listSubCategorias: Array<any>;
  public objCategoria: Categoria_minutas;
  public objSub_Categoria_Minutas: Sub_categoria_minutas;
  public listAcciones: Array<Acciones_minutas>;
  public listPedidoDetalle: Array<any>;
  public objAcciones: Acciones_minutas;
  public listRegistroMinutasxUsuario: Array<any>;
  public objPedido: Pedido;
  public listDetalle: Array<Detalle>;
  public listDetalleSeleccionados = [];
  public cantidadSeleccionados: any;
  public codigoFiltro: any;
  public objPedidoMinutas: Pedido_minutas;
  public objRegistroMinutas: Registro_minutas;
  public validacion: any;
  public objCategoriaNoConformes: Categoria_no_conformes;
  public objNoConformes: No_conformes;
  public listCategoriaNoConformmes: Array<Categoria_no_conformes>;
  public listNoConformes: Array<No_conformes>;
  position = "top-right";

  constructor(private _SubCategoriasService: SubCategoriasService,
              private _ElementService: ElementsService,
              private _AccionesService: AccionesService,
              private _RegistroMinutasService: Registro_minutasService,
              public parserFormatter: NgbDateParserFormatter,) {
    this.token = localStorage.getItem('token');
  }


  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('RegistrarMinutaComponent');
    this.listarSubCategoria();
    this.listarMinutasUsuarios();
    this.listarCategoriaNoConformes();
    this.objCategoria = new Categoria_minutas('', '', '');
    this.objSub_Categoria_Minutas = new Sub_categoria_minutas('', '',
      '', '', '', '', '');
    this.objAcciones = new Acciones_minutas('', '', '',
      '', '', '', '');
    this.objPedido = new Pedido('', '', '', '');
    this.objRegistroMinutas = new Registro_minutas('', '', '', '',
      '', '', '', '', '', '', '', '','');
    this.objPedidoMinutas = new Pedido_minutas('', '', '', '');
    this.objCategoriaNoConformes = new Categoria_no_conformes('000', '', '', '');
    this.objNoConformes = new No_conformes('000', '', '', '', '');
    $("#loaderTablaAcciones").hide();
    $("#loaderTablaPedido").hide();
    $("#noPermitido").hide();
    $("#noPermitidoInput").hide()
    $("#loaderTablaMinutas").hide();
    this.cantidadSeleccionados = 0;
    this.codigoFiltro = '';
    this.validacion = 0;
  }

  listarSubCategoria() {
    this._SubCategoriasService.listarsubCategoriasCategorias(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listSubCategorias = respuesta.data;
        } else {

        }
      }, error2 => {

      }
    )
  }

  seleccionarAcciones(accion) {
    if (accion.estado_acciones_minutas.toLowerCase() == 'activo') {
      this.objAcciones.id_acciones_minutas = accion.id_accion_minutas_fk;
      this.objAcciones.descripcion_acciones_minutas = accion.descripcion_acciones_minutas;
      this.objAcciones.estado_acciones_minutas = accion.estado_acciones_minutas;
      this.objAcciones.seleccion_pedido_accion = accion.seleccion_pedido_accion;
      if (this.objAcciones.seleccion_pedido_accion == '1' || this.objAcciones.seleccion_pedido_accion == true) {
        $("#noPermitido").hide();
        $("#noPermitidoInput").hide();
      } else {
        $("#noPermitido").show();
        $("#noPermitidoInput").show();
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, esta accion esta con un estado: INACTIVO');
    }


  }

  seleccionarCategoria(cate) {
    this.limpiarCampos();
    $("#btnIniciarMinuta").text('Iniciar Tarea');
    this.objCategoria.descripcion_categoria_minutas = cate.descripcion_categoria_minutas;
    this.objCategoria.id_categoria_minutas = cate.id_categoria_minutas;
    this.objCategoria.id_tipo_pedido_minutas_fk = cate.id_tipo_pedido_minutas_fk;
    this.listarPedidosDetalles();
  }

  listarMinutasUsuarios() {
    $("#loaderTablaMinutas").show();
    this._RegistroMinutasService.listarMinutasxUsuario(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);

        if (respuesta.status == 'success') {
          if (respuesta.data[0].registro == null) {
            this.validacion = 0
            $("#loaderTablaMinutas").hide();
          } else {
            this.validacion = 1;
            this.listRegistroMinutasxUsuario = respuesta.data;
            $("#loaderTablaMinutas").hide();
          }
          $("#loaderTablaMinutas").hide();
        } else {


        }
      }, error2 => {

      }
    )
  }

  listarPedidosDetalles() {
    $("#loaderTablaPedido").show();
    this._RegistroMinutasService.listarPedidosDetalles(this.objCategoria.id_tipo_pedido_minutas_fk, this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listPedidoDetalle = respuesta.data;
          $("#loaderTablaPedido").hide();
        } else {

        }
      }, error2 => {

      }
    )
  }

  seleccionarPedido(pedido) {
    console.log(pedido);
    this.objPedidoMinutas.codigo_pedido_minutas = pedido.pedido.code;
    if (pedido.pedido.id_order_type =='8') {
      this.objPedidoMinutas.descripcion_pedido_minutas = pedido.pedido.description + '-' + pedido.pedido.observations;
    } else {
      this.objPedidoMinutas.descripcion_pedido_minutas = pedido.pedido.description;
    }
    this.objPedidoMinutas.destribuidor_pedido_minutas = pedido.pedido.name;

    this.cantidadSeleccionados = 0;
    this.listDetalleSeleccionados = [];
    this.objPedido = pedido.pedido;
    if (pedido.detalle != 0) {
      this.listDetalle = pedido.detalle;
    } else {
      this.listDetalle = [];
    }
    this._ElementService.pi_poAlertaWarning('Seleccionaste el pedido con codigo: ' + pedido.pedido.code, 'LTE-000');
  }

  selectDetalle(detalle) {
    var validacion = 0;
    for (var i = 0; i < this.listDetalleSeleccionados.length; i++) {
      if (detalle.id.trim() == this.listDetalleSeleccionados[i]['id'].trim()) {
        validacion = 1;
        this.listDetalleSeleccionados.splice(i, 1);
        this.cantidadSeleccionados--;
      }
    }
    if (validacion == 0) {
      this.listDetalleSeleccionados.push({
        'id': detalle.id,
        'prod_description': detalle.prod_description,
        'description': detalle.description
      });
      this.cantidadSeleccionados++;
    }
  }

  cargarDetalle() {
    if (this.cantidadSeleccionados != 0) {
      this._ElementService.pi_poAlertaSuccess('Se seleccionaron: ' + this.cantidadSeleccionados + ' de forma correcta', 'LTE-000');
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se seleccionaron los detalles del pedido');
    }
  }

  filtrarPedido() {
    if (this.codigoFiltro.trim() != '') {
      var validacion = 0
      var arregloEspera = [];
      for (var i = 0; i < this.listPedidoDetalle.length; i++) {
        if (this.codigoFiltro.trim() == this.listPedidoDetalle[i].pedido.code.trim()) {
          arregloEspera.push(this.listPedidoDetalle[i]);
          validacion = 1;
        }
      }
      if (validacion == 1) {
        this.listPedidoDetalle = arregloEspera;
      } else {
        this._ElementService.pi_poAlertaMensaje('No se encontro resultados con el ccodigo: ' + this.codigoFiltro, 'LTE-000');
        this.listarPedidosDetalles();
      }
    } else {
      this._ElementService.pi_poAlertaError('Lo sentimos, el codigo es requerido para poder filtrar', 'LTE-000');
    }

  }

  iniciarTarea() {
    if (this.objAcciones.id_acciones_minutas != '') {
      if (this.objRegistroMinutas.estado_minuta == 'DETENIDA') {

        this._RegistroMinutasService.continuarMinuta(this.token, this.objRegistroMinutas).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarMinutasUsuarios();
              this.limpiarCamposTodos();
              // $("#btnIniciarMinuta").text('Iniciar Tarea');
              // this.metodoTiempoMuerto();
              this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
            } else {
              this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
            }
          }, error2 => {

          }
        )
      } else {
        if (this.objAcciones.seleccion_pedido_accion == '1' || this.objAcciones.seleccion_pedido_accion == true) {
          if (this.cantidadSeleccionados != 0) {
            swal({
              title: 'Esta seguro de iniciar la minuta?',
              text: 'Categoria seleccionada: ' + this.objCategoria.descripcion_categoria_minutas + ', Accion seleccionada: ' + this.objAcciones.descripcion_acciones_minutas + ',' +
              ' Codigo pedido: ' + this.objPedido.code + ', cantidad detalles: ' + this.cantidadSeleccionados,
              type: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Si, lo estoy'
            }).then((result) => {
              $("#loaderUsuario").show();
              if (result.value) {
                this._RegistroMinutasService.crearRegistroMinuta(this.token, this.objPedidoMinutas, this.objRegistroMinutas, this.listDetalleSeleccionados, this.objAcciones.id_acciones_minutas).subscribe(
                  respuesta => {
                    this._ElementService.pi_poValidarCodigo(respuesta);
                    if (respuesta.status == 'success') {
                      this.listarMinutasUsuarios();
                      this.limpiarCamposTodos();
                      //this.metodoTiempoMuerto();
                      this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
                    } else {
                      this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
                    }
                  }, error2 => {

                  }
                )
              }
            });
          } else {
            this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, No se seleccionaron los detalles del pedido');
          }
        } else {
          //Minuta sin pedidos
          swal({
            title: 'Esta seguro de iniciar la minuta?',
            text: 'Categoria seleccionada: ' + this.objCategoria.descripcion_categoria_minutas + ', Accion seleccionada: ' + this.objAcciones.descripcion_acciones_minutas,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, lo estoy'
          }).then((result) => {
            $("#loaderUsuario").show();
            if (result.value) {
              this._RegistroMinutasService.crearRegistroMinutaSimple(this.token, this.objAcciones.id_acciones_minutas,this.objRegistroMinutas.descripcion_minuta).subscribe(
                respuesta => {
                  this._ElementService.pi_poValidarCodigo(respuesta);
                  if (respuesta.status == 'success') {
                    this.listarMinutasUsuarios();
                    this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
                    //this.metodoTiempoMuerto();
                  } else {
                    this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
                  }
                }, error2 => {

                }
              )
            }
          });
        }
      }
    } else {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, Por favor seleccione una accion');
    }
  }

  seleccionarMinuta(mxu) {
    this.objRegistroMinutas.id_registro_minutas = mxu.registro.id_registro_minutas;
    this.objRegistroMinutas.id_pedido_minutas_fk = mxu.registro.id_pedido_minutas_fk;
    this.objRegistroMinutas.estado_minuta = mxu.registro.estado_minuta;
    this.objAcciones.id_acciones_minutas = mxu.registro.id_acciones_minutas
    this.objAcciones.descripcion_acciones_minutas = mxu.registro.descripcion_acciones_minutas;
    if (mxu.registro.estado_minuta != 'TERMINADOF') {
      if (this.objRegistroMinutas.estado_minuta == 'DETENIDA') {
        if (this.objAcciones.id_acciones_minutas != '') {
          if (this.objRegistroMinutas.estado_minuta == 'DETENIDA') {
            // $("#btnIniciarMinuta").text('Continuar Tarea');
            $("#noPermitido").show();
            $("#noPermitidoInput").show();
          } else {
            //$("#btnIniciarMinuta").val('Iniciar Tarea');
            $("#noPermitido").hide();
            $("#noPermitidoInput").hide();
          }
        } else {
          this._ElementService.pi_poVentanaAlertaWarning('LTE-000', 'Lo sentimos, selecciones una accion para continua');
        }
      }
    }
  }

  limpiarCampos() {
    this.objRegistroMinutas = new Registro_minutas('', '', '', '',
      '', '', '', '', '', '', '', '','');
  }

  limpiarCamposTodos() {
    this.objCategoria = new Categoria_minutas('', '', '');
    this.objSub_Categoria_Minutas = new Sub_categoria_minutas('', '',
      '', '', '', '', '');
    this.objAcciones = new Acciones_minutas('', '', '',
      '', '', '', '');
    this.objPedido = new Pedido('', '', '', '');
    this.objRegistroMinutas = new Registro_minutas('', '', '', '',
      '', '', '', '', '', '', '', '','');
    this.objPedidoMinutas = new Pedido_minutas('', '', '', '');
    this.objCategoriaNoConformes = new Categoria_no_conformes('000', '', '', '');
    this.objNoConformes = new No_conformes('000', '', '', '', '');
  }

  detenerMinuta() {
    if (this.objNoConformes.id != '000' && this.objCategoriaNoConformes.id != '000') {
      var descripcion = this.objRegistroMinutas.observacion_minuta;
      this.objRegistroMinutas.observacion_minuta = this.objNoConformes.id + '-' + descripcion;
      this._RegistroMinutasService.detenerMinuta(this.token, this.objRegistroMinutas).subscribe(
        respuesta => {
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success') {
            this.listarMinutasUsuarios();
            this.limpiarCamposTodos();
            //this.agregarTiempoMuertoLE();
            this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);

          } else {

          }
        }, error2 => {

        }
      )

    }
  }

  terminarMinuta() {
    swal({
      title: 'Esta seguro de terminar la minuta?',
      text: 'Al confirmar esta pregunta, no se podran revertir los cambios, Esta usted seguro',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si, lo estoy'
    }).then((result) => {
      $("#loaderUsuario").show();
      if (result.value) {
        this._RegistroMinutasService.terminarMinuta(this.token, this.objRegistroMinutas).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarMinutasUsuarios();
              this.limpiarCamposTodos();
              // this.agregarTiempoMuertoLE();
              this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
            } else {
                this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
            }
          }, error2 => {

          }
        )
      }
    });

  }

  listarCategoriaNoConformes() {
    this._RegistroMinutasService.listarCategoriaNoConformida().subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listCategoriaNoConformmes = respuesta.data;
        } else {

        }
      }, error2 => {

      }
    )
  }

  listarNoConformes() {
    this._RegistroMinutasService.listarNoConformida(this.objCategoriaNoConformes.id).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0) {
            this.listNoConformes = respuesta.data;
          } else {
            this.listNoConformes = [];
          }

        } else {

        }
      }, error2 => {

      }
    )
  }

  //Metodos tiempo muerto
  metodoTiempoMuerto() {
    var jsonTiempoMuerto = localStorage.getItem('tiempoMuerto');
    if (jsonTiempoMuerto != null) {
      var objTiempoMuerto = JSON.parse(jsonTiempoMuerto);
      if (this.validarIgualdaTiempoMuerto(objTiempoMuerto) == true) {
        this._RegistroMinutasService.calcularTiempoMuerto(objTiempoMuerto, this.token).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              localStorage.removeItem('tiempoMuerto');
            } else {

            }
          }, error2 => {

          }
        )
      } else {
        localStorage.removeItem('tiempoMuerto');
      }
    }
  }

  validarIgualdaTiempoMuerto(objTimepoP) {
    var fechaActual = moment(new Date()).format('Y-MM-DD');
    if (fechaActual == objTimepoP['fecha']) {
      return true;
    } else {
      return false;
    }

  }

  agregarTiempoMuertoLE() {
    var fecha = moment(new Date()).format('Y-MM-DD');
    var tiempo = moment(new Date()).format('Y-MM-DD H:mm:ss');
    var objTiempo = new Object({'fecha': fecha, 'tiempo': tiempo});
    localStorage.removeItem('tiempoMuerto');
    var json = JSON.stringify(objTiempo);
    localStorage.setItem('tiempoMuerto', json);
  }
}


