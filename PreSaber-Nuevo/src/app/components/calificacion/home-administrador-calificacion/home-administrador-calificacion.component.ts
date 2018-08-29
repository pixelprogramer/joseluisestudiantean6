import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-administrador-calificacion',
  templateUrl: './home-administrador-calificacion.component.html',
  styleUrls: ['./home-administrador-calificacion.component.scss'],
  providers: [ElementsService]
})
export class HomeAdministradorCalificacionComponent implements OnInit {

  constructor(private _ElementService :ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeAdministradorCalificacionComponent');
  }

}
