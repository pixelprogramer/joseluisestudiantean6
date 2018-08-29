import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { HomePremarcadoComponent } from './home-premarcado.component';

describe('HomePremarcadoComponent', () => {
  let component: HomePremarcadoComponent;
  let fixture: ComponentFixture<HomePremarcadoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ HomePremarcadoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(HomePremarcadoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
