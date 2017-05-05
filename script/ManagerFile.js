ManagerFile = function(){   }

ManagerFile.prototype.getListFiles = function(callback){

    var jqxhr = $.post( "getListFile.php", function() {
    })
        .done(function(data){
            data_json = jQuery.parseJSON(data);
            callback(data_json);
        })

        .fail(function() {
            alert( "error" );
     });
};


ManagerFile.prototype.getDataFiles = function(filepath){

    var jqxhr = $.post( "getDataFile.php",{filepath: filepath }, function() {
    })
        .done(function(data){
            data_json = jQuery.parseJSON(data);

            for(var i = 0; i< data_json.length; i++){
                $("#inp-text-title").val(data_json[i]["title"]);
                $("#inp-text-tag").val(data_json[i]["tag"]);
                $("#inp-text-date").val(data_json[i]["date"]);
                $("#inp-text-abstract").val(data_json[i]["abstract"]);
                $("#inp-text-content").val(data_json[i]["content"]);

                app.setContentInFrameEditor(data_json[i]["content"]);
            }
        })
        .fail(function() {
            alert( "error" );
        });
}

ManagerFile.prototype.saveFile = function(filepath, title, tag, date,abstract, content){


    var jqxhr = $.post( "saveFilesPost.php",{filepath:filepath, title:title, tag:tag, date:date, abstract:abstract, content:content}, function() {
    })
    .done(function(data){
         app.showListFiles();
    })
    .fail(function() {
            alert( "error" );
    });

}




ManagerFile.prototype.createBlog = function(){

    var jqxhr = $.post( "gerator/index.php", function() {
    })
        .done(function(data){
            alert("blog criado");
        })

        .fail(function() {
            alert( "error" );
        });
};

