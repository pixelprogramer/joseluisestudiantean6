import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { IndicadoresCalificacionComponent } from './indicadores-calificacion.component';

describe('IndicadoresCalificacionComponent', () => {
  let component: IndicadoresCalificacionComponent;
  let fixture: ComponentFixture<IndicadoresCalificacionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ IndicadoresCalificacionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(IndicadoresCalificacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
