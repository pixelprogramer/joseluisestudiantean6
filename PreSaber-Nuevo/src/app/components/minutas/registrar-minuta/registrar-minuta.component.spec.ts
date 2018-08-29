import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { RegistrarMinutaComponent } from './registrar-minuta.component';

describe('RegistrarMinutaComponent', () => {
  let component: RegistrarMinutaComponent;
  let fixture: ComponentFixture<RegistrarMinutaComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ RegistrarMinutaComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(RegistrarMinutaComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
