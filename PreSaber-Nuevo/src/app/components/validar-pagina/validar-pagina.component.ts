import { Component, OnInit } from '@angular/core';
import swal from "sweetalert2";
import {HttpClient} from "@angular/common/http";
import {Router} from "@angular/router";
import {ToastyService} from "ng2-toasty";
import {GLOBAL} from "../../services/global";
import {ElementsService} from "../../services/elements.service";

@Component({
  selector: 'app-validar-pagina',
  templateUrl: './validar-pagina.component.html',
  styleUrls: ['./validar-pagina.component.scss'],
  providers: [ElementsService]
})
export class ValidarPaginaComponent implements OnInit {

  public positon: string;
  public url: string;
  public user: any;
  public userIdentity: any;
  public home: string;

  constructor(public _http: HttpClient, private _Router: Router, private _ElementSrvice: ElementsService) {
    this.url = GLOBAL.url;
    this.positon='top-right';
  }

  ngOnInit() {
    this.pi_poValidarUsuario();
  }
  pi_poValidarUsuario() {
    this.userIdentity = this._ElementSrvice.getUserIdentity();
    let validacion = 0;
    if (this.userIdentity != null) {

        this.home = this.userIdentity.rol_descripcion;
        this._Router.navigate([this.userIdentity.permisos[0].ruta_menu]);
        let posisicon = 0;
        let validacion=0
        for (var i=0; i<this.userIdentity.permisos.length; i++)
        {
          if (this.userIdentity.permisos[i].pagina_defecto == '1' || this.userIdentity.permisos[i].pagina_defecto == true )
          {
            validacion = 1;
            posisicon = i;
          }
        }
        if (validacion == 1)
        {
          this._Router.navigate([this.userIdentity.permisos[posisicon].ruta_menu]);
        }
    } else {
      this._Router.navigate(['login']);
    }
  }
}
