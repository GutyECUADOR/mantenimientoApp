$(function() {

    config.date_range();
    altair_product_edit.init();

    UIkit.modal($('#modal_facturadoA'), {modal: false, keyboard: false, bgclose: false, center: true}).show();

    $(".repuestos_table").on('keyup', 'input.codigos_prod', function(e) {
        e.preventDefault();
        let codProducto_Ingresado = e.target.value;
        console.log(codProducto_Ingresado);

        validaProducto(codProducto_Ingresado, e.target);

    });

    $('#modal_facturadoA_cancel').on('click', function(event) {
        let $select = $("#product_edit_facturadoa").selectize();
        let selectize = $select[0].selectize;
        selectize.setValue(1); //Facturado a cliente 
    });

    $('#modal_facturadoA_confirm').on('click', function(event) {
        let $select = $("#product_edit_facturadoa").selectize();
        let selectize = $select[0].selectize;
        selectize.setValue(0); //Facturado a cliente 
    });

    /* Evita problema de doble calendario en firefox*/
    /* $('input[type=date]').on('click', function(event) {
        var isFirefox = typeof InstallTrigger !== 'undefined';
        if (isFirefox) {
            event.preventDefault();
        }
    }); */


    
});

/* Peticion al API retornara objeto con la informacion si existe el producto */

class Producto {
    constructor(codigo, nombre, cantidad, precio, descuento) {
      this.codigo = codigo;
      this.nombre = nombre;
      this.cantidad = cantidad;
      this.precio = precio;
      this.descuento = descuento;
    }
  }

function validaProducto(codProducto, inputHTML) {
    $.ajax({
        type: 'get',
        url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=validaProducto',
        data: { codProducto: codProducto },

        success: function(response) {
            let prodIdentificado = JSON.parse(response).producto;
            console.log(prodIdentificado);

            let rows = document.getElementsByName("codigos_prod");

            for (i = 0, total = rows.length; i < total; i++) {
                if (rows[i] === inputHTML) {
                    position = i;
                    if (prodIdentificado != null) {

                        document.getElementsByName("nombres_prod")[position].value = prodIdentificado.Nombre;
                        document.getElementsByName("cants_prod")[position].value = 1;
                        document.getElementsByName("precios_prod")[position].value = Number(prodIdentificado.PrecA);
                        document.getElementsByName("desc_prod")[position].value = getPorcentDescuento();

                    } else {
                        document.getElementsByName("nombres_prod")[position].value = "";
                        document.getElementsByName("cants_prod")[position].value = 0;
                        document.getElementsByName("precios_prod")[position].value = "";
                        document.getElementsByName("desc_prod")[position].value = 0;
                    }

                }


            }



        },
        error: function(error) {
            console.error(error.statusText);
        }

    });



}

function getPorcentDescuento(){
    let facturadoA = document.getElementById('product_edit_facturadoa').value;
    if (facturadoA == '0'){
        return 90;
    }else{
        return 0;
    }
}

function getProductos() {
    
    let ArrayProductos = [];

    let rows = document.getElementsByName("codigos_prod");

    for (i = 0, total = rows.length; i < total; i++) {
    
        let codigoProd = document.getElementsByName("codigos_prod")[i].value; 
        let nombreProd = (document.getElementsByName("nombres_prod")[i].value).trim(); 
        let cantProd = document.getElementsByName("cants_prod")[i].value;
        let precioProd = document.getElementsByName("precios_prod")[i].value ;
        let descuentoProd = document.getElementsByName("desc_prod")[i].value ;

        if (codigoProd.length != 0 
            && nombreProd.length != 0 
            && cantProd.length != 0 
            && precioProd.length != 0 
            && descuentoProd.length != 0 ) {
                let producto = new Producto(codigoProd, nombreProd, cantProd, precioProd, descuentoProd);
                ArrayProductos.push(producto);
        }
        
    }

    return ArrayProductos;
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
       
    },
    edit_form: function() {
        // form variables
        var $product_edit_form = $('#product_edit_form'),
            $product_edit_submit_btn = $('#product_edit_submit');


        // submit form
        $product_edit_submit_btn.on('click', function(e) {
            e.preventDefault();
            let codigoMNT = document.getElementById("codMantenimiento").innerHTML;
            let productosArray = JSON.stringify(getProductos());
            var form_serialized = JSON.stringify($product_edit_form.serializeObject(), null, 2);
            //UIkit.modal.alert('<p>Producto data:</p><pre>' + form_serialized + '</pre>');
            console.log(getProductos());
            
            let objectForm = JSON.parse(form_serialized);
            let product_ordenFisica = objectForm.product_ordenFisica;
            console.log(product_ordenFisica);

            var isOrdenFisicaValida = false;

            /*Validacion de codigo de mantenimiento fisico sea unico*/
            $.ajax({
                url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=validaOrdenFisica',
                method: 'GET',
                data: { product_ordenFisica: product_ordenFisica },

                success: function(response) {
                    console.log(response);
                    response = JSON.parse(response);
                   
                    if (response.status == 'OK') {
                        isOrdenFisicaValida = true;
                        UIkit.modal.alert(response.mensaje, {labels: {'Ok': 'Listo'}} );
                        saveData();

                    } else if (response.status == 'FAIL') {
                        UIkit.modal.alert(response.mensaje, {labels: {'Ok': 'Ok'}});
                    } else {
                        console.error(response);
                    }
                },
                error: function(error) {
                    alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                }

            });

            /* Evia Fourmulario Serializado y el array de productos al API para ser guardados en las tablas*/
            function saveData(){
                UIkit.modal.confirm('Confirme, actualizar informacion de la orden de trabajo y agregar los repuestos indicados a la orden ' + codigoMNT + ' ?', function() {
                    $.ajax({
                        url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=updateOrden',
                        method: 'GET',
                        data: { formData: form_serialized, productosArray: productosArray },
    
                        success: function(response) {
                            console.log(response);
                            response = JSON.parse(response);
                           
                            if (response.status == 'OK') {
                                UIkit.modal.alert(response.mensaje, {labels: {'Ok': 'Listo'}} );
                                setTimeout(() => {
                                    location.assign('index.php?&action=mantenimientosAG');
                                }, 3000);
                               
                            } else if (response.status == 'FAIL') {
                                UIkit.modal.alert(response.mensaje, {labels: {'Ok': 'Ok'}});
                            } else{
                                console.error(response);
                            }
                        },
                        error: function(error) {
                            alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                        }
    
                    });
                }, {labels: {'Ok': 'Si, actualizar y registrar', 'Cancel': 'No'}});
            }
            

            
        })
    }
    
};