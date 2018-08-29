import { Component, OnInit } from '@angular/core';
import {LoginService} from "../../services/login.service";
import {Usuario} from "../../models/seguridad/usuario";
import {ElementsService} from "../../services/elements.service";
import {Router} from "@angular/router";
//import {appRoutingProviders} from "../../app.rounting";

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss'],
  providers: [LoginService]
})
export class LoginComponent implements OnInit {
  public ObjUser: Usuario;
  public userIdentity: any;
  position = "top-right";
  constructor(private _loginService:LoginService, private _ElementService: ElementsService,private _Router: Router,) {
    this.ObjUser =  new Usuario('','','','','','',
      '','','','','','');
  }

  ngOnInit() {
    this.userIdentity = this._ElementService.getUserIdentity();
  }
  login() {
    $('#btnIngresar').attr('disabled', 'disabled');
    $('#loader').show();
    this._loginService.login(this.ObjUser).subscribe(
      respuesta => {
        if (respuesta.status != 'error') {
          localStorage.setItem('token', respuesta.data);
          this._loginService.login(this.ObjUser, true).subscribe(
            respuesta1 => {
              localStorage.setItem('userIdentityltesoftware', JSON.stringify(respuesta1.data));
              $('#loader').hide();
              if (respuesta1 != null) {
                let posisicon = 0;
                let validacion=0;
                for (var i=0; i<respuesta1.data.permisos.length; i++)
                {
                  if (respuesta1.data.permisos[i].pagina_defecto == '1' || respuesta1.data.permisos[i].pagina_defecto == true)
                  {
                    validacion = 1;
                    posisicon = i;
                  }
                }
                if (validacion == 1)
                {
                  //this._Router.navigate(['']);
                  // this._Router.navigate(['/ðŸ‘‘/ðŸ’»/ðŸ ']);
                  window.location.href = '/joseluisestudiante/';
                   //this._Router.navigate([respuesta1.data.permisos[posisicon].ruta_menu]);
                 // this._Router.navigate(['/👑/💻/🏠']);
                 //window.location.href = respuesta1.data.permisos[posisicon].ruta_menu;
                }else
                {
                  this._ElementService.pi_poVentanaAlertaWarning('LTE-001','Lo sentimos, por favor comunicarse con el ' +
                    'Departamento de sistemas, No se encontro la pagina predeterminada');
                  localStorage.removeItem('token');
                  localStorage.removeItem('userIdentityltesoftware');
                  localStorage.removeItem('ficha');
                }

              }

            }, error2 => {

            }
          );
          $('#btnIngresar').removeAttr('disabled', 'disabled');
        } else {
          this._ElementService.pi_poAlertaError('Lo sentimos, Los datos ingresados son incorrectos','LTE-000');
          $('#btnIngresar').removeAttr('disabled', 'disabled');
        }

      }, error2 => {

      }
    );
  }
}
