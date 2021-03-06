<?php

namespace App\Http\Controllers;

use App\Cerveza;
use App\Coupon;
use Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct()
    {
      $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $sugerencias = Cerveza::inRandomOrder()->take(3)->get();

      return view('layouts_cliente.clienteCompra', compact('sugerencias'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
      $duplicados = Cart::search(function($cartItem, $id) use ($request){
        return $cartItem->id === $request->id;
      });

      if($duplicados->isNotEmpty())
      {
        return redirect()->route('cart.index')->with('success_message', "Ya has agregado este producto antes");
      }

      $cerveza = new Cerveza();
      Cart::add(array(
        'id' => $request->id,
        'name' => $request->name,
        'price' => $request->price,
        'quantity' => 1,
        'attributes' => array(),
        'associatedModel' => $cerveza
      ));

      return redirect()->route('cart.index')->with('success_message', 'El producto fue agregado al carro');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

      Cart::update($id, array(
        'quantity' => array(
            'relative' => false,
            'value' => $request->quantity
        ),
      ));
      session()->flash('success_message', 'La cantidad ha sido actualizada');
      return response()->json(["mensaje_exito" => true]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Cart::remove($id);

      if(Cart::isEmpty())
      {
        Cart::clear();
        Cart::clearCartConditions();
      }

      return back()->with('success_message', 'El producto ha sido removido');
    }

    public function apply(Request $request)
    {
      $coupon = Coupon::findByCode($request->cupon);

      //Si el código no existe
      if(is_null($coupon))
      {
        return redirect()->route('cart.index')->with('error_message', 'Cupón no válido');
      }

      $coupon_type = $coupon->coupon_type;

      //Verificación del tipo de cupón para saber qué método utilizar
      if($coupon_type == "App\CantidadMinimaCoupon")
      {
        $desc = $coupon->desc_por_cant(Cart::getSubtotal(), Cart::getTotalQuantity());
        if($desc == 0)
        {
          return redirect()->route('cart.index')->with('error_message', 'No tienes la cantidad mínima de productos');
        }
      }
      else{
        $desc = $coupon->descuento(Cart::getSubtotal());
        if(Cart::getSubtotal() < $desc)
        {
          return redirect()->route('cart.index')->with('error_message', 'No es posible aplicar este cupón');
        }
      }

      //Creación del array de condición para el carrito
      $cupon_condition = new \Darryldecode\Cart\CartCondition(array(
        'name' => 'cupon',
        'type' => 'coupon',
        'target' => 'subtotal',
        'value' => '-'.$desc,
        'attributes' => array(
          'codigo' => $coupon->codigo,
          'descuento' => $desc
        )
      ));

      $conditions = Cart::getConditions();
      $cuenta_cupones = 0;

      //Ciclo que verifica si ya existen condiciones tipo cupón y ver si ya hay dos o si se ha utilizado el cupón
      foreach($conditions as $condition)
      {
        if($condition->getType() == 'coupon')
        {
          $cuenta_cupones++;
          if($cuenta_cupones == 1)
          {
            return redirect()->route('cart.index')->with('error_message', 'No puedes utilizar más de un cupón');
          }
          else if($condition->getAttributes()['codigo'] == $coupon->codigo)
          {
            return redirect()->route('cart.index')->with('error_message', 'Este cupón ha sido utilizado');
          }
        }
      }

      //Si no hay ningún problema se aplica el cupón
      Cart::condition($cupon_condition);

      return redirect()->route('cart.index')->with('success_message', 'Tu cupón ha sido aplicado');
    }
}
