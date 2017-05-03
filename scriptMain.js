$("body").ready(function(){
    getListFiles();

    $('#bt-codemode').click(function(){
        $("#inp-text-content").show();
        $("#inp-text-content2").hide();
    });
});