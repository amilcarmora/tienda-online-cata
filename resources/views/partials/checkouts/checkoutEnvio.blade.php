<div class="nav flex-column flex-md-row nav-pills text-center">
  <a href="#" class="nav-link flex-sm-fill text-sm-center" disabled>
      <i class="fa fa-map-marker"></i>Dirección
  </a>
  <a href="#" class="nav-link flex-sm-fill text-sm-center active">
      <i class="fa fa-truck"></i>Envío
  </a>
  <a href="#" class="nav-link flex-sm-fill text-sm-center" disabled>
      <i class="fa fa-money"></i>Pago
  </a>
  <a href="#" class="nav-link flex-sm-fill text-sm-center" disabled>
      <i class="fa fa-eye"></i>Pedido
  </a>
</div>
<div class="content py-3">
  <div class="row">
    <div class="col-md-6">
      <div class="box shipping-method">
        <h4>Envío exprés</h4>
        <p>Recibirás tu pedido en un máximo de 3 días hábiles.</p>
        <div class="box-footer text-center">
          <input type="radio" name="delivery" value="expres">
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="box shipping-method">
        <h4>Envío normal</h4>
        <p>Recibirás tu pedido en un máximo de 7 días hábiles.</p>
        <div class="box-footer text-center">
          <input type="radio" name="delivery" value="normal">
        </div>
      </div>
    </div>
  </div>
</div>
<div class="box-footer d-flex justify-content-between">
  <a href="{{route('cliente.checkout_dom')}}" class="btn btn-outline-secondary">
    <i class="fa fa-chevron-left"></i>Regresa a Dirección
  </a>
  <a href="{{route('cliente.checkout_pag')}}" class="btn btn-primary">
    Selecciona método de pago
    <i class="fa fa-chevron-right"></i>
  </a>
</div>