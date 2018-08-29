import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomeAdminSistemasComponent } from './home-admin-sistemas.component';

describe('HomeAdminSistemasComponent', () => {
  let component: HomeAdminSistemasComponent;
  let fixture: ComponentFixture<HomeAdminSistemasComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomeAdminSistemasComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomeAdminSistemasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
