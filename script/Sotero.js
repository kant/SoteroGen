Sotero = function(){

    this.currentFile = "";
    this.managerFile = new ManagerFile();
    this.modeeditor = "text";
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

        app.modeeditor = "code";

        $(this).hide();
        $('#bt-textmode').show();
        $("#inp-text-content").show();
        $("#inp-text-content2").hide();
    });

    $('#bt-textmode').click(function(){

        app.modeeditor = "text";

        $(this).hide();
        $('#bt-codemode').show();
        $("#inp-text-content").hide();
        $("#inp-text-content2").show();

        app.setContentInFrameEditor($("#inp-text-content").val())
    });

    $("#bt-setimage").click(function(){
        if(app.modeeditor == "code"){
            $("#inp-image").trigger('click');
            var path = "teste.jpg"
            app.insertAtCaret("<img src='"+path+"'>");
        }
    });

    $("#bt-bold").click(function(){
        if(app.modeeditor == "code")
            app.insertAtCaret("<b></b>"  );
    });

    $("#bt-italic").click(function(){
        if(app.modeeditor == "code")
            app.insertAtCaret("<i></i>"  );
    });

    $("#bt-setcode").click(function(){
        if(app.modeeditor == "code")
            app.insertAtCaret("<div class='code'>  code here </div>"  );
    });

    $("#bt-setlink").click(function(){
        if(app.modeeditor == "code")
            app.insertAtCaret("<a class='link' href='' > link here</a>"  );
    });


    $("#bt-settitle").click(function(){
        if(app.modeeditor == "code")
            app.insertAtCaret("<div class='subtitle'> title here </div>")
       //$("#inp-text-content").val( $("#inp-text-content").val() +"<div class='subtitle'> title here </div>"  );
    });

    //code of http://jsfiddle.net/eghpf/2/
    $("#inp-text-content" ).keypress(function(event) {
        if((app.modeeditor == "code") && (event.keyCode == 13) && (event.shiftKey )){

            app.insertAtCaret("\r<p>\r</p>");
            var currentLineNumber= this.value.substr(0, this.selectionStart).split("\n").length;
            currentPosition = currentLineNumber+1;

        }

    });

    $("#inp-text-date").keyup(function(event){

        newstr = $("#inp-text-date").val();

        //se não for numero
        if ( isNaN( $("#inp-text-date").val()[ $("#inp-text-date").val().length-1] )   ){

            console.log($("#inp-text-date").val()[$("#inp-text-date").val().length-1]);

            $("#inp-text-date").val( $("#inp-text-date").val().substr(0, $("#inp-text-date").val().length-1) );
        }

        //se maior que o tamanho limite
        if( $("#inp-text-date").val().length > 9){
            $("#inp-text-date").val( $("#inp-text-date").val().substr(0, 10) );
        }

        //insere o caracter /
        if ( (newstr.length == 2) && ( newstr[2] != "/" )){
            $("#inp-text-date").val( $("#inp-text-date").val()+ "/" )
        }else if ( (newstr.length == 5) && ( newstr[4] != "/" )){
                $("#inp-text-date").val( $("#inp-text-date").val()+ "/" )
        }

    });
};

//code of  http://stackoverflow.com/questions/1064089/inserting-a-text-where-cursor-is-using-javascript-jquery
Sotero.prototype.insertAtCaret = function(text) {
    var txtarea = document.getElementById("inp-text-content");
    if (!txtarea) { return; }

    var scrollPos = txtarea.scrollTop;
    var strPos = 0;
    var br = ((txtarea.selectionStart || txtarea.selectionStart == '0') ?
        "ff" : (document.selection ? "ie" : false ) );
    if (br == "ie") {
        txtarea.focus();
        var range = document.selection.createRange();
        range.moveStart ('character', -txtarea.value.length);
        strPos = range.text.length;
    } else if (br == "ff") {
        strPos = txtarea.selectionStart;
    }

    var front = (txtarea.value).substring(0, strPos);
    var back = (txtarea.value).substring(strPos, txtarea.value.length);
    txtarea.value = front + text + back;
    strPos = strPos + text.length;
    if (br == "ie") {
        txtarea.focus();
        var ieRange = document.selection.createRange();
        ieRange.moveStart ('character', -txtarea.value.length);
        ieRange.moveStart ('character', strPos);
        ieRange.moveEnd ('character', 0);
        ieRange.select();
    } else if (br == "ff") {
        txtarea.selectionStart = strPos;
        txtarea.selectionEnd = strPos;
        txtarea.focus();
    }

    txtarea.scrollTop = scrollPos;
}




Sotero.prototype.setFunticionsButtonsMenu = function(){
    $(document).bind('keydown', function(e) {

        //ctrl+s
        if(e.ctrlKey && (e.which == 83)) {
            e.preventDefault();
            app.savePost();
            return false;
        }

        //ctrl+p
        else if(e.ctrlKey && (e.which == 80)) {
            e.preventDefault();
            app.createNewPost();
            return false;
        }

        //ctrl+c
        else if(e.ctrlKey && (e.which == 67)) {
            e.preventDefault();
            app.showScreenConfing();
            return false;
        }


        //ctrl+a
        else if(e.ctrlKey && (e.which == 65)) {
            e.preventDefault();
            app.showScreenAbout();
            return false;
        }

        //ctrl+b
        else if(e.ctrlKey && (e.which == 66)) {
            e.preventDefault();
            app.getManagerFile().createBlog();
            return false;
        }
    });


    $(document).keypress(function(event) {

        //b
        if(( event.keyCode == 98) && (event.ctrlKey)){



        //a
        }else if(( event.keyCode ==  97) && (event.ctrlKey)){

        }
    });

    $("#bt-newpost").click(function(){
      app.createNewPost();
    });

    $("#bt-savepost").click(function(){
        app.savePost();
    });


    $("#bt-settings").click(function(){
        app.showScreenConfing();
    });
    $("#bt-createblog").click(function(){
        app.getManagerFile().createBlog();
    });
    $("#bt-about").click(function(){
        app.showScreenAbout();
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

Sotero.prototype.savePost = function(){
    if(app.currentFile!="newfile"){
        app.getManagerFile().saveFile(app.currentFile, $("#inp-text-title").val(), $("#inp-text-tag").val(),
            $("#inp-text-date").val(),  $("#inp-text-abstract").val(), $("#inp-text-content").val());
    }else{
        app.getManagerFile().saveFile("gerator/posts/"+$("#inp-text-title").val().replace(" ", "")+".txt" , $("#inp-text-title").val(), $("#inp-text-tag").val(),
            $("#inp-text-date").val(),  $("#inp-text-abstract").val(), $("#inp-text-content").val());
        app.setCurrentFile("");
    }
}


Sotero.prototype.showScreenAbout = function(){
    alert("about")
};



Sotero.prototype.showScreenConfing = function(){
    alert("config")
}

Sotero.prototype.createNewPost = function(){

    app.setCurrentFile("newfile");
    $("#list-file").append('<div class="item-list-file" filepath=""><img src="ui/icon_li.png">Novo Post</div>');

}