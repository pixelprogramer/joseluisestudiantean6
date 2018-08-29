import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { NuevoPedidoDistribuidorComponent } from './nuevo-pedido-distribuidor.component';

describe('NuevoPedidoDistribuidorComponent', () => {
  let component: NuevoPedidoDistribuidorComponent;
  let fixture: ComponentFixture<NuevoPedidoDistribuidorComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ NuevoPedidoDistribuidorComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(NuevoPedidoDistribuidorComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
