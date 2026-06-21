<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\OrdenServicio;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReporteController extends Controller
{
    public function index()
    {
        return view('reportes.index');
    }

    public function exportarContratos()
    {
        $contratos = Contrato::with('cliente')->orderBy('id', 'asc')->get();
        $filename = 'reporte_contratos_' . now()->format('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () use ($contratos) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(239) . chr(187) . chr(191));

            fputcsv($handle, ['ID', 'Cliente', 'RUC', 'Fecha Inicio', 'Fecha Fin', 'Tarifa (S/)', 'Estado'], ';');

            foreach ($contratos as $contrato) {
                fputcsv($handle, [
                    $contrato->id,
                    $contrato->cliente->nombre,
                    $contrato->cliente->ruc,
                    $contrato->fecha_inicio->format('d/m/Y'),
                    $contrato->fecha_fin->format('d/m/Y'),
                    number_format($contrato->tarifa, 2, '.', ''),
                    $contrato->estado,
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }

    public function exportarOrdenes()
    {
        $ordenes = OrdenServicio::with('cliente')->orderBy('id', 'asc')->get();
        $filename = 'reporte_ordenes_' . now()->format('Y-m-d') . '.csv';

        $response = new StreamedResponse(function () use ($ordenes) {
            $handle = fopen('php://output', 'w');

            fwrite($handle, chr(239) . chr(187) . chr(191));

            fputcsv($handle, ['ID', 'Cliente', 'RUC', 'Sede', 'Fecha', 'Ventana Horaria', 'Volumen Estimado', 'Estado'], ';');

            foreach ($ordenes as $orden) {
                fputcsv($handle, [
                    $orden->id,
                    $orden->cliente->nombre,
                    $orden->cliente->ruc,
                    $orden->sede,
                    $orden->fecha->format('d/m/Y'),
                    $orden->ventana_horaria,
                    $orden->volumen_estimado,
                    $orden->estado,
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=UTF-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }
}
