<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Cerveza;

class ShopController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Mostrar los estilos de cervezas en el sidebar menu izquierdo
        $estilos = (DB::table('cervezas')->select('estilo')
                    ->groupBy('estilo')->orderBy('estilo', 'ASC')->get())->all();
        
        //Mostrar 12 cervezas aleatoriamente (esto es temporal)
        $productos = Cerveza::inRandomOrder()->take(12)->get();
        $productos = $productos->all();

        return view('layouts_tienda.tienda', compact('estilos', 'productos'));
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //Mostrar los estilos de cervezas en el sidebar menu izquierdo
        $estilos = (DB::table('cervezas')->select('estilo')
                    ->groupBy('estilo')->orderBy('estilo', 'ASC')->get())->all();

        $cerveza = Cerveza::where('id', $id)->firstOrFail();

        return view('layouts_tienda.producto', compact('cerveza', 'estilos'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function paginacion(int $paginas)
    {
        dd($paginas);
    }
}
