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

