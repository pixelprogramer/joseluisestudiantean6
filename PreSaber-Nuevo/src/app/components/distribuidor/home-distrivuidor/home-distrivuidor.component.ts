import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-distrivuidor',
  templateUrl: './home-distrivuidor.component.html',
  styleUrls: ['./home-distrivuidor.component.scss'],
  providers: [ElementsService]
})
export class HomeDistrivuidorComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeDistrivuidorComponent');
  }

}
