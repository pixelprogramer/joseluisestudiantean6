import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { ValidarPaginaComponent } from './validar-pagina.component';

describe('ValidarPaginaComponent', () => {
  let component: ValidarPaginaComponent;
  let fixture: ComponentFixture<ValidarPaginaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ ValidarPaginaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(ValidarPaginaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
