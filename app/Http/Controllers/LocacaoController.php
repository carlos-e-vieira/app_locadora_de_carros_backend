<?php

namespace App\Http\Controllers;

use App\Models\Locacao;
use App\Repositories\LocacaoRepository;
use Illuminate\Http\Request;

class LocacaoController extends Controller
{
    private Locacao $locacao;

    public function __construct(Locacao $locacao)
    {
        $this->locacao = $locacao;
    }

    public function index(Request $request)
    {
        $locacaoRepository = new LocacaoRepository($this->locacao);

        // condição de busca com filtro
        if ($request->has('filtro')) {
            $locacaoRepository->filtro($request->filtro);
        }

        // condição de busca por atributos do marca
        if ($request->has('atributos')) {
            $locacaoRepository->selectAtributos($request->atributos);
        }

        return response()->json($locacaoRepository->getResultado(), 200);
    }

    public function store(Request $request)
    {
        $request->validate($this->locacao->rules(), $this->locacao->feedback());
        $locacao = $this->locacao->create(
            [
                'cliente_id' => $request->cliente_id,
                'carro_id' => $request->carro_id,
                'data_inicio_periodo' => $request->data_inicio_periodo,
                'data_final_previsto_periodo' => $request->data_final_previsto_periodo,
                'data_final_realizado_periodo' => $request->data_final_realizado_periodo,
                'valor_diaria' => $request->valor_diaria,
                'km_inicial' => $request->km_inicial,
                'km_final' => $request->km_final
            ]
        );

        return response()->json($locacao, 201);
    }

    public function show($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['success' => false], 404);
        }

        return response()->json($locacao, 200);
    }

    public function update(Request $request, $id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['success' => false], 404);
        }

        if ($request->method() === 'PATCH') {
            $dinamicsRules = array();

            foreach ($locacao->rules() as $input => $rule) {

                if (array_key_exists($input, $request->all())) {
                    $dinamicsRules[$input] = $rule;
                }

            }
            $request->validate($dinamicsRules, $this->locacao->feedback());
        }

        if ($request->method() === 'PUT') {
            $request->validate($this->locacao->rules(), $this->locacao->feedback());
        }

        // Preencher objeto carro com os dados da request
        $locacao->fill($request->all());
        $locacao->save();

        return response()->json($locacao, 200);
    }

    public function destroy($id)
    {
        $locacao = $this->locacao->find($id);

        if ($locacao === null) {
            return response()->json(['success' => false], 404);
        }

        $locacao->delete();
        return response()->json(['success' => true], 200);
    }
}
