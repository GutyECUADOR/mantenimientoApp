
$(function() {

    var solicitud = new Solicitud();

    app = {
        init: function() {
            this.altair_init();
            this.loadChecks();
            //this.altair_validations();
        },
        altair_init: function () {
            var $todo_list = $('#todo_list');
            $todo_list.find('input:checkbox')
                .on('ifChecked', function(){
                    $(this).closest('li').addClass('md-list-item-disabled');
                })
                .on('ifUnchecked', function(){
                    $(this).closest('li').removeClass('md-list-item-disabled');
                });
        },
        altair_validations: function() {
            var $formValidate = $('#formActividadesBasicas');
    
            $formValidate
                .parsley({
                    'excluded': 'input[type=button], input[type=submit], input[type=reset], input[type=hidden], .selectize-input > input'
                })
                .on('form:validated',function() {
                    altair_md.update_input($formValidate.find('.md-input-danger'));
                })
                .on('field:validated',function(parsleyField) {
                    if($(parsleyField.$element).hasClass('md-input')) {
                        altair_md.update_input( $(parsleyField.$element) );
                    }
                });
    
            window.Parsley.on('field:validate', function() {
                var $server_side_error = $(this.$element).closest('.md-input-wrapper').siblings('.error_server_side');
                if($server_side_error) {
                    $server_side_error.hide();
                }
            });
    
    
            // datepicker callback
            $('#val_birth').on('hide.uk.datepicker', function() {
                $(this).parsley().validate();
            });
        },
        loadChecks: function () {
            fetch('views/modulos/ajax/API_supervisores.php?action=getCheckListActBasicas')
            .then( response =>  response.json())
            .then(function (data){
                let jsonChecks = data.data;
                solicitud.checkItems = [];
                jsonChecks.forEach(checkAPI => {
                    let nuevoCheckItem = new CheckItem(checkAPI.Codigo.trim(), checkAPI.Titulo.trim(), checkAPI.Descripcion.trim());
                    solicitud.checkItems.push(nuevoCheckItem);
                });
                app.renderCheckList(solicitud.checkItems);
            })
            .catch( error => console.log(error))
        },
        save_solicitud: function() {

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
          
        },
        validaCanDoEvaluation: function() {

            var modalBlocked = UIkit.modal.blockUI('<div class=\'uk-text-center\'>Realizando, espere por favor...<br/><img class=\'uk-margin-top\' src=\'assets/img/spinners/spinner.gif\' alt=\'\'>');
                modalBlocked.show();
            let sesionEvaluador =  $('#txt_hidden_sessionEvaluador').val();

            fetch(`views/modulos/ajax/API_supervisores.php?action=countEvaluacionesSup&evaluador=${sesionEvaluador}&evaluado=${solicitud.supervisor}&semana=${solicitud.semana}`,{
                method: 'GET',
            })
            .then( response =>  response.json())
            .then( responseJSON => {
                console.log(responseJSON);
                modalBlocked.hide();

                    if (responseJSON.status == 'OK') {
                        let cantidadEvaluaciones = responseJSON.data.CantCheckList;
                        if (cantidadEvaluaciones == 0) {
                            app.save_solicitud();
                            console.log('Registrar');
                        }else if (cantidadEvaluaciones >= 1) {
                            console.log('Verifica si puede registrar en esta semana');
                            modalBlocked.show();
                            fetch(`views/modulos/ajax/API_supervisores.php?action=getCanDoEvaluation&evaluador=${sesionEvaluador}&evaluado=${solicitud.supervisor}&semana=${solicitud.semana}`)
                            .then( response =>  response.json())
                            .then( responseJSON => {
                                console.log(responseJSON);
                                modalBlocked.hide();
                
                                if (responseJSON.status == 'OK') {
                                    let cantEvaluaciones = responseJSON.data.CantCheckList;
                                    let EvaluadoName = responseJSON.data.EvaluadoName;
                                    let EvaluadorName = responseJSON.data.EvaluadorName;
                                    let semanaExist = responseJSON.data.MismaSemana;
                
                                    if (cantEvaluaciones < 4 && cantEvaluaciones != null && semanaExist == null) {
                                        app.save_solicitud();
                                    }else{
                                        UIkit.modal.alert(`Evaluacion negada: El usuario ${EvaluadorName} ya ha realizado la evaluacion para la semana ${responseJSON.data.MismaSemana}, a ${EvaluadoName}. Espere a la siguiente semana para evaluar nuevamente`, { center: true, labels: { 'Ok': 'Ok' } }).on('hide.uk.modal', function () {});
                                    }
                                   
                                }else{
                                    alert('Error, no se puedo comprobar si la evaluacion actual es valida ')
                                }

                            })
                            .catch( error => console.log(error))
                        }else{
                            alert('Error al validar evaluacion, informe a sistemas');
                        }
                    }

                /*  */
            })
            .catch( error => console.log(error))
          
        },
        renderCheckList: function (arrayItems){
            arrayItems.forEach(item => {
                let row = `
                <li>
                    <div class="md-list-addon-element">
                        <input type="checkbox" id="${item.codigo}" name="${item.codigo}" class="icheck" data-md-icheck />
                    </div>
                    <div class="md-list-content">
                        <span class="md-list-heading"> ${item.titulo} </span>
                        <span class="uk-text-small uk-text-muted">${item.descripcion}</span>
                    </div>
                    <div class="md-input-wrapper md-input-filled">
                        <input type="text" data-check="${item.codigo}" class="md-input icomment" placeholder="Observacion del item">
                        <span class="md-input-bar"></span>
                    </div>
                </li>
                `;
                $("#listCheckItems ul").append(row);
                $('#listCheckItems ul').iCheck({checkboxClass: 'icheckbox_md'});
                this.altair_init()
            });
        },
        updateItemCheck(codigoBusqueda){
           let ArrayItems = solicitud.checkItems;
            let objActualizar = ArrayItems.find(checkItem => checkItem.codigo === codigoBusqueda);
            objActualizar.checked = !objActualizar.checked;
        },
        updateObeservacionCheck(codigoBusqueda, valor){
            let ArrayItems = solicitud.checkItems;
            let objActualizar = ArrayItems.find(checkItem => checkItem.codigo === codigoBusqueda);
 
            objActualizar.comentario = valor;

        },
        countScore(){
            let ArrayItems = solicitud.checkItems;
            return ArrayItems.reduce(function(total, record){
                if(record.checked == true ){
                    return total + 1;
                } 
                else {
                    return total;
                }
              }, 0);
        }
    };
    
    app.init(); // Inicializacion de estilos altair y carga de objetos dinamicos

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

    // Actualizacion de los campos CHECKED array del objeto solicitud
    $('#listCheckItems').on('ifChecked ifUnchecked', '.icheck', function(event){
        let codigo = this.id; // ID del objeto HTML
        let valor = this.value;
        app.updateItemCheck(codigo, valor);
    });

    // Actualizacion de los campos COMENTARIO array del objeto solicitud
    $('#listCheckItems').on('keyup', '.icomment', function(event){
        let codigo = $(this).data("check"); // ID del objeto HTML
        let valor = this.value;
        app.updateObeservacionCheck(codigo, valor);
    });

    /*Accions */
    $('#save_form_submit').on('click', function(e) {
        e.preventDefault();
        console.log(solicitud);
        if (solicitud.supervisor === null) {
            UIkit.modal.alert('Seleccione supervisor por favor.');
            return;
        }

        if (solicitud.semana === null) {
            UIkit.modal.alert('Seleccione semana por favor.');
            return;
        }

        UIkit.modal.confirm(`Confirme, desea registrar el documento ? </br> El puntaje actual es de ${ app.countScore() } / ${solicitud.checkItems.length} `, function() {
            app.validaCanDoEvaluation();
        }, {labels: {'Ok': 'Si, registrar', 'Cancel': 'No'}});

       

    });


    

});


class Solicitud {
    constructor() {
        this.supervisor = null,
        this.semana = null,
        this.bodega = null,
        this.checkItems = []
    }
}


class CheckItem {
    constructor(codigo, titulo, descripcion) {
       this.codigo = codigo;
       this.titulo = titulo;
       this.descripcion = descripcion;
       this.checked = false;
       this.comentario = '';
        
    }
}

