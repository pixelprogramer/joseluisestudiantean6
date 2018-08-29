import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AlmacenCalificacionComponent } from './almacen-calificacion.component';

describe('AlmacenCalificacionComponent', () => {
  let component: AlmacenCalificacionComponent;
  let fixture: ComponentFixture<AlmacenCalificacionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AlmacenCalificacionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AlmacenCalificacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
