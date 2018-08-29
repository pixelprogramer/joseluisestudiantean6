import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-comercial',
  templateUrl: './home-comercial.component.html',
  styleUrls: ['./home-comercial.component.scss'],
  providers: [ElementsService]
})
export class HomeComercialComponent implements OnInit {

  constructor(private _ElementService:ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeComercialComponent');
  }

}
