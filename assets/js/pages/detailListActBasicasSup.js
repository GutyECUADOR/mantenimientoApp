
$(function() {

    var arrayItems1xmes= [];
    var arrayItems2xmes= [];
    var arrayItems3xmes= [];

    app = {
        init: function() {
            app.loadCodItems1XMes();
           
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
            let contItems = items.length;
            
            console.log(tipoItem, contItems);
            
            let totalScore = 0;

            if(contItems == 0){
                totalScore = 0;
            }else if (arrayItems1xmes.includes(tipoItem) && contItems >= 1) {
                totalScore = 20;
            }else if (arrayItems2xmes.includes(tipoItem) && contItems == 1) { 
                totalScore = 10;
            }else if (arrayItems2xmes.includes(tipoItem) && contItems >= 2) { 
                totalScore = 20;
            }else if (arrayItems3xmes.includes(tipoItem) && contItems == 1) {
                totalScore = 5;
            }else if (arrayItems3xmes.includes(tipoItem) && contItems == 2) {
                totalScore = 13; 
            }else if (arrayItems3xmes.includes(tipoItem) && contItems >= 3) {
                totalScore = 20
            }else{
                totalScore == 0;
            }
                  
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

                    
                    console.log('Items1xmes:', arrayItems1xmes);
                    app.loadCodItems2XMes();
                     
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
    
                   
                    console.log('Items2xmes:', arrayItems2xmes);
                    app.loadCodItems3XMes();
                   
                }else{
                    alert('No se ha podido cargar reglas de validacion, informe a sistemas');
                }
                
            })
            .catch( error => console.log(error))
        },
        loadCodItems3XMes(){
            fetch(`views/modulos/ajax/API_supervisores.php?action=getActividadesByCondicion&condicion=3XMES`)
            .then( response => response.json())
            .then(function (data){
               
                if (data.status == 'OK') {
                    let arrayItems = data.respuesta;
                
                    arrayItems.forEach(function(element) {
                        arrayItems3xmes.push(element.Codigo.trim());
                    });
    
                    console.log('Items3xmes:', arrayItems3xmes);
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
