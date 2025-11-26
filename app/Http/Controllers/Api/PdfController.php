<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PdfController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function fichaTest()
    {
        // $data = ['title' => 'Mi primer documento', 'content' => 'Contenido del primer documento'];
        $persona = [
            'nombre_completo' => 'EDWIN BLADIMIR ANCO CRUZ',
            'numero_documento' => '71587026',
            'email' => 'edwin.anco@gmail.com',
            'telefono' => '914084004',
            'direccion' => 'PUNO',
        ];

        $empresa = [
            'razon_social' => 'COOPERATIVA DE SERVICIOS EDUCACIONALES CAPACITA',
            'ruc' => '20603337337'
        ];

        $programas = [
            'programa_1' => [
                'nombre' => 'ESP: SANIDAD ANIMAL',
                'asesor' => 'LUZ ROSABEL TAPIA'
            ],
            'programa_2' => [
                'nombre' => 'ESP: TÉCNICO AGROPECUARIO',
                'asesor' => 'LUZ ROSABEL TAPIA'
            ]
        ];

        $pago = [
            'matricula' => '120.00',
            'cuotas' => '12',
            'monto_cuota' => '120.00',
            'total' => '1560.00'
        ];

        // $data = ['title' => 'PROGRAMA DE EXTENSIÓN UNIVERSITARIA']
        $data = [
            'title' => 'PROGRAMA DE EXTENSIÓN UNIVERSITARIA',
            'content' => [
                'persona' => $persona,
                'empresa' => $empresa,
                'programas' => $programas,
                'pago' => $pago
            ]
        ];

        $pdf = Pdf::loadView('pdfs.generateFicha', $data);
        
        return $pdf->download('ficha_0001.pdf');
    }
}
