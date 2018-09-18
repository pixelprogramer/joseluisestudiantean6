import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-subir-archivo-resultados',
  templateUrl: './subir-archivo-resultados.component.html',
  styleUrls: ['./subir-archivo-resultados.component.scss'],
  providers: [ElementsService]
})
export class SubirArchivoResultadosComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('SubirArchivoResultadosComponent');
  }

}
