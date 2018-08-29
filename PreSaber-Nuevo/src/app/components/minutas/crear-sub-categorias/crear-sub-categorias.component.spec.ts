import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CrearSubCategoriasComponent } from './crear-sub-categorias.component';

describe('CrearSubCategoriasComponent', () => {
  let component: CrearSubCategoriasComponent;
  let fixture: ComponentFixture<CrearSubCategoriasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CrearSubCategoriasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CrearSubCategoriasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
