
$(function() {

    var arrayItems1xmes= [];
    var arrayItems2xmes= [];

    app = {
        init: function() {
           app.loadCodItems1XMes();
           app.loadCodItems2XMes();
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
            let contItems = 0;  //Determina cuantos checks en la fila existen
            items.each(function(index) {
               
                    if ($(this).attr("data-codCheckValue")==1) {
                        
                        contItems++;     
                        
                        if (arrayItems1xmes.includes(tipoItem)) { // Si es del tipo 1 al mes y existe 1 registro retornar total maximo
                            $('#total_'+tipoItem).html(totalScore);
                            return totalScore = 20;
                        }else if (arrayItems2xmes.includes(tipoItem) && contItems >=2) {
                            $('#total_'+tipoItem).html(totalScore);
                            return totalScore = 20;
                        }else if (arrayItems2xmes.includes(tipoItem) && contItems == 1) {
                            $('#total_'+tipoItem).html(totalScore);
                            return totalScore = 10;
                        }else{
                            totalScore += 5;
                        }
                    }
                });

            $('#total_'+tipoItem).html(totalScore);
            return totalScore;
        },
        loadCodItems1XMes(){
            fetch(`views/modulos/ajax/API_supervisores.php?action=getActividadesByCondicion&condicion=1XMES`)
            .then( response => response.json())
            .then(function (data){
                
                if (data.status == 'OK') {
                    let arrayItems = data.respuesta;
                
                    arrayItems.forEach(function(element) {
                        arrayItems1xmes.push(element.Codigo.trim());
                    });

                    console.log(arrayItems1xmes);
                    app.countTotalScores();
                }else{
                    alert('No se ha podido cargar reglas de validacion, informe a sistemas');
                }
                
            })
            .catch( error => console.log(error))
        },
        loadCodItems2XMes(){
            fetch(`views/modulos/ajax/API_supervisores.php?action=getActividadesByCondicion&condicion=2XMES`)
            .then( response => response.json())
            .then(function (data){
               
                if (data.status == 'OK') {
                    let arrayItems = data.respuesta;
                
                    arrayItems.forEach(function(element) {
                        arrayItems2xmes.push(element.Codigo.trim());
                    });
    
                    console.log(arrayItems2xmes);
                    app.countTotalScores();
                }else{
                    alert('No se ha podido cargar reglas de validacion, informe a sistemas');
                }
                
            })
            .catch( error => console.log(error))
        }
    };
    
    app.init(); // Inicializacion de estilos altair y carga de objetos dinamicos

    

    // De select Supervisor
    $('#selectSupervisor').on('change', function(event){
        
    });

    

});
