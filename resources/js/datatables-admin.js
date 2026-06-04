/**
 * datatables-admin.js — Artcania
 * Inicializa todas las DataTables del panel admin con:
 *  - búsqueda, ordenamiento, paginación
 *  - exportar PDF, Excel, CSV, impresión
 */

$(function () {

    /* ── Configuración base compartida ─────────────────── */
    var baseConfig = {
        language: {
            url: 'resources/json/es-ES.json'
        },
        pageLength: 15,
        lengthMenu: [[10, 15, 25, 50, -1], [10, 15, 25, 50, 'Todos']],
        responsive: true,
        autoWidth: false,
        dom: '<"d-flex flex-wrap align-items-center justify-content-between mb-2"lB>' +
             '<"dt-filter-row mb-3"f>' +
             'rt' +
             '<"d-flex flex-wrap align-items-center justify-content-between mt-2"ip>',
        buttons: [
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf me-1"></i> PDF',
                className: 'dt-button buttons-pdf',
                exportOptions: { columns: ':not(.no-export)' },
                customize: function (doc) {
                    doc.styles.tableHeader.fillColor = '#16102a';
                    doc.styles.tableHeader.color     = '#e8c65a';
                    doc.defaultStyle.fontSize        = 9;
                }
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel me-1"></i> Excel',
                className: 'dt-button buttons-excel',
                exportOptions: { columns: ':not(.no-export)' }
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-csv me-1"></i> CSV',
                className: 'dt-button buttons-csv',
                exportOptions: { columns: ':not(.no-export)' }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print me-1"></i> Imprimir',
                className: 'dt-button buttons-print',
                exportOptions: { columns: ':not(.no-export)' },
                customize: function (win) {
                    $(win.document.body).css('font-family', 'Arial, sans-serif');
                    $(win.document.body).find('table').css('font-size', '11px');
                }
            }
        ]
    };

    /* ── Tabla: Usuarios ───────────────────────────────── */
    if ($('#tablaUsuarios').length && !$.fn.DataTable.isDataTable('#tablaUsuarios')) {
        $('#tablaUsuarios').DataTable($.extend(true, {}, baseConfig, {
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [3, 4, 5] }, // verificado, activo, acciones
                { className: 'no-export', targets: [5] }
            ]
        }));
    }

    /* ── Tabla: Bitácora ───────────────────────────────── */
    if ($('#tablaBitacora').length && !$.fn.DataTable.isDataTable('#tablaBitacora')) {
        $('#tablaBitacora').DataTable($.extend(true, {}, baseConfig, {
            order: [[6, 'desc']], // fecha
            pageLength: 25
        }));
    }

    /* ── Tabla: Obras ──────────────────────────────────── */
    if ($('#tablaObras').length && !$.fn.DataTable.isDataTable('#tablaObras')) {
        $('#tablaObras').DataTable($.extend(true, {}, baseConfig, {
            order: [[5, 'desc']] // fecha
        }));
    }

    /* ── Tabla: Subastas ───────────────────────────────── */
    if ($('#tablaSubastas').length && !$.fn.DataTable.isDataTable('#tablaSubastas')) {
        $('#tablaSubastas').DataTable($.extend(true, {}, baseConfig, {
            order: [[5, 'desc']] // fecha cierre
        }));
    }

    /* ── Tabla: Artistas ───────────────────────────────── */
    if ($('#tablaArtistas').length && !$.fn.DataTable.isDataTable('#tablaArtistas')) {
        $('#tablaArtistas').DataTable($.extend(true, {}, baseConfig, {
            order: [[0, 'asc']]
        }));
    }

});
