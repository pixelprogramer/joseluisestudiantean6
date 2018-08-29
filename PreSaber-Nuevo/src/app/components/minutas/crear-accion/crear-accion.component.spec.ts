import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CrearAccionComponent } from './crear-accion.component';

describe('CrearAccionComponent', () => {
  let component: CrearAccionComponent;
  let fixture: ComponentFixture<CrearAccionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CrearAccionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CrearAccionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
