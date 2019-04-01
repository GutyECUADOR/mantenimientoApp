var app = new Vue({
    el: '#formActividadesBasicas',
    data () {
        return {
            txtCIRUC: '',
            usuarioIdentificado: '',
            info: null
        }
    },
    methods: {
        greet: function (event) {
          // `this` inside methods point to the Vue instance
          console.log(this.txtCIRUC);
          
        },
        searchCIRUC () {
            altair_helpers.content_preloader_show('regular'); /* Loading SVG */
            axios
                .get(`http://localhost/PHPProjects/mantenimientoApp/views/modulos/ajax/API_estadisticas.php?action=test`)
                .then(response => {
                    this.usuarioIdentificado = response.data.data;
                    console.log(response);
                    console.log(this.usuarioIdentificado);
                    altair_helpers.content_preloader_hide(); /* Stop Loading SVG */
                })
                .catch(error => console.log(error))
        }
    },
    filters: {
        capitalize: function (value) {
          if (!value) return '(NO IDENTIFICADO)'
          value = value.toString()
          return value.charAt(0).toUpperCase() + value.slice(1)
        }
      }
    
  })


$(function() {

    
    app.init();
    var $save_form_submit = $('#save_form_submit');

    $save_form_submit.on('click', function(e) {
        e.preventDefault();
        app.save_form();
    });
});

app = {
    init: function() {
        var $todo_list = $('#todo_list');
        $todo_list.find('input:checkbox')
            .on('ifChecked', function(){
                $(this).closest('li').addClass('md-list-item-disabled');
            })
            .on('ifUnchecked', function(){
                $(this).closest('li').removeClass('md-list-item-disabled');
            });
    },
    save_form: function() {
        let $formMain = $('#formActividadesBasicas'); //ID formulario 

        let form_serialized = JSON.stringify($formMain.serializeObject(), null, 2);
        UIkit.modal.alert('<p>Confirme datos del formulario:</p><pre>' + form_serialized + '</pre>');
        
      
    }
};

