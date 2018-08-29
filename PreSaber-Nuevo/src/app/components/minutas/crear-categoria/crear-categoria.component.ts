import {Component, OnInit} from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import {Categoria_minutas} from "../../../models/minutas/categoria_minutas";
import {CategoriaService} from "../../../services/minutas/categoria.service";

@Component({
  selector: 'app-crear-categoria',
  templateUrl: './crear-categoria.component.html',
  styleUrls: ['./crear-categoria.component.scss'],
  providers: [ElementsService, CategoriaService]
})
export class CrearCategoriaComponent implements OnInit {
  public objCategoriaMinutas: Categoria_minutas;
  public token: any;
  public listTipoPedido: Array<any>;
  public listCategoria: Array<Categoria_minutas>;
  position = "top-right";

  constructor(private _ElementService: ElementsService,
              private _CategoriaService: CategoriaService) {
    this.token = localStorage.getItem('token');
    this.objCategoriaMinutas = new Categoria_minutas('', '', '');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CrearCategoriaComponent');
    this.listarCategoriaCrear();
    this.listarCategoria();
    $("#loaderTablaCategoria").hide();
    $("#loaderTablaCategoriaPropios").hide();
  }

  seleccionarTipoPedido(tp) {
    this.objCategoriaMinutas.id_tipo_pedido_minutas_fk = tp.id;
    this.objCategoriaMinutas.descripcion_categoria_minutas = tp.description;
    this._ElementService.pi_poAlertaSuccess('Se selecciono la categoria :' + this.objCategoriaMinutas.descripcion_categoria_minutas, 'LTE-000');
  }

  listarCategoriaCrear() {
    $("#loaderTablaCategoria").show();
    this._CategoriaService.listarCategoriaCrear(this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listTipoPedido = respuesta.data;
          $("#loaderTablaCategoria").hide();
        } else {

        }
      }, error2 => {

      }
    )
  }

  crearCategoria() {
    this._CategoriaService.crearCategoria(this.objCategoriaMinutas, this.token).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          this.listarCategoria()
          this._ElementService.pi_poAlertaSuccess(respuesta.msg, respuesta.code);
        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }
  listarCategoria()
  {
    $("#loaderTablaCategoriaPropios").show();
    this._CategoriaService.listarCategoria(this.token).subscribe(
      respuesta=>{
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success')
        {
          this.listCategoria = respuesta.data;
          $("#loaderTablaCategoriaPropios").hide();
        }else
        {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      },error2 => {

      }
    )
  }
  seleccionarCategoria(tpl){
    this.objCategoriaMinutas = tpl;
  }

}
