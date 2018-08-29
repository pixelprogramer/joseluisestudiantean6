import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { UnificacionUsuariosComponent } from './unificacion-usuarios.component';

describe('UnificacionUsuariosComponent', () => {
  let component: UnificacionUsuariosComponent;
  let fixture: ComponentFixture<UnificacionUsuariosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ UnificacionUsuariosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(UnificacionUsuariosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
