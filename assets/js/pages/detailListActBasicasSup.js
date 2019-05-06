
$(function() {

    var arrayItems1xmes= ['ITEM0014','ITEM0016']

    app = {
        init: function() {
            app.countTotalScores();
        },
        countTotalScores(){
            let items = $('span[data-codcheckitem]');
            let totalGeneral = 0;
            let totalMaximo = 0;
            let cantidadItems = 0;
            items.each(function(index) {
                let valueItem = $(this).attr("data-codcheckitem");
                totalMaximo += 20;
                cantidadItems++;
                totalGeneral += app.countScoreOfItem(valueItem); //Pasamos el codigo del item para la suma
                
            });
            /* console.log('Puntaje total:' + totalGeneral + '/' + totalMaximo);
            console.log('Equivalente:' + (totalGeneral / cantidadItems)); */

            $("#totalGeneralShow").html(totalGeneral + '/' + totalMaximo);
            $("#totalGeneralEquivalenteShow").html((totalGeneral / cantidadItems).toFixed(2) + '/'+ cantidadItems);
            
           
        },
        countScoreOfItem(tipoItem){
            let items = $('i[data-codCheck="'+tipoItem+'"]'); //Recuperamos los items con el custom attribute
            let totalScore = 0;
            items.each(function(index) {
                    if ($(this).attr("data-codCheckValue")==1) {
                        totalScore += 5;
                        if (arrayItems1xmes.includes(tipoItem)) { // Si es del tipo 1 al mes y existe 1 registro retornar total maximo
                            $('#total_'+tipoItem).html(totalScore);
                            return totalScore = 20;
                        }
                    }
                });

            $('#total_'+tipoItem).html(totalScore);
            return totalScore;
        }
    };
    
    app.init(); // Inicializacion de estilos altair y carga de objetos dinamicos

    

    // De select Supervisor
    $('#selectSupervisor').on('change', function(event){
        
    });

    

});
