import { Component, OnInit } from '@angular/core';
import {ElementsService} from "../../../services/elements.service";
//import {appRoutingProviders} from "../../../app.rounting";

@Component({
  selector: 'app-home-admin-sistemas',
  templateUrl: './home-admin-sistemas.component.html',
  styleUrls: ['./home-admin-sistemas.component.scss'],
  providers : [ElementsService]
})
export class HomeAdminSistemasComponent implements OnInit {

  constructor(private _ElementService: ElementsService) { }

  ngOnInit() {
    this._ElementService.pi_poValidarUsuario('HomeAdminSistemasComponent');
  }

}
