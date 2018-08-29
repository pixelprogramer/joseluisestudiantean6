import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { StockUsuarioComponent } from './stock-usuario.component';

describe('StockUsuarioComponent', () => {
  let component: StockUsuarioComponent;
  let fixture: ComponentFixture<StockUsuarioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ StockUsuarioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(StockUsuarioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
