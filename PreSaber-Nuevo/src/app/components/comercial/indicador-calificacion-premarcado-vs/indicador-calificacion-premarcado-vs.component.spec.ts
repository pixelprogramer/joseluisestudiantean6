import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { IndicadorCalificacionPremarcadoVsComponent } from './indicador-calificacion-premarcado-vs.component';

describe('IndicadorCalificacionPremarcadoVsComponent', () => {
  let component: IndicadorCalificacionPremarcadoVsComponent;
  let fixture: ComponentFixture<IndicadorCalificacionPremarcadoVsComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IndicadorCalificacionPremarcadoVsComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IndicadorCalificacionPremarcadoVsComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
