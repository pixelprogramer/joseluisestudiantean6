import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeDistrivuidorComponent } from './home-distrivuidor.component';

describe('HomeDistrivuidorComponent', () => {
  let component: HomeDistrivuidorComponent;
  let fixture: ComponentFixture<HomeDistrivuidorComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeDistrivuidorComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeDistrivuidorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
