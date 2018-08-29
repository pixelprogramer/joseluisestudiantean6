import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeAdministradorLogisticaComponent } from './home-administrador-logistica.component';

describe('HomeAdministradorLogisticaComponent', () => {
  let component: HomeAdministradorLogisticaComponent;
  let fixture: ComponentFixture<HomeAdministradorLogisticaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeAdministradorLogisticaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeAdministradorLogisticaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
