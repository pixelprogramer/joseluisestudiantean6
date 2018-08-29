import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeCalificacionComponent } from './home-calificacion.component';

describe('HomeCalificacionComponent', () => {
  let component: HomeCalificacionComponent;
  let fixture: ComponentFixture<HomeCalificacionComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeCalificacionComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeCalificacionComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
