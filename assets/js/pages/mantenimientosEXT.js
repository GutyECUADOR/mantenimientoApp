$(function() {

    /* Evita problema de doble calendario en firefox*/
    $('input[type=date]').on('click', function(event) {
        var isFirefox = typeof InstallTrigger !== 'undefined';
        if (isFirefox) {
            event.preventDefault();
        }
    });
     
    config.date_range();

    $('.anularCita').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        UIkit.modal.confirm('Está seguro que desea anular el mantenimiento externo ' + codigoMNT + ' ?', function() {
            $.ajax({
                url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=anularExterno',
                method: 'GET',
                data: 'codigoMNT=' + codigoMNT,

                success: function( response ) {
                    console.log(response);
                    response = JSON.parse(response);
                    if (response.status == 'OK') {

                        UIkit.modal.alert(response.mensaje, {center: true, labels: {'Ok': 'Ok'}}).on('hide.uk.modal', function() {
                            
                            location.reload();
                        });
                       
                    }else{
                        UIkit.modal.alert(response.mensaje, {center: true, labels: {'Ok': 'Ok'}}).on('hide.uk.modal', function() {
                            
                            location.reload();
                        });
                    }
                    
                },
                error: function(error) {
                    alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                }

            });
        },  {labels: {'Ok': 'Si', 'Cancel': 'Cancelar'}});


    });

    /*Funcion para generar el reporte segun codigo de mantenimiento*/
    $('.showInforme').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        alert('Generando reporte con ID : ' + codigoMNT);
        window.open('reportes/hojaMantenimientoExternosByID.php?codigoMNT=' + codigoMNT);
        
    });

   

});

config = {
    // date range
    date_range: function() {
        var $dp_start = $('#uk_dp_proxMant');

        var $dp_start = $('#uk_dp_start'),
            $dp_end = $('#uk_dp_end');

        var start_date = UIkit.datepicker($dp_start, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });

        var start_date = UIkit.datepicker($dp_start, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });

        var end_date = UIkit.datepicker($dp_end, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });

    }
}
