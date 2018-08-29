import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeAdministradorComercialComponent } from './home-administrador-comercial.component';

describe('HomeAdministradorComercialComponent', () => {
  let component: HomeAdministradorComercialComponent;
  let fixture: ComponentFixture<HomeAdministradorComercialComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeAdministradorComercialComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeAdministradorComercialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
