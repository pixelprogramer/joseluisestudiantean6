import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-administrador-comercial',
  templateUrl: './home-administrador-comercial.component.html',
  styleUrls: ['./home-administrador-comercial.component.scss'],
  providers: [ElementsService]
})
export class HomeAdministradorComercialComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeAdministradorComercialComponent');
  }

}
