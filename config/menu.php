<?php

return [
    'Administrador' => [
        ['route' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
        ['route' => 'catalogos.index', 'icon' => 'database', 'label' => 'Catálogos'],
        ['route' => 'contratos.index', 'icon' => 'file-signature', 'label' => 'Contratos'],
        ['route' => 'usuarios.index', 'icon' => 'users', 'label' => 'Usuarios y Perfiles'],
        ['route' => 'ordenes-servicio.index', 'icon' => 'clipboard-list', 'label' => 'Órdenes de Servicio'],
        ['route' => 'ordenes-venta.index', 'icon' => 'shopping-cart', 'label' => 'Órdenes de Venta'],
        ['route' => 'facturas.index', 'icon' => 'file-text', 'label' => 'Facturas Electrónicas'],
        ['route' => 'rutas.index', 'icon' => 'map', 'label' => 'Rutas de Recolección'],
        ['route' => 'guias.index', 'icon' => 'receipt', 'label' => 'Guías de Recojo'],
        ['route' => 'clasificacion.index', 'icon' => 'tag', 'label' => 'Clasificación de Material'],
        ['route' => 'inventario.index', 'icon' => 'boxes', 'label' => 'Cierre de Inventario'],
        ['route' => 'reportes.index', 'icon' => 'bar-chart-3', 'label' => 'Reportes'],
    ],

    'Asistente Administrativo' => [
        ['route' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
        ['route' => 'catalogos.index', 'icon' => 'database', 'label' => 'Catálogos'],
        ['route' => 'contratos.index', 'icon' => 'file-signature', 'label' => 'Contratos'],
        ['route' => 'ordenes-servicio.index', 'icon' => 'clipboard-list', 'label' => 'Órdenes de Servicio'],
        ['route' => 'ordenes-venta.index', 'icon' => 'shopping-cart', 'label' => 'Órdenes de Venta'],
        ['route' => 'facturas.index', 'icon' => 'file-text', 'label' => 'Facturas Electrónicas'],
        ['route' => 'reportes.index', 'icon' => 'bar-chart-3', 'label' => 'Reportes'],
    ],

    'Encargado de Logística' => [
        ['route' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
        ['route' => 'ordenes-servicio.index', 'icon' => 'clipboard-list', 'label' => 'Órdenes de Servicio'],
        ['route' => 'rutas.index', 'icon' => 'map', 'label' => 'Rutas de Recolección'],
    ],

    'Supervisor de Planta' => [
        ['route' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
        ['route' => 'catalogos.index', 'icon' => 'database', 'label' => 'Catálogos'],
        ['route' => 'clasificacion.index', 'icon' => 'tag', 'label' => 'Clasificación de Material'],
        ['route' => 'inventario.index', 'icon' => 'boxes', 'label' => 'Cierre de Inventario'],
    ],

    'Chofer' => [
        ['route' => 'dashboard', 'icon' => 'layout-dashboard', 'label' => 'Dashboard'],
        ['route' => 'guias.index', 'icon' => 'receipt', 'label' => 'Guías de Recojo'],
    ],
];
