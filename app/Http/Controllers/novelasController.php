<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Novela;
use App\Models\Capitulo;

class novelasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $novelas = Novela::paginate(12);
        return view('home', compact('novelas'));
    }

    public function probando()
    {
        $novelas = Novela::orderBy('id', 'desc')->get();

        return response(json_encode($novelas), 200)->header('Content-type', 'text/plain');
    }

    public function buscador(Request $request)
    {

        $id = $_POST['id'];

        $busqueda = Novela::where('titulo', 'LIKE', '%' . $id . '%')->get();

        return response(json_encode($busqueda), 200);
    }

    public function marcado(Request $request)
    {

        $id = $_POST['id'];

        $marcado = Capitulo::where('id', '=', $id)->update(['marcado' => true]);

        return response(json_encode($marcado), 200);
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

    public function capitulo(Request $request)
    {
        $id = $_POST['id'];

        $capitulo = Capitulo::find($id);

        return response(json_encode($capitulo), 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $titulo = $_POST['titulo'];
        $linkImage = $_POST['linkImage'];
        $slug = $_POST['slug'];
        $descripcion = $_POST['descripcion'];

        Novela::insert(
            [
                'titulo' => $titulo,
                'linkImage' => $linkImage,
                'descripcion' => $descripcion,
                'slug' => $slug
            ]
        );
    }

    public function subirCapitulo(Request $request)
    {
        $numero = $_POST['numero'];
        $titulo = $_POST['titulo'];
        $id_novelas = $_POST['id_novelas'];
        $capitulo = $_POST['capitulo'];

        $capitulos = new Capitulo;
        $capitulos->titulo = $titulo;
        $capitulos->numero = $numero;
        $capitulos->capitulo = $capitulo;
        $capitulos->id_Novelas = $id_novelas;
        $capitulos->save();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
}
