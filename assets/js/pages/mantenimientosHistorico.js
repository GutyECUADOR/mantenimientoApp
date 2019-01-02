new Vue({
    el: '#page_content',
    data() {
        return {
            mantenimientos: []
        }
    },
    methods: {
        searchMant(){
            // EndPoint de API
            axios.get('views/modulos/ajax/API_estadisticas.php?action=getHistorico')
            .then(function (response) {
                console.log(response.data.data);
                
            })
            .catch(function (error) {
                console.log(error);
            })
            .then(function () {
                console.log(JSON.stringify(this.mantenimientos));
            });

        },
        showMessage(){
            console.log('Hola como estas');
        }
    },
})

/* 

$(function() {
    
   
    altair_form_adv.date_range();
    fechaActual = new Date().toISOString().slice(0, 10);
    console.log(fechaActual);


});
 */
/* 
altair_form_adv = {

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

        var end_date = UIkit.datepicker($dp_end, {
            format: 'YYYY-MM-DD',
            i18n: {
                months: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                weekdays: ['DOM', 'LUN', 'MAR', 'MIE', 'JUE', 'VIE', 'SAB']
            }
        });

        $dp_start.on('change', function() {
            end_date.options.minDate = $dp_start.val();
            setTimeout(function() {
                $dp_end.focus();
            }, 300);
        });

        $dp_end.on('change', function() {
            start_date.options.maxDate = $dp_end.val();
        });
    }
} */