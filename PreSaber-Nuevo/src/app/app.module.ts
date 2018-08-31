import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import {SharedModule} from './shared/shared.module';
import {BrowserAnimationsModule} from '@angular/platform-browser/animations';

import {routing,appRoutingProviders} from "./app.rounting";
import {FormsModule} from "@angular/forms";
import {HttpClientModule} from "@angular/common/http";


import { AppComponent } from './app.component';
import {CKEditorModule} from "ng2-ckeditor";
import {SafeHtmlPipe} from "./services/tanfromarHtml";
import { TrasladoUsuarioComponent } from './components/traslado-usuario/traslado-usuario.component';
import { CrearGruposComponent } from './components/crear-grupos/crear-grupos.component';
import { ComercialComponent } from './components/pantallas/comercial/comercial.component';
import { LogisticaComponent } from './components/pantallas/logistica/logistica.component';
import { CalificacionComponent } from './components/pantallas/calificacion/calificacion.component';
import { PremarcadoComponent } from './components/pantallas/premarcado/premarcado.component';
import { UnificacionUsuariosComponent } from './components/calificacion/unificacion-usuarios/unificacion-usuarios.component';
import { LoginComponent } from './components/login/login.component';
import { HomeAdminSistemasComponent } from './components/administradorSistemas/home-admin-sistemas/home-admin-sistemas.component';
import { CrearUsuariosComponent } from './components/administradorSistemas/crear-usuarios/crear-usuarios.component';
import { CrearPermisosComponent } from './components/administradorSistemas/crear-permisos/crear-permisos.component';
import { CrearMenuComponent } from './components/administradorSistemas/crear-menu/crear-menu.component';
import { AdministrarMenuComponent } from './components/administradorSistemas/administrar-menu/administrar-menu.component';
import { HomeAdministradorCalificacionComponent } from './components/calificacion/home-administrador-calificacion/home-administrador-calificacion.component';
import { HomeCalificacionComponent } from './components/calificacion/home-calificacion/home-calificacion.component';
import { HomeAdministradorComercialComponent } from './components/comercial/home-administrador-comercial/home-administrador-comercial.component';
import { HomeComercialComponent } from './components/comercial/home-comercial/home-comercial.component';
import { HomeAdministradorLogisticaComponent } from './components/logistica/home-administrador-logistica/home-administrador-logistica.component';
import { HomeLogisticaComponent } from './components/logistica/home-logistica/home-logistica.component';
import { HomeAdministradorPremarcadoComponent } from './components/premarcado/home-administrador-premarcado/home-administrador-premarcado.component';
import { HomePremarcadoComponent } from './components/premarcado/home-premarcado/home-premarcado.component';
import { CrearCategoriaComponent } from './components/minutas/crear-categoria/crear-categoria.component';
import { CrearAccionComponent } from './components/minutas/crear-accion/crear-accion.component';
import { CrearSubCategoriasComponent } from './components/minutas/crear-sub-categorias/crear-sub-categorias.component';
import { RegistrarMinutaComponent } from './components/minutas/registrar-minuta/registrar-minuta.component';
import { GenerarReporteComponent } from './components/minutas/generar-reporte/generar-reporte.component';
import { EditarColegioComponent } from './components/calificacion/editar-colegio/editar-colegio.component';
import { CrearRolComponent } from './components/administradorSistemas/crear-rol/crear-rol.component';
import { HomeDistrivuidorComponent } from './components/distribuidor/home-distrivuidor/home-distrivuidor.component';
import { NuevoPedidoDistribuidorComponent } from './components/distribuidor/nuevo-pedido-distribuidor/nuevo-pedido-distribuidor.component';
import { MisPedidosComponent } from './components/distribuidor/mis-pedidos/mis-pedidos.component';
import { IndicadoresCalificacionComponent } from './components/calificacion/indicadores-calificacion/indicadores-calificacion.component';
import { AlmacenCalificacionComponent } from './components/calificacion/almacen-calificacion/almacen-calificacion.component';
import { StockUsuarioComponent } from './components/calificacion/stock-usuario/stock-usuario.component';
import {ValidarPaginaComponent} from "./components/validar-pagina/validar-pagina.component";
import { ServiceWorkerModule } from '@angular/service-worker';
import { environment } from '../environments/environment';
import { IndicadorCalificacionPremarcadoVsComponent } from './components/comercial/indicador-calificacion-premarcado-vs/indicador-calificacion-premarcado-vs.component';


@NgModule({
  declarations: [
    AppComponent,
    SafeHtmlPipe,
    TrasladoUsuarioComponent,
    CrearGruposComponent,
    ComercialComponent,
    LogisticaComponent,
    CalificacionComponent,
    PremarcadoComponent,
    UnificacionUsuariosComponent,
    LoginComponent,
    HomeAdminSistemasComponent,
    CrearUsuariosComponent,
    CrearPermisosComponent,
    CrearMenuComponent,
    AdministrarMenuComponent,
    HomeAdministradorCalificacionComponent,
    HomeCalificacionComponent,
    HomeAdministradorComercialComponent,
    HomeComercialComponent,
    HomeAdministradorLogisticaComponent,
    HomeLogisticaComponent,
    HomeAdministradorPremarcadoComponent,
    HomePremarcadoComponent,
    CrearCategoriaComponent,
    CrearAccionComponent,
    CrearSubCategoriasComponent,
    RegistrarMinutaComponent,
    GenerarReporteComponent,
    EditarColegioComponent,
    CrearRolComponent,
    HomeDistrivuidorComponent,
    NuevoPedidoDistribuidorComponent,
    MisPedidosComponent,
    IndicadoresCalificacionComponent,
    AlmacenCalificacionComponent,
    ValidarPaginaComponent,
    StockUsuarioComponent,
    IndicadorCalificacionPremarcadoVsComponent
  ],
  imports: [
    BrowserModule,
    BrowserAnimationsModule,
    SharedModule,
    routing,
    FormsModule,
    HttpClientModule,
    CKEditorModule,
    ServiceWorkerModule.register('ngsw-worker.js', { enabled: environment.production }),

  ],
  providers: [appRoutingProviders],
  bootstrap: [AppComponent]
})
export class AppModule { }
