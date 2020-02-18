$(function() {

    var solicitud = new Solicitud();

    app = {
        init: function() {
            this.altair_init();
        },
        altair_init: function () {
            var $dp_start = $('#uk_dp_fecha'),
            $dp_end = $('#uk_dp_proxMant');

            var start_date = UIkit.datepicker($dp_start, {
                format: 'YYYY-MM-DD',
                i18n: {
                    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
                }
            });

            UIkit.datepicker($dp_end, {
                format: 'YYYY-MM-DD',
                i18n: {
                    months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                    weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
                }
            });

        },
        renderBusquedaClientes: function (arrayItems){
            $('#resultadosBusquedaClientes').find("li").remove();
            //$('#resultadosBusquedaClientes').find("tr:gt(0)").remove();
            arrayItems.forEach(item => {
                let row = `
                    <li>
                        <div class="md-list-addon-element">
                            <button class="md-btn md-btn-primary btnAddCliente" data-ruc="${item.RUC.trim()}"><i class="md-list-addon-icon material-icons"></i></button>
                        </div>
                        <div class="md-list-content">
                            <span class="md-list-heading">${item.NOMBRE.trim()}</span>
                            <span class="uk-text-small uk-text-muted">RUC: ${item.RUC.trim()}</span>
                        </div>
                    </li>
                `;
                $("#resultadosBusquedaClientes").append(row);
               
            });
        },
        displayInfoCliente: function (cliente){
            if (cliente) {
                const myCliente = new Cliente(cliente.RUC, cliente.NOMBRE, cliente.EMAIL, cliente.TELEFONO, cliente.VENDEDOR, cliente.TIPOPRECIO, cliente.DIASPAGO, cliente.FPAGO);
                solicitud.cliente = myCliente;
                $('#inputNombre').val(cliente.NOMBRE.trim());
                $('#inputCorreo').val(cliente.EMAIL.trim());
                $('#inputDireccion').val(cliente.DIRECCION.trim());
                $('#inputTelefono').val(cliente.TELEFONO.trim());

            } else {
                solicitud = null;
                solicitud.cliente = null;
                $('#inputNombre').val('(Sin identificar)');
                $('#inputCorreo').val('');
                $('#inputDireccion').val('');
                $('#inputTelefono').val('');
                

            }
        },
        buscarClientes: function (terminoBusqueda, tipoBusqueda) {
            fetch(`views/modulos/ajax/API_mantenimientosEQ.php?action=searchCliente&terminoBusqueda=${terminoBusqueda}&tipoBusqueda=${tipoBusqueda}`)
            .then( response => response.json())
            .then(function (data){
                let arrayClientes = data.data;
                if (data.status == 'OK' && arrayClientes.length > 0) {
                    console.log(arrayClientes);
                    app.renderBusquedaClientes(arrayClientes);
                }else{
                    alert('No existen resultados');
                }
                
            })
            .catch( error => console.log(error))

        },
        validaCliente: function (RUC) {
            fetch(`views/modulos/ajax/API_mantenimientosEQ.php?action=getInfoCliente&ruc=${RUC}`)
            .then( response => response.json())
            .then(function (data){
                let cliente = data.data;
                if (cliente) {
                    console.log(cliente);
                    app.displayInfoCliente(cliente);
                }
                
            })
            .catch( error => console.log(error))

        },
        validaSolicitud: function () {
            if (solicitud.cliente === null) {
                UIkit.modal.alert('Indique un cliente por favor.');
                return false;
            }
    
            if (solicitud.tipoEquipo === null) {
                UIkit.modal.alert('Seleccione tipo de equipo por favor.');
                return false;
            }
    
            if (solicitud.tipoMantenimiento === null) {
                UIkit.modal.alert('Seleccione tipo de mantenimiento por favor.');
                return false;
            }

            if (solicitud.tecnico === null) {
                UIkit.modal.alert('Seleccione tecnico por favor.');
                return false;
            }

            if (solicitud.serieModelo === null) {
                UIkit.modal.alert('Indique una serie o modelo del equipo.');
                return false;
            }

            if (solicitud.bodega === null) {
                UIkit.modal.alert('Indique un local/bodega.');
                return false;
            }

            if (solicitud.fechaPrometida === null) {
                UIkit.modal.alert('Indique la fecha de entrega del equipo.');
                return false;
            }

            return true;
           
        },
        save_solicitud: function () {

            var modalBlocked = UIkit.modal.blockUI('<div class=\'uk-text-center\'>Realizando, espere por favor...<br/><img class=\'uk-margin-top\' src=\'assets/img/spinners/spinner.gif\' alt=\'\'>');
                modalBlocked.show();

            console.log(solicitud);
            let formData = new FormData();
            formData.append('solicitud', JSON.stringify(solicitud));
            
            fetch('views/modulos/ajax/API_mantenimientosEQ.php?action=saveMantenimientoExterno',{
                method: 'POST', 
                body: formData
            })
            .then( response => response.json())
            .then( responseJSON => {
                console.log(responseJSON);
                modalBlocked.hide();

                if (responseJSON.status == 'OK' || responseJSON.status == 'ok') {
                    UIkit.modal.alert(`${responseJSON.mensaje}`, { center: true, labels: { 'Ok': 'Ok' } }).on('hide.uk.modal', function () {
                        location.href = "index.php?&action=mantenimientosEXT";
                    });
                }
            })
            .catch( error => console.log(error))
          
        }
    };
    
    app.init(); // Inicializacion de estilos altair y carga de objetos dinamicos



    /* Eventos y Acciones */
    $("#inputRUC").on("keyup change", function(event) {
        let RUC = $(this).val();
        app.validaCliente(RUC);
    });

    $("#searchClienteModal_Button").on('click', function(event) {
        event.preventDefault();
        
        let terminoBusqueda = document.getElementById("terminoBusquedaModalCliente").value;
        let tipoBusqueda = document.getElementById("tipoBusquedaModalCliente").value;
        if (terminoBusqueda.length > 0) {
            app.buscarClientes(terminoBusqueda, tipoBusqueda); 
        }else{
            alert('Indique un termino de busqueda');
        }
        
    });

    $("#resultadosBusquedaClientes").on('click', '.btnAddCliente', function(event) {
        event.preventDefault();

        let RUC = $(this).data('ruc');
        $('#inputRUC').val(RUC);
        app.validaCliente(RUC);
        UIkit.modal("#modal_AgendarNuevo").hide();
    });

    

    // De select tipoMantenimiento
    $('#select_tipoMantenimiento').on('change', function(event){
        let valor = this.value;
        solicitud.tipoMantenimiento = valor;
    });

    // De select tipoEquipo
    $('#select_tipoEquipo').on('change', function(event){
        let valor = this.value;
        solicitud.tipoEquipo = valor;
    });

    // De select tipoEquipo
    $('#select_tecnico').on('change', function(event){
        let valor = this.value;
        solicitud.tecnico = valor;
    });

    // De select tipoEquipo
    $('#select_bodega').on('change', function(event){
        let valor = this.value;
        solicitud.bodega = valor;
    });

    // De input fecha
    $('#uk_dp_fecha').on('change', function(event){
        let valor = this.value;
        solicitud.fechaPrometida = valor;
    });

    // Input SerieModelo
    $('#inputSerieModelo').on('keyup change', function(event){
        let valor = this.value;
        solicitud.serieModelo = valor;
    });

    // Input Comentario
    $('#inputComentario').on('keyup change', function(event){
        let valor = this.value;
        solicitud.comentario = valor;
    });

    /*Accions */
    $('#save_form_submit').on('click', function(e) {
        e.preventDefault();
        console.log(solicitud);
        if (app.validaSolicitud()) {
            UIkit.modal.confirm(`Confirme, desea registrar el documento ?` , function() {
                console.log(JSON.stringify(solicitud));
                app.save_solicitud();
            }, {labels: {'Ok': 'Si, registrar', 'Cancel': 'No'}});
        }
        
    });


});

/* Clases Utilizadas en esta seccion */

class Solicitud {
    constructor() {
        this.cliente = null,
        this.tipoMantenimiento = null,
        this.tecnico = null,
        this.bodega = null,
        this.serieModelo = null,
        this.tipoEquipo = null,
        this.fecha = new Date(),
        this.fechaPrometida = null,
        this.comentario = null
    }
}


class Cliente {
    constructor(RUC, nombre, email, telefono, vendedor, tipoPrecio, diasPago, formaPago) {
      this.RUC = RUC;
      this.nombre = nombre;
      this.email = email;
      this.telefono = telefono;
      this.vendedor = vendedor;
      this.tipoPrecio = tipoPrecio;
      this.diasPago = diasPago;
      this.formaPago = formaPago;
      
    }

    getTipoPrecio() {
        return + this.tipoPrecio;
    }
}