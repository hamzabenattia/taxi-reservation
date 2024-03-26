
$("#depart").keyup(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let rue = $("#depart").val();
    $.get('https://api-adresse.data.gouv.fr/search/', {
        q: rue,
        limit: 15,
        autocomplete: 1
    }, function (data, status, xhr) {
        let liste = "";
        $.each(data.features, function(i, obj) {
            // données phase 1 (obj.properties.label) & phase 2 : name, postcode, city
            // J'ajoute chaque élément dans une liste
            liste += '<li class="pl-8 pr-2 py-1 border-b-2 border-gray-100 relative cursor-pointer hover:bg-yellow-50 hover:text-gray-900"><a href="#" name="'+obj.properties.label+'" data-name="'+obj.properties.name+'" data-postcode="'+obj.properties.postcode+'" data-city="'+obj.properties.city+'">'+obj.properties.label+'</a></li>';
        });
        $('.adress-dep ul').html(liste);

        // ToDo: Au clic du lien voulu, on envoie l'info en $_POST
        $('.adress-dep ul>li').on("click","a", function(event) {
            // Stop la propagation par défaut
            event.preventDefault();
            event.stopPropagation();

            $("#depart").val($(this).attr("name"));

            $('.adress-dep ul').empty();
        });

    }).error(function () {
        // alert( "error" );
        $('.adress-des ul').empty();

    }).always(function () {
        // alert( "finished" );
    }, 'json');
});




$("#destination").keyup(function(event) {
    event.preventDefault();
    event.stopPropagation();

    let rue = $("#destination").val();
    $.get('https://api-adresse.data.gouv.fr/search/', {
        q: rue,
        limit: 15,
        autocomplete: 1
    }, function (data, status, xhr) {
        let liste = "";
        $.each(data.features, function(i, obj) {
            // données phase 1 (obj.properties.label) & phase 2 : name, postcode, city
            // J'ajoute chaque élément dans une liste
            liste += '<li class="pl-8 pr-2 py-1 border-b-2 border-gray-100 relative cursor-pointer hover:bg-yellow-50 hover:text-gray-900"><a href="#" name="'+obj.properties.label+'" data-name="'+obj.properties.name+'" data-postcode="'+obj.properties.postcode+'" data-city="'+obj.properties.city+'">'+obj.properties.label+'</a></li>';
        });
        $('.adress-des ul').html(liste);

        // ToDo: Au clic du lien voulu, on envoie l'info en $_POST
        $('.adress-des ul>li').on("click","a", function(event) {
            // Stop la propagation par défaut
            event.preventDefault();
            event.stopPropagation();

            $("#destination").val($(this).attr("name"));

            $('.adress-des ul').empty();
        });

    }).error(function () {
        $('.adress-des ul').empty();

        // alert( "error" );
    }).always(function () {
        // alert( "finished" );
    }, 'json');
});
