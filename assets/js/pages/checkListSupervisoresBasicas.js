
$(function() {

    solicitud = new Solicitud();

    app = {
        init: function() {
            this.altair_init();
            this.loadChecks();
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
        }
        ,
        save_solicitud: function() {
            //let $formMain = $('#formActividadesBasicas'); //ID formulario 
            //let form_serialized = JSON.stringify($formMain.serializeObject(), null, 2);
            //UIkit.modal.alert('<p>Confirme datos del formulario:</p><pre>' + form_serialized + '</pre>');
            console.log(solicitud);
          
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
            console.log(solicitud);
        },
        updateObeservacionCheck(codigoBusqueda, valor){
            let ArrayItems = solicitud.checkItems;
            let objActualizar = ArrayItems.find(checkItem => checkItem.codigo === codigoBusqueda);
 
            objActualizar.comentario = valor;
             
         }
    };
    
    app.init(); // Inicializacion de estilos altair y carga de objetos dinamicos

    // Actualizacion de los campos CHECKED array del objeto solicitud
    $('#listCheckItems').on('ifChecked ifUnchecked', '.icheck', function(event){
        let codigo = this.id; // ID del objeto HTML
        let valor = this.value;
        app.updateItemCheck(codigo, valor);
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
        app.save_solicitud();

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

