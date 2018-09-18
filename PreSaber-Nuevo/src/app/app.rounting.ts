import {ModuleWithProviders} from "@angular/core";
import {Routes, RouterModule} from "@angular/router";


import {CrearGruposComponent} from "./components/crear-grupos/crear-grupos.component";
import {TrasladoUsuarioComponent} from './components/traslado-usuario/traslado-usuario.component';
import {ComercialComponent} from "./components/pantallas/comercial/comercial.component";
import {LogisticaComponent} from "./components/pantallas/logistica/logistica.component";
import {CalificacionComponent} from "./components/pantallas/calificacion/calificacion.component";
import {PremarcadoComponent} from "./components/pantallas/premarcado/premarcado.component";
import {UnificacionUsuariosComponent} from "./components/calificacion/unificacion-usuarios/unificacion-usuarios.component";
import {LoginComponent} from "./components/login/login.component";
import {HomeAdminSistemasComponent} from "./components/administradorSistemas/home-admin-sistemas/home-admin-sistemas.component";
import {CrearUsuariosComponent} from "./components/administradorSistemas/crear-usuarios/crear-usuarios.component";
import {CrearPermisosComponent} from "./components/administradorSistemas/crear-permisos/crear-permisos.component";
import {CrearMenuComponent} from "./components/administradorSistemas/crear-menu/crear-menu.component";
import {AdministrarMenuComponent} from "./components/administradorSistemas/administrar-menu/administrar-menu.component";
import {HomeAdministradorCalificacionComponent} from "./components/calificacion/home-administrador-calificacion/home-administrador-calificacion.component";
import {HomeCalificacionComponent} from "./components/calificacion/home-calificacion/home-calificacion.component";
import {HomeAdministradorComercialComponent} from "./components/comercial/home-administrador-comercial/home-administrador-comercial.component";
import {HomeComercialComponent} from "./components/comercial/home-comercial/home-comercial.component";
import {HomeAdministradorLogisticaComponent} from "./components/logistica/home-administrador-logistica/home-administrador-logistica.component";
import {HomeLogisticaComponent} from "./components/logistica/home-logistica/home-logistica.component";
import {HomeAdministradorPremarcadoComponent} from "./components/premarcado/home-administrador-premarcado/home-administrador-premarcado.component";
import {HomePremarcadoComponent} from "./components/premarcado/home-premarcado/home-premarcado.component";
import {CrearCategoriaComponent} from "./components/minutas/crear-categoria/crear-categoria.component";
import {CrearAccionComponent} from "./components/minutas/crear-accion/crear-accion.component";
import {CrearSubCategoriasComponent} from "./components/minutas/crear-sub-categorias/crear-sub-categorias.component";
import {RegistrarMinutaComponent} from "./components/minutas/registrar-minuta/registrar-minuta.component";
import {GenerarReporteComponent} from "./components/minutas/generar-reporte/generar-reporte.component";
import {EditarColegioComponent} from "./components/calificacion/editar-colegio/editar-colegio.component";
import {CrearRolComponent} from "./components/administradorSistemas/crear-rol/crear-rol.component";
import {HomeDistrivuidorComponent} from "./components/distribuidor/home-distrivuidor/home-distrivuidor.component";
import {NuevoPedidoDistribuidorComponent} from "./components/distribuidor/nuevo-pedido-distribuidor/nuevo-pedido-distribuidor.component";
import {MisPedidosComponent} from "./components/distribuidor/mis-pedidos/mis-pedidos.component";
import {IndicadoresCalificacionComponent} from "./components/calificacion/indicadores-calificacion/indicadores-calificacion.component";
import {AlmacenCalificacionComponent} from "./components/calificacion/almacen-calificacion/almacen-calificacion.component";
import {Stock_usuario_almacen_calificacion} from "./models/almacen/stock_usuario_almacen_calificacion";
import {StockUsuarioComponent} from "./components/calificacion/stock-usuario/stock-usuario.component";
import {ValidarPaginaComponent} from "./components/validar-pagina/validar-pagina.component";
import {IndicadorCalificacionPremarcadoVsComponent} from "./components/comercial/indicador-calificacion-premarcado-vs/indicador-calificacion-premarcado-vs.component";
import {SubirArchivoResultadosComponent} from "./components/calificacion/subir-archivo-resultados/subir-archivo-resultados.component";


const appRoutes: Routes = [

  {path: '', component: ValidarPaginaComponent, pathMatch: 'full'},
  {path: '⚡/crearGrupos', component: CrearGruposComponent},
  {path: '📚/traslado/👪', component: TrasladoUsuarioComponent},
  {path: '⚡/unificar/👪', component: UnificacionUsuariosComponent},
  {path: '📊/📺', component: ComercialComponent},
  {path: '📦/📺', component: LogisticaComponent},
  {path: '📚/📺', component: CalificacionComponent},
  {path: '📝/📺', component: PremarcadoComponent},
  {path: '👑/💻/🏠', component: HomeAdminSistemasComponent},
  {path: '👑/💻/crear/👦', component: CrearUsuariosComponent},
  {path: '👑/💻/crearPermisos', component: CrearPermisosComponent},
  {path: '👑/💻/administrarMenu', component: AdministrarMenuComponent},
  {path: '👑/📚/🏠', component: HomeAdministradorCalificacionComponent},
  {path: '📚/🏠', component: HomeCalificacionComponent},
  {path: '👑/📚/🏠', component: HomeAdministradorCalificacionComponent},
  {path: '📊/🏠', component: HomeComercialComponent},
  {path: '👑/📦/🏠', component: HomeAdministradorLogisticaComponent},
  {path: '📦/🏠', component: HomeLogisticaComponent},
  {path: '👑/📝/🏠', component: HomeAdministradorPremarcadoComponent},
  {path: '📝/🏠', component: HomePremarcadoComponent},
  {path: '👑/💻/crearMenus', component: CrearMenuComponent},
  {path: '📈/⚡/✔', component: CrearCategoriaComponent},
  {path: '📈/⚡/crearAccion', component: CrearAccionComponent},
  {path: '📈/⚡/crearSubCategoriasMinutas', component: CrearSubCategoriasComponent},
  {path: '⚡/⏰', component: RegistrarMinutaComponent},
  {path: '📈/⚡/📰', component: GenerarReporteComponent},
  {path: '📚/🏠/👦', component: EditarColegioComponent},
  {path: '👑/nuevoRol', component: CrearRolComponent},
  {path: 'calificacion/indicadores', component: IndicadoresCalificacionComponent},
  {path: 'calificacion/almacenClificacion',component: AlmacenCalificacionComponent},
  {path: 'calificacion/almacen/stockUsuario',component: StockUsuarioComponent},
  {path: 'calificacion/SubirArchivos',component: SubirArchivoResultadosComponent},
  {path: 'comercial/indicadores/calificacionvspremarcado',component: IndicadorCalificacionPremarcadoVsComponent},
  //Rutas distribuidor
  {path: 'home/distribuidor', component: HomeDistrivuidorComponent},
  {path: 'distribuidor/RealizarNuevoPedido', component: NuevoPedidoDistribuidorComponent},
  {path: 'distribuidor/misPedidos', component: MisPedidosComponent},
  {path: 'login', component: LoginComponent},
  {path: '**', component: ValidarPaginaComponent}

];

export const appRoutingProviders: any[] = [];
export const routing: ModuleWithProviders = RouterModule.forRoot(appRoutes);
