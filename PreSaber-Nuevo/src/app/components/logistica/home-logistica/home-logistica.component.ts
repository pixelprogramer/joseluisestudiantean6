import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";

@Component({
  selector: 'app-home-logistica',
  templateUrl: './home-logistica.component.html',
  styleUrls: ['./home-logistica.component.scss'],
  providers: [ElementsService]
})
export class HomeLogisticaComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeLogisticaComponent')
  }

}
