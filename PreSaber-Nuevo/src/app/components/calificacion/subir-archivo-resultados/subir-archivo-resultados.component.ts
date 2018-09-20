import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
import { Subirresultado } from  "../../../models/sisCalificacion/subirresultado";
import { CalificacionService } from "../../../services/calificacion/calificacion.service";


@Component({
  selector: 'app-subir-archivo-resultados',
  templateUrl: './subir-archivo-resultados.component.html',
  styleUrls: ['./subir-archivo-resultados.component.scss'],
  providers: [ElementsService, CalificacionService]
})
export class SubirArchivoResultadosComponent implements OnInit {
public subirresultado : Subirresultado;

  constructor(
    private _ElementService: ElementsService,
    private _CalificacionServices: CalificacionService
    ) {
      this.subirresultado = new Subirresultado('', '', '', '', '', '','');
  }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('SubirArchivoResultadosComponent');

  }

  onSubmit(){

  }

  public filesToUpload;
  public resultUpload;
  fileChangeEvent(fileinput: any){
    this.filesToUpload=<Array<File>>fileinput.target.files;
    console.log(this.filesToUpload);
  }

}
