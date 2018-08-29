import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeAdministradorPremarcadoComponent } from './home-administrador-premarcado.component';

describe('HomeAdministradorPremarcadoComponent', () => {
  let component: HomeAdministradorPremarcadoComponent;
  let fixture: ComponentFixture<HomeAdministradorPremarcadoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeAdministradorPremarcadoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeAdministradorPremarcadoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
