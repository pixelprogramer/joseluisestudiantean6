import {NgModule} from '@angular/core';
import {CommonModule} from '@angular/common';

import 'd3';
import 'nvd3';

import {MenuItems} from './menu-items/menu-items';
import {AccordionAnchorDirective, AccordionLinkDirective, AccordionDirective} from './accordion';
import {ToggleFullscreenDirective} from './fullscreen/toggle-fullscreen.directive';
import {CardRefreshDirective} from './card/card-refresh.directive';
import {CardToggleDirective} from './card/card-toggle.directive';
import {CardComponent} from './card/card.component';
import {NgbModule} from '@ng-bootstrap/ng-bootstrap';
import {ParentRemoveDirective} from './elements/parent-remove.directive';
import {FormsModule, ReactiveFormsModule} from '@angular/forms';
import {ModalAnimationComponent} from './modal-animation/modal-animation.component';
import {ModalBasicComponent} from './modal-basic/modal-basic.component';
import {ToastyModule} from 'ng2-toasty';
import {SimpleNotificationsModule} from 'angular2-notifications';
import {TagInputModule} from 'ngx-chips';
import {AnimatorModule} from 'css-animator';
import {ColorPickerModule} from 'ngx-color-picker';
import {CurrencyMaskModule} from 'ng2-currency-mask';
import {SelectModule} from 'ng-select';
import {SelectOptionService} from './elements/select-option.service';
import {FormWizardModule} from 'angular2-wizard';
import {NgxDatatableModule} from '@swimlane/ngx-datatable';
import {DataFilterPipe} from './elements/data-filter.pipe';
import {DataTableModule} from 'angular2-datatable';
import {FroalaEditorModule, FroalaViewModule} from 'angular-froala-wysiwyg';
import {FileUploadModule} from 'ng2-file-upload';
import {ScrollToModule} from '@nicky-lenaers/ngx-scroll-to';
import {AgmCoreModule} from '@agm/core';
import {Ng2GoogleChartsModule} from 'ng2-google-charts';
import {NgxEchartsModule} from 'ngx-echarts';
import {UiSwitchModule} from 'ng2-ui-switch/dist';
import {ChartModule} from 'angular2-chartjs';
import {KnobModule} from 'ng2-knob';
import {NvD3Module} from 'ng2-nvd3';
import {TodoService} from './todo/todo.service';
import {ClickOutsideModule} from 'ng-click-outside';
import {SpinnerComponent} from './spinner/spinner.component';
import {PERFECT_SCROLLBAR_CONFIG, PerfectScrollbarConfigInterface, PerfectScrollbarModule} from 'ngx-perfect-scrollbar';
import {NotificationsService} from 'angular2-notifications';
import {ChartistModule} from 'ng-chartist';
import {appRoutingProviders, routing} from "../app.rounting";
import {AppComponent} from "../app.component";
import {LogisticaComponent} from "../components/pantallas/logistica/logistica.component";
import {HomeAdministradorLogisticaComponent} from "../components/logistica/home-administrador-logistica/home-administrador-logistica.component";
import {HomeDistrivuidorComponent} from "../components/distribuidor/home-distrivuidor/home-distrivuidor.component";
import {StockUsuarioComponent} from "../components/calificacion/stock-usuario/stock-usuario.component";
import {RegistrarMinutaComponent} from "../components/minutas/registrar-minuta/registrar-minuta.component";
import {HomeComercialComponent} from "../components/comercial/home-comercial/home-comercial.component";
import {HomeAdministradorPremarcadoComponent} from "../components/premarcado/home-administrador-premarcado/home-administrador-premarcado.component";
import {CrearSubCategoriasComponent} from "../components/minutas/crear-sub-categorias/crear-sub-categorias.component";
import {CrearUsuariosComponent} from "../components/administradorSistemas/crear-usuarios/crear-usuarios.component";
import {HomeCalificacionComponent} from "../components/calificacion/home-calificacion/home-calificacion.component";
import {HomeLogisticaComponent} from "../components/logistica/home-logistica/home-logistica.component";
import {IndicadoresCalificacionComponent} from "../components/calificacion/indicadores-calificacion/indicadores-calificacion.component";
import {UnificacionUsuariosComponent} from "../components/calificacion/unificacion-usuarios/unificacion-usuarios.component";
import {AlmacenCalificacionComponent} from "../components/calificacion/almacen-calificacion/almacen-calificacion.component";
import {CrearRolComponent} from "../components/administradorSistemas/crear-rol/crear-rol.component";
import {CalificacionComponent} from "../components/pantallas/calificacion/calificacion.component";
import {LoginComponent} from "../components/login/login.component";
import {TrasladoUsuarioComponent} from "../components/traslado-usuario/traslado-usuario.component";
import {NuevoPedidoDistribuidorComponent} from "../components/distribuidor/nuevo-pedido-distribuidor/nuevo-pedido-distribuidor.component";
import {HomePremarcadoComponent} from "../components/premarcado/home-premarcado/home-premarcado.component";
import {ValidarPaginaComponent} from "../components/validar-pagina/validar-pagina.component";
import {EditarColegioComponent} from "../components/calificacion/editar-colegio/editar-colegio.component";
import {SafeHtmlPipe} from "../services/tanfromarHtml";
import {CrearPermisosComponent} from "../components/administradorSistemas/crear-permisos/crear-permisos.component";
import {AdministrarMenuComponent} from "../components/administradorSistemas/administrar-menu/administrar-menu.component";
import {CrearMenuComponent} from "../components/administradorSistemas/crear-menu/crear-menu.component";
import {IndicadorCalificacionPremarcadoVsComponent} from "../components/comercial/indicador-calificacion-premarcado-vs/indicador-calificacion-premarcado-vs.component";
import {CrearCategoriaComponent} from "../components/minutas/crear-categoria/crear-categoria.component";
import {CrearGruposComponent} from "../components/crear-grupos/crear-grupos.component";
import {CrearAccionComponent} from "../components/minutas/crear-accion/crear-accion.component";
import {HomeAdministradorCalificacionComponent} from "../components/calificacion/home-administrador-calificacion/home-administrador-calificacion.component";
import {GenerarReporteComponent} from "../components/minutas/generar-reporte/generar-reporte.component";
import {PremarcadoComponent} from "../components/pantallas/premarcado/premarcado.component";
import {HomeAdminSistemasComponent} from "../components/administradorSistemas/home-admin-sistemas/home-admin-sistemas.component";
import {ComercialComponent} from "../components/pantallas/comercial/comercial.component";
import {MisPedidosComponent} from "../components/distribuidor/mis-pedidos/mis-pedidos.component";
import {HomeAdministradorComercialComponent} from "../components/comercial/home-administrador-comercial/home-administrador-comercial.component";
import {ServiceWorkerModule} from "@angular/service-worker";
import {environment} from "../../environments/environment";


