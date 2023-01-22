<?php

namespace App\Http\Controllers;

use App\Models\Carro;
use App\Repositories\CarroRepository;
use Illuminate\Http\Request;

class CarroController extends Controller
{
    private Carro $carro;

    public function __construct(Carro $carro)
    {
        $this->carro = $carro;
    }

    public function index(Request $request)
    {
        $carroRepository = new CarroRepository($this->carro);

        // condição de busca por atributos do modelo
        if ($request->has('atributos_modelo')) {
            $atributos_modelo = 'modelo:id,'. $request->atributos_modelo;
            $carroRepository->selectAtributosRegistrosRelacionados($atributos_modelo);
        
        } else {
            $carroRepository->selectAtributosRegistrosRelacionados('modelo');
        }

        // condição de busca com filtro
        if ($request->has('filtro')) {
            $carroRepository->filtro($request->filtro);
        }

        // condição de busca por atributos do marca
        if ($request->has('atributos')) {
            $carroRepository->selectAtributos($request->atributos);
        }

        return response()->json($carroRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->carro->rules(), $this->carro->feedback());
        $carro = $this->carro->create(
            [
                'modelo_id' => $request->modelo_id,
                'placa' => $request->placa,
                'disponivel' => $request->disponivel,
                'km' => $request->km
            ]
        );

        return response()->json($carro, 201);
    }

    public function show($id)
    {
        // adicionando o relacionamento - uma marca tem muito modelos
        $carro = $this->carro->with('modelo')->find($id);

        if ($carro === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json($carro, 200);
    }

    public function update(Request $request, $id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null) {
            return response()->json(['success' => false], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicsRules = array();

            foreach ($carro->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }

            }
            $request->validate($dinamicsRules, $this->carro->feedback());
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->carro->rules(), $this->carro->feedback());
        }

        // Preencher objeto carro com os dados da request
        $carro->fill($request->all());
        $carro->save();

        return response()->json($carro, 200);
    }

    public function destroy($id)
    {
        $carro = $this->carro->find($id);

        if ($carro === null) {
            return response()->json(['success' => false], 404);
        }

        $carro->delete();
        return response()->json(['success' => true], 200);
    }
}
