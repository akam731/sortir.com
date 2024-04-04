document.addEventListener('DOMContentLoaded', function() {

    var selectElement = document.getElementById('event_update_place');
    selectElement.id = 'event_place';
    const placeSelect = document.getElementById('event_place');
    replaceDetails(placeSelect.value);

    /* Création du tableau contenant toutes les options du select places */
    var placeSelectOptions = [];
    const options = placeSelect.options;
    for (var i = 0; i < options.length; i++) {
        const rank = findRank(options[i].value);
        /* Cette ligne permet de mettre en classe, l'id de la ville */
        options[i].className  = "City_id_"+placesData[rank].City_id;
        placeSelectOptions.push(options[i]);
    }

    /* Fonction qui remplace les détails de la place grâce à son id*/
    function replaceDetails(id){
        const selectedValue = id;
        placesData.forEach(function(place) {
            if(place.id == selectedValue){
                document.getElementById('place_rue').innerText = place.street;
                document.getElementById('place_zip').innerText = place.zip_code;
                document.getElementById('place_coord').innerText = place.latitude + " / " +place.longitude;
            }
        });
    }

    /*  Fonction qui adapte les détails de la place en focntion du choix dans le select  */
    placeSelect.addEventListener('change', function() {
        const selectedValue = placeSelect.value;
        replaceDetails(selectedValue)
    });


    const citySelect = document.getElementById('citySelect');

    /*  Fonction qui adapte la select des places en focntion du choix dans le select city  */
    citySelect.addEventListener('change', function() {
        let citySelectValue = citySelect.value;
        resetPlaceOptions();
        if(citySelectValue != "all") {
            citySelectValue = "City_id_"+citySelectValue;
            for (let i = 0; i < placeSelectOptions.length; i++) {
                if (placeSelectOptions[i].className == citySelectValue){
                    placeSelect.appendChild(placeSelectOptions[i]);
                }
            }
        }else{
            addPlaceOptions();
        }

        placeSelect.options[0].selected = true;
        replaceDetails(placeSelect.options[0].value);

    });

    /* Fonctions qui remetent à 0 les options du select place */
    function resetPlaceOptions() {
            placeSelect.innerText = '';
    }
    function addPlaceOptions() {
        for (let i = 0; i < placeSelectOptions.length; i++) {
            placeSelect.add(placeSelectOptions[i]);
        }
    }

    /* Fonction qui remetde récupérer la position d'une place grâce à son id */
    function findRank(id) {
        var i;
        for (i = 0; i < placesData.length; i++) {
            if (placesData[i].id == id) {
                break;
            }
        }
        return i;
    }










    /* Gestion de l'affichage du formulaire de créatuib de place */

    let isFormVisible = false;
    const placeForm = document.getElementById('add_place');
    const placeFormButton = document.getElementById('placeFormButton');

    placeFormButton.addEventListener('click',placeFormManager);

    function placeFormManager() {

        if (isFormVisible){
            placeForm.style.display = 'none';
            isFormVisible = false;
        }else{
            placeForm.style.display = 'block';
            isFormVisible = true;
        }

    }

    if (isPlaceCreated == true){
        document.getElementById('add_place').style.display = "none";
        replaceDetails(placeSelect[placeSelect.length - 1].value);
        placeSelect.options[placeSelect.options.length - 1].selected = true;
    }

    document.getElementById('closeAddingPLace').addEventListener('click', function (){
        document.getElementById('add_place').style.display = "none";
        isFormVisible = false;
    })





});