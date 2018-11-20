(function() {

    $('.anularCita').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        UIkit.modal.confirm('Está seguro que desea anular el mantenimiento ' + codigoMNT + ' ?', function() {
            $.ajax({
                url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=anular',
                method: 'GET',
                data: 'codigoMNT=' + codigoMNT,

                success: function() {
                    alert('Mantenimiento anulado: ' + codigoMNT + 'agende nuevo mantenimiento desde la sección mantenimientos.');
                    location.reload();
                },
                error: function(error) {
                    alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                }

            });
        });


    });

    $('.aprobarCita').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        UIkit.modal.confirm('Dar por culminado el mantenimiento ' + codigoMNT + ' ?', function() {
            $.ajax({
                url: 'views/modulos/ajax/API_mantenimientosEQ.php?action=aprobar',
                method: 'GET',
                data: 'codigoMNT=' + codigoMNT,

                success: function() {
                    alert('Mantenimiento finalizado: ' + codigoMNT);
                    location.reload();
                },
                error: function(error) {
                    alert('No se pudo completar la operación. #' + error.status + ' ' + error.statusText);
                }

            });
        });


    });

    /*Funcion para generar el reporte segun codigo de mantenimiento*/
    $('.showInforme').on('click', function(event) {
        let htmlElement = event.currentTarget;
        let codigoMNT = htmlElement.getAttribute("data-mantenimiento");

        $.ajax({
            url: 'reportes/hojaMantenimientoByID.php',
            method: 'GET',
            data: 'codigoMNT=' + codigoMNT,

            success: function() {
                alert('Generando reporte con ID : ' + codigoMNT);
                window.open('reportes/hojaMantenimientoByID.php?codigoMNT=' + codigoMNT);
            }

        });
    });

})();