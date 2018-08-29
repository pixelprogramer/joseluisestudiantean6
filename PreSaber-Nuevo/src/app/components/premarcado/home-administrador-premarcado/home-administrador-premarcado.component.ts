import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-administrador-premarcado',
  templateUrl: './home-administrador-premarcado.component.html',
  styleUrls: ['./home-administrador-premarcado.component.scss'],
  providers: [ElementsService],
})
export class HomeAdministradorPremarcadoComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeAdministradorPremarcadoComponent');
  }

}
