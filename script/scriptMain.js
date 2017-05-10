var  app;
$("body").ready(function(){

    app = new Sotero();
    app.showListFiles();
    app.setFunticionTextAndCodeMode();
    app.setFunticionsButtonsMenu();


    $('input[type=file]').change(function () {
        console.log(this.files[0]);
    });

});



