$(function() {
    config.date_range();
    altair_product_edit.init();

    $(".repuestos_table").on('keyup', 'input.codigos_prod, input.cants_prod', function(e) {
        e.preventDefault();
        let codProducto_Ingresado = e.target.value;
        console.log(codProducto_Ingresado);

        validaProducto(codProducto_Ingresado);

    });

    /* Evita problema de doble calendario en firefox*/
    $('input[type=date]').on('click', function(event) {
        var isFirefox = typeof InstallTrigger !== 'undefined';
        if (isFirefox) {
            event.preventDefault();
        }
    });
});

/* Peticion al API retornara objeto con la informacion si existe el producto */

function validaProducto(codProducto) {
    $.ajax({
        type: 'get',
        url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=validaProducto',
        data: { codProducto: codProducto },

        success: function(response) {
            console.log(JSON.parse(response));
        },
        error: function(error) {
            console.error(error.statusText);
        }

    });



}

config = {
    // date range
    date_range: function() {
        var $dp_start = $('#uk_dp_start'),
            $dp_end = $('#uk_dp_end');

        var start_date = UIkit.datepicker($dp_start, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });


    }
}

altair_product_edit = {
    init: function() {
        // product edit form
        altair_product_edit.edit_form();
        // product tags
        altair_product_edit.product_tags();
    },
    edit_form: function() {
        // form variables
        var $product_edit_form = $('#product_edit_form'),
            $product_edit_submit_btn = $('#product_edit_submit');


        // submit form
        $product_edit_submit_btn.on('click', function(e) {
            e.preventDefault();
            let codigoMNT = document.getElementById("codMantenimiento").innerHTML;
            var form_serialized = JSON.stringify($product_edit_form.serializeObject(), null, 2);
            UIkit.modal.alert('<p>Producto data:</p><pre>' + form_serialized + '</pre>');

            UIkit.modal.confirm('Confirme, actualizar informacion de la orden de trabajo ' + codigoMNT + ' ?', function() {
                $.ajax({
                    url: '',
                    method: 'GET',
                    data: form_serialized,

                    success: function() {
                        alert('Actualizado');

                    },
                    error: function(error) {
                        alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                    }

                });
            });
        })
    },
    product_tags: function() {

        $('#product_edit_tags_control').selectize({
            plugins: {
                'remove_button': {
                    label: ''
                }
            },
            placeholder: 'Select product tag(s)',
            options: [
                { id: 1, title: 'LTE', value: 'lte' },
                { id: 2, title: 'Quad HD', value: 'quad_hd' },
                { id: 3, title: 'Android™ 5.0', value: 'android_5' },
                { id: 4, title: '64GB', value: '64gb' }
            ],
            render: {
                option: function(data, escape) {
                    return '<div class="option">' +
                        '<span>' + escape(data.title) + '</span>' +
                        '</div>';
                },
                item: function(data, escape) {
                    return '<div class="item">' + escape(data.title) + '</div>';
                }
            },
            maxItems: null,
            valueField: 'value',
            labelField: 'title',
            searchField: 'title',
            create: true,
            onDropdownOpen: function($dropdown) {
                $dropdown
                    .hide()
                    .velocity('slideDown', {
                        begin: function() {
                            $dropdown.css({ 'margin-top': '0' })
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            },
            onDropdownClose: function($dropdown) {
                $dropdown
                    .show()
                    .velocity('slideUp', {
                        complete: function() {
                            $dropdown.css({ 'margin-top': '' })
                        },
                        duration: 200,
                        easing: easing_swiftOut
                    })
            }
        });
    }
};