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
        return response()->json($marcas, 200);
    }

    public function store(Request $request)
    {
        //$marca = Marca::create($request->all());
        $request->validate($this->marca->rules(), $this->marca->feedback());

        $image = $request->file('imagem');
        $imageEndpoint = $image->store('imagens', 'public');

        $marca = $this->marca->create(
            [
                'nome' => $request->nome,
                'imagem' => $imageEndpoint
            ]
        );

        return response()->json($marca, 201);
    }

    public function show($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json($marca, 200);
    }

    public function update(Request $request, $id)
    {
        //$marca->update($request->all());
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['success' => false], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicsRules = array();

            foreach ($marca->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }

            }
            $request->validate($dinamicsRules, $this->marca->feedback());
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->marca->rules(), $this->marca->feedback());
        }

        $marca->update($request->all());
        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['success' => false], 404);
        }

        $marca->delete();
        return response()->json(['success' => true], 200);
    }
}
