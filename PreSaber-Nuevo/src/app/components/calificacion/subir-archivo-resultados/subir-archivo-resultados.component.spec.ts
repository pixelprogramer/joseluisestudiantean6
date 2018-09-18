import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { SubirArchivoResultadosComponent } from './subir-archivo-resultados.component';

describe('SubirArchivoResultadosComponent', () => {
  let component: SubirArchivoResultadosComponent;
  let fixture: ComponentFixture<SubirArchivoResultadosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ SubirArchivoResultadosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(SubirArchivoResultadosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