const DEFAULT_PERFECT_SCROLLBAR_CONFIG: PerfectScrollbarConfigInterface = {
  suppressScrollX: true
};

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    NgbModule.forRoot(),
    ToastyModule.forRoot(),
    SimpleNotificationsModule.forRoot(),
    TagInputModule,
    UiSwitchModule,
    AnimatorModule,
    ColorPickerModule,
    SelectModule,
    CurrencyMaskModule,
    FormWizardModule,
    NgxDatatableModule,
    routing,
    DataTableModule,
    FroalaEditorModule.forRoot(),
    FroalaViewModule.forRoot(),
    FileUploadModule,
    ScrollToModule.forRoot(),
    AgmCoreModule.forRoot({apiKey: 'AIzaSyCE0nvTeHBsiQIrbpMVTe489_O5mwyqofk'}),
    ServiceWorkerModule.register('ngsw-worker.js', { enabled: environment.production }),
    Ng2GoogleChartsModule,
    NgxEchartsModule,
    ChartModule,
    KnobModule,
    NvD3Module,
    ClickOutsideModule,
    PerfectScrollbarModule,
    ChartistModule
  ],
  declarations: [
    AccordionAnchorDirective,
    AccordionLinkDirective,
    AccordionDirective,
    ToggleFullscreenDirective,
    CardRefreshDirective,
    CardToggleDirective,
    ParentRemoveDirective,
    CardComponent,
    SpinnerComponent,
    ModalAnimationComponent,
    ModalBasicComponent,
    DataFilterPipe,
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
  exports: [
    AccordionAnchorDirective,
    AccordionLinkDirective,
    AccordionDirective,
    ToggleFullscreenDirective,
    CardRefreshDirective,
    CardToggleDirective,
    ParentRemoveDirective,
    CardComponent,
    SpinnerComponent,
    NgbModule,
    FormsModule,
    ReactiveFormsModule,
    ModalBasicComponent,
    ModalAnimationComponent,
    ToastyModule,
    SimpleNotificationsModule,
    TagInputModule,
    UiSwitchModule,
    AnimatorModule,
    ColorPickerModule,
    SelectModule,
    CurrencyMaskModule,
    FormWizardModule,
    NgxDatatableModule,
    DataTableModule,
    DataFilterPipe,
    FroalaEditorModule,
    FroalaViewModule,
    FileUploadModule,
    ScrollToModule,
    AgmCoreModule,
    Ng2GoogleChartsModule,
    NgxEchartsModule,
    ChartModule,
    KnobModule,
    NvD3Module,
    ClickOutsideModule,
    PerfectScrollbarModule,
    ChartistModule
  ],
  providers: [
    MenuItems,
    TodoService,
    appRoutingProviders,
    SelectOptionService,
    NotificationsService,
    {
      provide: PERFECT_SCROLLBAR_CONFIG,
      useValue: DEFAULT_PERFECT_SCROLLBAR_CONFIG
    }
  ]
})
export class SharedModule {
}
