import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeAdministradorCalificacionComponent } from './home-administrador-calificacion.component';

describe('HomeAdministradorCalificacionComponent', () => {
  let component: HomeAdministradorCalificacionComponent;
  let fixture: ComponentFixture<HomeAdministradorCalificacionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeAdministradorCalificacionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeAdministradorCalificacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
