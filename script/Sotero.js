Sotero = function(){

    this.currentFile = "";
    this.managerFile = new ManagerFile();


}

Sotero.prototype.getManagerFile = function(){
    return this.managerFile;
};

Sotero.prototype.setCurrentFile = function(filepath){
    this.currentFile = filepath;
}

Sotero.prototype.showListFiles = function(){
   this.getManagerFile().getListFiles( function(listfile){

       $("#list-file").html("");

       for(var i = 0; i< data_json.length; i++){
           $("#list-file").append('<div class="item-list-file" filepath='+
               listfile [i]["filename"] +'> <img src="ui/icon_li.png">' +
               listfile [i]["title"]+'</div>'
           );
       }

       $(".item-list-file").bind('click', function(event){
           filepath = $(this).attr("filepath");
           app.setCurrentFile(filepath);
           app.getManagerFile().getDataFiles( filepath );
       });

   } );
};


Sotero.prototype.setFunticionTextAndCodeMode = function (){

    $('#bt-codemode').click(function(){
        $(this).hide();
        $('#bt-textmode').show();
        $("#inp-text-content").show();
        $("#inp-text-content2").hide();
    });

    $('#bt-textmode').click(function(){
        $(this).hide();
        $('#bt-codemode').show();
        $("#inp-text-content").hide();
        $("#inp-text-content2").show();

        app.setContentInFrameEditor($("#inp-text-content").val())
    });

};


Sotero.prototype.setFunticionsButtonsMenu = function(){


    $("#bt-newpost").click(function(){
        app.setCurrentFile("newfile");

        $("#list-file").append('<div class="item-list-file" filepath=""><img src="ui/icon_li.png">Novo Post</div>');

    });


    $("#bt-savepost").click(function(){
        if(app.currentFile!="newfile"){
            app.getManagerFile().saveFile(app.currentFile, $("#inp-text-title").val(), $("#inp-text-tag").val(),
            $("#inp-text-date").val(),  $("#inp-text-abstract").val(), $("#inp-text-content").val());
        }else{

            app.getManagerFile().saveFile("gerator/posts/"+$("#inp-text-title").val().replace(" ", "")+".txt" , $("#inp-text-title").val(), $("#inp-text-tag").val(),
            $("#inp-text-date").val(),  $("#inp-text-abstract").val(), $("#inp-text-content").val());

            app.setCurrentFile("");
        }
    });


    $("#bt-settings").click(function(){
        alert("config")
    });
    $("#bt-createblog").click(function(){
        app.getManagerFile().createBlog();
    });
    $("#bt-about").click(function(){
        alert("about")
    });

};


Sotero.prototype.setContentInFrameEditor= function(content){

    var ifrm = document.getElementById('inp-text-content2');
    ifrm = ifrm.contentWindow || ifrm.contentDocument.document || ifrm.contentDocument;
    ifrm.document.open();
    ifrm.document.write("<html><head> <link href='gerator/style/style_pages.css' rel='stylesheet'></head> <body style='padding:20px'>" +
        "<div class='abstract'>"+content+"</div></body></html>");
    ifrm.document.close();

}
