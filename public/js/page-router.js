(function () {

    $.sammy('#main-routes', function(){
       this.get('#/', function(context){
            $('#soundboard-main').show();
       });

    });

})();