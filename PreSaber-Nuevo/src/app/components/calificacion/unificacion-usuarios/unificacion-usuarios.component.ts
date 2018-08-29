import { Component, OnInit } from '@angular/core';
import {CalificacionService} from "../../../services/calificacion/calificacion.service";
import {ElementsService} from "../../../services/elements.service";
import swal from "sweetalert2";

@Component({
  selector: 'app-unificacion-usuarios',
  templateUrl: './unificacion-usuarios.component.html',
  styleUrls: ['./unificacion-usuarios.component.scss'],
  providers: [CalificacionService,ElementsService]
})
export class UnificacionUsuariosComponent implements OnInit {
  public token:any;
  public cadenaPaquetes: string;
  public cadenaEstudiantes: string;
  public listEstudiantes: Array<object>;
  public listEstudiantesSeleccionados= [];
  public cantidadSeleccionados: any;
  public filtroEstudiante:any;
  position = "top-right";
  constructor(private _CalificacionService:CalificacionService,
              private _ElementService: ElementsService) {
    this.token = localStorage.getItem('token');
    this.cadenaPaquetes = '';
    this.cadenaEstudiantes = '';
    this.cantidadSeleccionados=0;
    this.filtroEstudiante='';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('UnificacionUsuariosComponent');
    $("#loaderTablaEstudiante").hide();
  }
  listarEstudiantesPaquetes()
  {
    $("#loaderTablaEstudiante").show();
    if (this.cadenaPaquetes.trim() != '')
    {
      this._CalificacionService.listarEstudianteUnificacion(this.cadenaPaquetes).subscribe(
        respuesta=>{
          this.listEstudiantes=respuesta.data;
          $("#loaderTablaEstudiante").hide();
        },error2 => {

        }
      )
    }else
    {
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos los paquetes son necesarios');
    }
  }
  selectEstudiante(estudiante)
  {
    var validacion =0;
    for (var i=0; i<this.listEstudiantesSeleccionados.length; i++)
    {
      if (estudiante.calixestuidn.trim() == this.listEstudiantesSeleccionados[i].trim()){
        validacion=1;
        this.listEstudiantesSeleccionados.splice(i,1);
        this.cantidadSeleccionados--;
      }
    }
    if (validacion==0)
    {
      this.listEstudiantesSeleccionados.push(estudiante.calixestuidn);
      this.cantidadSeleccionados++;
    }
    console.log(this.cantidadSeleccionados);
  }
  unificarEstudiantes()
  {
    swal({
      title: 'Esta usted seguro de unificar?',
      text: 'Se seleccionaron '+this.listEstudiantesSeleccionados.length +' estudiantes.',
      type: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#3085d6',
      cancelButtonColor: '#d33',
      confirmButtonText: 'Si lo estoy, unificar ahora!'
    }).then((result) => {
      $("#loaderUsuario").show();
      if (result.value) {
        this.cadenaEstudiantes='';
        var coma =',';
        for (var i=0; i<this.listEstudiantesSeleccionados.length; i++)
        {
          if(this.cadenaEstudiantes != '')
          {
            this.cadenaEstudiantes+=coma;
          }
          this.cadenaEstudiantes+=this.listEstudiantesSeleccionados[i];
        }
        this._CalificacionService.unificarEstudiantes(this.cadenaEstudiantes,this.token).subscribe(
          respuesta=>{
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success')
            {
              this.listarEstudiantesPaquetes();
              this.listEstudiantesSeleccionados=[];
              this.cantidadSeleccionados=0;
              this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Por favor, Volver a calificar para ver reflejados los cambios');
            }

          },error2 => {

          }
        )
      }
    });
  }
  filtrarEstudiante(){
    if (this.filtroEstudiante.trim() != '' && this.cadenaPaquetes !='')
    {
        this._CalificacionService.filtrarEstudiantes(this.cadenaPaquetes,this.filtroEstudiante).subscribe(
          respuesta=>{
            this._ElementService.pi_poValidarCodigo(respuesta);
            if (respuesta.status == 'success')
            {
              if (respuesta.data !=0)
              {
                this.listEstudiantes=respuesta.data;
                this._ElementService.pi_poAlertaSuccess('Se encontraron resultados','LTE-000');
              }else
              {
                this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos, no se encontraron resultados');
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
      this._ElementService.pi_poVentanaAlertaWarning('LTE-000','Lo sentimos, El campo filtro y los paquetes son requeridos');
    }
  }
}
