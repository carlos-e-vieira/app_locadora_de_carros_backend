<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;

class MarcaController extends Controller
{
    private Marca $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index()
    {
        //$marcas = Marca::all();
        $marcas = $this->marca->all();
        return $marcas;
    }

    public function store(Request $request)
    {
        //$marca = Marca::create($request->all());
        $marca = $this->marca->create($request->all());
        return $marca;
    }

    public function show($id)
    {
        $marca = $this->marca->find($id);
        return $marca;
    }

    public function update(Request $request, $id)
    {
        //$marca->update($request->all());
        $marca = $this->marca->find($id);
        $marca->update($request->all());
        return $marca;
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);
        $marca->delete();
        return ['success' => true];
    }
}
