<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use App\Repositories\ClienteRepository;

class ClienteController extends Controller
{
    private Cliente $cliente;

    public function __construct(Cliente $cliente)
    {
        $this->cliente = $cliente;
    }

    public function index(Request $request)
    {
        $clienteRepository = new ClienteRepository($this->cliente);

        // condição de busca com filtro
        if ($request->has('filtro')) {
            $clienteRepository->filtro($request->filtro);
        }

        // condição de busca por atributos do marca
        if ($request->has('atributos')) {
            $clienteRepository->selectAtributos($request->atributos);
        }

        return response()->json($clienteRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->cliente->rules(), $this->cliente->feedback());
        $carro = $this->cliente->create(
            [
                'nome' => $request->nome
            ]
        );

        return response()->json($carro, 201);
    }

    public function show($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json($cliente, 200);
    }

    public function update(Request $request, $id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['success' => false], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicsRules = array();

            foreach ($cliente->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }

            }
            $request->validate($dinamicsRules, $this->cliente->feedback());
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->cliente->rules(), $this->cliente->feedback());
        }

        // Preencher objeto carro com os dados da request
        $cliente->fill($request->all());
        $cliente->save();

        return response()->json($cliente, 200);
    }

    public function destroy($id)
    {
        $cliente = $this->cliente->find($id);

        if ($cliente === null) {
            return response()->json(['success' => false], 404);
        }

        $cliente->delete();
        return response()->json(['success' => true], 200);
    }
}
