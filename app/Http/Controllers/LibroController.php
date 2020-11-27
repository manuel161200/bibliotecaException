<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Libros;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \App\Exceptions\CustomException
     */
    public function index(Request $request)
    {
        if($request->ajax())
        {
            $libro = Libros::latest()->get();
            return DataTables::of($libro)
                ->addColumn('action', function($libro){
                    $button = '<button type="button" name="edit" id="'.$libro->id.'" class="edit btn btn-primary btn-sm">Edit</button>';
                    $button .= '&nbsp;&nbsp;&nbsp;<button type="button" name="edit" id="'.$libro->id.'" class="delete btn btn-danger btn-sm">Delete</button>';
                    return $button;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('tablaLibros');
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
        $form_data = array(
            'titulo'        =>  $request->titulo,
            'sinopsis'         =>  $request->sinopsis,
            'numPaginas'   => $request->numPaginas
        );

            $totalLibros = Libros::all()->count();

            if ($totalLibros < 5) {
                Libros::create($form_data);

                return response()->json(['success' => 'El libro ha sido añadido']);
            } else {
                //La excecpion funciona pero no la muestra
                throw new CustomException('Has sobrepasado el limite de 5 libros que podías agragar');
            }
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
        if(request()->ajax())
        {
            $data = Libros::findOrFail($id);
            return response()->json(['result' => $data]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $form_data = array(
            'titulo'    =>  $request->titulo,
            'sinopsis'     =>  $request->sinopsis,
            'numPaginas'  =>  $request->numPaginas
        );

        Libros::whereId($request->hidden_id)->update($form_data);

        return response()->json(['success' => 'El libro ha sido editado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $libro = Libros::findOrFail($id);
        $libro->delete();
    }
}
