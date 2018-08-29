import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-premarcado',
  templateUrl: './home-premarcado.component.html',
  styleUrls: ['./home-premarcado.component.scss'],
  providers: [ElementsService]
})
export class HomePremarcadoComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomePremarcadoComponent');
  }

}
