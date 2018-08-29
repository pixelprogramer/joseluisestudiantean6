import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CrearPermisosComponent } from './crear-permisos.component';

describe('CrearPermisosComponent', () => {
  let component: CrearPermisosComponent;
  let fixture: ComponentFixture<CrearPermisosComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CrearPermisosComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CrearPermisosComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
