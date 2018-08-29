import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TrasladoUsuarioComponent } from './traslado-usuario.component';

describe('TrasladoUsuarioComponent', () => {
  let component: TrasladoUsuarioComponent;
  let fixture: ComponentFixture<TrasladoUsuarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TrasladoUsuarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TrasladoUsuarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
