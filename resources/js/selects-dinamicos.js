/**
 * selects-dinamicos.js — Artcania
 * Carga encadenada: Categoría → Subcategoría → Técnica
 * Requiere jQuery (ya cargado en el layout)
 */

$(function () {

    var $categoria     = $('#selectCategoria');
    var $subcategoria  = $('#selectSubcategoria');
    var $tecnica       = $('#selectTecnica');

    /* ── Utilidades ─────────────────────────────────────── */
    function llenarSelect($sel, items, placeholder) {
        $sel.empty().append(
            $('<option>', { value: '', text: placeholder })
        );
        $.each(items, function (_, item) {
            $sel.append(
                $('<option>', { value: item.id, text: item.nombre })
            );
        });
    }

    function resetSelect($sel, placeholder) {
        $sel.empty().append(
            $('<option>', { value: '', text: placeholder })
        ).prop('disabled', true);
    }

    /* ── Al cambiar Categoría → cargar Subcategorías ────── */
    $categoria.on('change', function () {
        var catId = $(this).val();

        // Resetear dependientes
        resetSelect($subcategoria, '— Selecciona subcategoría —');
        resetSelect($tecnica,      '— Selecciona técnica —');

        if (!catId) return;

        $.ajax({
            url:      BASE_URL + '/ajax/get_subcategorias.php',
            data:     { categoria_id: catId },
            dataType: 'json',
            success: function (data) {
                if (data && data.length) {
                    llenarSelect($subcategoria, data, '— Selecciona subcategoría —');
                    $subcategoria.prop('disabled', false);
                } else {
                    // Sin subcategorías: habilitar técnica directamente
                    cargarTecnicas(null);
                }
            },
            error: function () {
                console.warn('Artcania: Error al cargar subcategorías');
            }
        });
    });

    /* ── Al cambiar Subcategoría → cargar Técnicas ──────── */
    $subcategoria.on('change', function () {
        var subId = $(this).val();
        cargarTecnicas(subId || null);
    });

    function cargarTecnicas(subcategoriaId) {
        var params = subcategoriaId ? { subcategoria_id: subcategoriaId } : {};

        $.ajax({
            url:      BASE_URL + '/ajax/get_tecnicas.php',
            data:     params,
            dataType: 'json',
            success: function (data) {
                if (data && data.length) {
                    llenarSelect($tecnica, data, '— Selecciona técnica —');
                    $tecnica.prop('disabled', false);
                }
            },
            error: function () {
                // silencioso si la tabla técnicas no existe aún
            }
        });
    }

    /* ── Inicializar estado deshabilitado ───────────────── */
    if (!$categoria.val()) {
        $subcategoria.prop('disabled', true);
        $tecnica.prop('disabled', true);
    }

});
