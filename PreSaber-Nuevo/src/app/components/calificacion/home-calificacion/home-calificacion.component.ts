import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-calificacion',
  templateUrl: './home-calificacion.component.html',
  styleUrls: ['./home-calificacion.component.scss'],
  providers: [ElementsService]
})
export class HomeCalificacionComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeCalificacionComponent');
  }

}
