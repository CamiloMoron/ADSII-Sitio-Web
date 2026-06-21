@extends('layouts.app')

@section('content')
<div class="fade-in">
    <h2 class="text-xl font-bold text-white mb-1">Hub de Reportes</h2>
    <p class="text-sm mb-8" style="color:#64748b;">Centro de exportación de datos transaccionales en formato CSV</p>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl">

        <div class="rounded-xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background:#f59e0b22;">
                    <i data-lucide="file-signature" style="width:20px;height:20px;color:#f59e0b;"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Acuerdos Comerciales</h3>
                    <span class="text-xs mono" style="color:#64748b;">reporte_contratos.csv</span>
                </div>
            </div>
            <p class="text-sm mb-6" style="color:#94a3b8;">
                Exporta el listado completo de vigencias, tarifas pactadas y estados de los contratos corporativos.
            </p>
            <a href="{{ route('reportes.contratos') }}" 
               class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110" 
               style="background:#f59e0b;color:#0f172a;">
                <i data-lucide="download" style="width:16px;height:16px;"></i>
                Descargar CSV
            </a>
        </div>

        <div class="rounded-xl p-6 fade-in" style="background:#1e293b;border:1px solid #334155;">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" style="background:#3b82f622;">
                    <i data-lucide="clipboard-list" style="width:20px;height:20px;color:#60a5fa;"></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-white">Servicios Logísticos</h3>
                    <span class="text-xs mono" style="color:#64748b;">reporte_ordenes.csv</span>
                </div>
            </div>
            <p class="text-sm mb-6" style="color:#94a3b8;">
                Histórico consolidado de recojos programados, volúmenes estimados y estados de las órdenes.
            </p>
            <a href="{{ route('reportes.ordenes') }}" 
               class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg text-sm font-semibold transition hover:brightness-110" 
               style="background:#3b82f6;color:#ffffff;">
                <i data-lucide="download" style="width:16px;height:16px;"></i>
                Descargar CSV
            </a>
        </div>

    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
