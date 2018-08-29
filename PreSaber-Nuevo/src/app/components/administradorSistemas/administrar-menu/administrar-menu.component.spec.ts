import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { AdministrarMenuComponent } from './administrar-menu.component';

describe('AdministrarMenuComponent', () => {
  let component: AdministrarMenuComponent;
  let fixture: ComponentFixture<AdministrarMenuComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ AdministrarMenuComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(AdministrarMenuComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
