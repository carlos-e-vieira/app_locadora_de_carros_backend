<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MarcaController extends Controller
{
    private Marca $marca;

    public function __construct(Marca $marca)
    {
        $this->marca = $marca;
    }

    public function index()
    {
        // adicionando o relacionamento - uma marca tem muito modelos
        $marcas = $this->marca->with('modelos')->get();
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
        // adicionando o relacionamento - uma marca tem muito modelos
        $marca = $this->marca->with('modelos')->find($id);

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

        // Remove o arquivo antigo caso um novo seja enviado no request
        if ($request->file('imagem')) {
            Storage::disk('public')->delete($marca->imagem);
        }
        
        $image = $request->file('imagem');
        $imageEndpoint = $image->store('imagens', 'public');

        $marca->update(
            [
                'nome' => $request->nome,
                'imagem' => $imageEndpoint
            ]
        );
        return response()->json($marca, 200);
    }

    public function destroy($id)
    {
        $marca = $this->marca->find($id);

        if ($marca === null) {
            return response()->json(['success' => false], 404);
        }

        // Remove o arquivo antigo
        Storage::disk('public')->delete($marca->imagem);

        $marca->delete();
        return response()->json(['success' => true], 200);
    }
}
