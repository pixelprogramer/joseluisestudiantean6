import { Component, OnInit } from '@angular/core';
import {PantallasService} from "../../../services/pantallas/pantallas.service";
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-calificacion',
  templateUrl: './calificacion.component.html',
  styleUrls: ['./calificacion.component.scss'],
  providers:[PantallasService,ElementsService]
})
export class CalificacionComponent implements OnInit {

  public lista: Array<object>;
  public listaFinal=[];
  public cantidadFilas: any;
  public cantidadAcumulada: any;
  public intervalo: any;
  public hojaActual: any;
  public cantidadHojas: any;
  public cantidadEstudiantes: string;
  public cantidadColegios: string;
  constructor(private _PantallaService:PantallasService, private _ElementService: ElementsService) {
    this.cantidadFilas=15;
    this.cantidadAcumulada =0;
    this.hojaActual=0;
    this.cantidadHojas=0;
    this.cantidadEstudiantes='';
    this.cantidadColegios ='';
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('CalificacionComponent');
    $("#loaderInformacion").hide();
    this.listarResultados();

    this.intervalo = setInterval(() => {
      this.cambiarPantalla();
    }, 5000);

  }
  listarResultados()
  {
    this.listarCantidades();
    $("#loaderInformacion").show();
    this._PantallaService.listarPantallaCalificacion().subscribe(
      respuesta=>{
        this.lista = respuesta.data;
        let r = this.lista.length/this.cantidadFilas;
        this.cantidadHojas = Math.round(r);
        console.log(this.cantidadHojas);
        $("#loaderInformacion").hide();

      },error2 => {

      }
    )
  }
  listarCantidades()
  {
    this._PantallaService.cantidadColegioEstudianteCalificacion().subscribe(
      respuesta=>{
        this.cantidadEstudiantes = respuesta.data.cantidadEstudiantes;
        this.cantidadColegios = respuesta.data.cantidadColegios;
      },error2 => {

      }
    )
  }

  cambiarPantalla()
  {
    this.hojaActual++;
    this.cantidadAcumulada = this.cantidadAcumulada+this.cantidadFilas;
    if (this.cantidadAcumulada <= this.lista.length)
    {
      this.listaFinal=[];
      let contador =1;
      for(var n = this.cantidadAcumulada-this.cantidadFilas ; n<this.cantidadAcumulada; n++)
      {
        this.listaFinal.push(this.lista[n]);
        contador++;
      }
    }else
    {
      this.cantidadAcumulada=0;
      this.hojaActual=1;
      this.listarResultados();
    }

    console.log(this.listaFinal);
  }
}
