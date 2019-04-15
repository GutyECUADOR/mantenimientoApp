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
                            <button class="md-btn md-btn-primary"><i class="md-list-addon-icon material-icons"></i></button>
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
                myCliente = null;
                cotizacion.cliente = null;
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
                console.log(cliente);
                app.displayInfoCliente(cliente);
            })
            .catch( error => console.log(error))

        },
        save_solicitud: function () {

            var modalBlocked = UIkit.modal.blockUI('<div class=\'uk-text-center\'>Realizando, espere por favor...<br/><img class=\'uk-margin-top\' src=\'assets/img/spinners/spinner.gif\' alt=\'\'>');
                modalBlocked.show();

            console.log(solicitud);
            let formData = new FormData();
            formData.append('solicitud', JSON.stringify(solicitud));
            
            fetch('views/modulos/ajax/API_supervisores.php?action=saveActividadesBasicas',{
                method: 'POST', 
                body: formData
            })
            .then( response =>  response.json())
            .then( responseJSON => {
                console.log(responseJSON);
                modalBlocked.hide();

                if (responseJSON.status == 'OK') {
                    UIkit.modal.alert(`${responseJSON.mensaje} ${responseJSON.respuesta.CAB.mensaje}`, { center: true, labels: { 'Ok': 'Ok' } }).on('hide.uk.modal', function () {
                        location.href = "index.php?&action=checkListSupervisoresBasicas";
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

    // De select Supervisor
    $('#selectSupervisor').on('change', function(event){
        let valor = this.value;
        solicitud.supervisor = valor;
    });

    // De select Semana
    $('#selectSemana').on('change', function(event){
        let valor = this.value;
        solicitud.semana = valor;
    });

    // De select Semana
    $('#selectBodega').on('change', function(event){
        let valor = this.value;
        solicitud.bodega = valor;
    });

    /*Accions */
    $('#save_form_submit').on('click', function(e) {
        e.preventDefault();
        console.log(solicitud);
        

    });


});

/* Clases Utilizadas en esta seccion */

class Solicitud {
    constructor() {
        this.cliente = null,
        this.tipoMantenimiento = null,
        this.serieModelo = null,
        this.tipoEquipo = null,
        this.fecha = new Date(),
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