import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { PremarcadoComponent } from './premarcado.component';

describe('PremarcadoComponent', () => {
  let component: PremarcadoComponent;
  let fixture: ComponentFixture<PremarcadoComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ PremarcadoComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(PremarcadoComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
