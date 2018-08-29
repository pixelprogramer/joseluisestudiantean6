import {Component, OnInit} from '@angular/core';
import {Variables} from "../../models/variables";
import {ElementsService} from "../../services/elements.service";
import {TrasladoUsuarioService} from "../../services/trasladoUsuario.service";
import swal from "sweetalert2";

@Component({
  selector: 'app-traslado-usuario',
  templateUrl: './traslado-usuario.component.html',
  styleUrls: ['./traslado-usuario.component.scss'],
  providers: [ElementsService, TrasladoUsuarioService]
})
export class TrasladoUsuarioComponent implements OnInit {
  public token: any;
  public listEstudiantes: Array<object>;
  public objTraslado: Variables;
  public listEstudiantesSeleccionados= [];
  public cantidadSeleccionados: any;
  position = "top-right";
  public filtroEstudiante: any;
  constructor(private _ElementService: ElementsService, private _TrasladoUsuarios: TrasladoUsuarioService) {
    this.token = localStorage.getItem('token');
    this.objTraslado = new Variables('', '', '', '','');
    this.cantidadSeleccionados=0;
    this.filtroEstudiante='';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('TrasladoUsuarioComponent');
    $("#loaderUsuario").hide();
  }

  cargarEstudiantes() {
    this._TrasladoUsuarios.listar(this.objTraslado).subscribe(
      respuesta => {
        this._ElementService.pi_poValidarCodigo(respuesta);
        if (respuesta.status == 'success') {
          if (respuesta.data != 0)
          {
            this.listEstudiantes = respuesta.data;
          }else
          {
            this.listEstudiantes = [];
          }

        } else {
          this._ElementService.pi_poVentanaAlertaWarning(respuesta.code, respuesta.msg);
        }
      }, error2 => {

      }
    )
  }


  cambiarUsuario()
  {
    if (this.listEstudiantesSeleccionados.length !=0)
    {
    swal({
      title: 'LTE-000',
      text: 'Esta seguro que desea cambiar el estudiante: '+this.objTraslado.nombre+' al paquete: '+this.objTraslado.caliidnnew,
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Cambiar'
    }).then((result) => {
      $("#loaderUsuario").show();
      if (result.value) {
        this._TrasladoUsuarios.actualizar(this.objTraslado,this.listEstudiantesSeleccionados,this.token).subscribe(
          resultado=>{
            this._ElementService.pi_poValidarCodigo(resultado);
            if (resultado.status == 'success')
            {
              this.cargarEstudiantes();
              $("#loaderUsuario").hide();
              this._ElementService.pi_poAlertaSuccess(resultado.msg,resultado.code);
              this.objTraslado.caliidnnew = '';
              this.objTraslado.calixestuidn = '';
              this.listEstudiantesSeleccionados =[];
            }else
            {
              this._ElementService.pi_poVentanaAlertaWarning(resultado.code,resultado.msg);
            }
          },error2 => {

          }
        )

      }

    });
    }else
    {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos, Debes seleccionar un estudiante');
    }
  }
  selectEstudiante(estudiante)
  {

      var validacion =0;
      for (var i=0; i<this.listEstudiantesSeleccionados.length; i++)
      {
        if (estudiante.calixestuidn.trim() == this.listEstudiantesSeleccionados[i]['calixestuidn'].trim()){
          validacion=1;
          this.listEstudiantesSeleccionados.splice(i,1);
          this.cantidadSeleccionados--;
        }
      }
      if (validacion==0)
      {
        this.listEstudiantesSeleccionados.push({'caliidn':estudiante.caliidn,'calixestuidn':estudiante.calixestuidn,'estunombrev':estudiante.estunombrev});
        this.cantidadSeleccionados++;
      }


  }
  filtrarEstudiante(){
    if (this.filtroEstudiante !='' || this.objTraslado.caliidn !='')
    {
      this._TrasladoUsuarios.filtrarUsuario(this.filtroEstudiante,this.objTraslado).subscribe(
        respuesta=>{
          this._ElementService.pi_poValidarCodigo(respuesta);
          if (respuesta.status == 'success')
          {
            if (respuesta.data !=0){
              this.listEstudiantes = respuesta.data;
              this._ElementService.pi_poAlertaSuccess('Se encontraron resultados','LTE-000');
            }else
            {
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos, no se encontraron resultados con: '+this.filtroEstudiante);
            }
          }else
          {
            this._ElementService.pi_poVentanaAlertaWarning(respuesta.code,respuesta.msg);
          }
        },error2 => {

        }
      )
    }else
    {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos, El paquete y el filtro son requeridos');
      if (this.objTraslado.caliidn !='')
      {
        this.cargarEstudiantes();
      }

    }
  }

}
