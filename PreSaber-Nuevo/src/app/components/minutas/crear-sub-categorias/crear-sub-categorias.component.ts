import {Component, OnInit} from '@angular/core';
import {SubCategoriasService} from "../../../services/minutas/subCategorias.service";
import {ElementsService} from "../../../services/elements.service";
import {Categoria_minutas} from "../../../models/minutas/categoria_minutas";
import {Sub_categoria_minutas} from "../../../models/minutas/sub_categoria_minutas";
import {AccionesService} from "../../../services/minutas/acciones.service";
import {Acciones_minutas} from "../../../models/minutas/acciones_minutas";
import swal from "sweetalert2";

@Component({
  selector: 'app-crear-sub-categorias',
  templateUrl: './crear-sub-categorias.component.html',
  styleUrls: ['./crear-sub-categorias.component.scss'],
  providers: [SubCategoriasService, ElementsService, AccionesService]
})
export class CrearSubCategoriasComponent implements OnInit {
  public token: any;
  public listSubCategorias: Array<any>;
  public objCategoria: Categoria_minutas;
  public objSub_Categoria_Minutas: Sub_categoria_minutas;
  public listAcciones: Array<Acciones_minutas>;
  public objAcciones: Acciones_minutas;
  constructor(private _SubCategoriasService: SubCategoriasService,
              private _ElementService: ElementsService,
              private _AccionesService: AccionesService) {
    this.token = localStorage.getItem('token');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearSubCategoriasComponent');
    this.listarSubCategoria();
    this.objCategoria = new Categoria_minutas('', '', '');
    this.objSub_Categoria_Minutas = new Sub_categoria_minutas('', '',
      '', '', '', '', '');
    $("#loaderTablaAcciones").hide();
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

  seleccionarCategoria(cate) {
    this.objCategoria.descripcion_categoria_minutas = cate.descripcion_categoria_minutas;
    this.objCategoria.id_categoria_minutas = cate.id_categoria_minutas;
  }

  nuevaAccion() {
    $("#loaderTablaAcciones").show();
    this._AccionesService.listarAcciones(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listAcciones = respuesta.data;
          $("#loaderTablaAcciones").hide();
        } else {

        }
      }, error2 => {

      }
    )
  }

  agregarAccion(accion) {
    this.objSub_Categoria_Minutas.id_categoria_sub_menu_fk = this.objCategoria.id_categoria_minutas;
    this.objSub_Categoria_Minutas.id_accion_minutas_fk=accion.id_acciones_minutas;
    this._SubCategoriasService.añadirSubCategoria(this.objSub_Categoria_Minutas,this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listarSubCategoria();
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
        }
      }, error2 => {

      }
    )
  }
  seleccionarAcciones(accion)
  {
    this.objAcciones = accion;
    swal({
      title: 'LTE-000',
      text: 'Esta seguro de eliminar esta accion',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Eliminar'
    }).then((result) => {
      $("#loaderUsuario").show();
      if (result.value) {
        this._SubCategoriasService.eliminarSubCategoria(this.objAcciones,this.token).subscribe(
          respuesta => {
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success') {
              this.listarSubCategoria();
            } else {
              this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
            }
          }, error2 => {

          }
        )
      }
    });
  }
}
