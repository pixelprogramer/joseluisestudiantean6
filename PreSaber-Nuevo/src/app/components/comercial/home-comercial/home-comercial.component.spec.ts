import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeComercialComponent } from './home-comercial.component';

describe('HomeComercialComponent', () => {
  let component: HomeComercialComponent;
  let fixture: ComponentFixture<HomeComercialComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeComercialComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeComercialComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
