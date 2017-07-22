ManagerFile = function(){ };


ManagerFile.prototype.getSetting = function(callback){

    var jqxhr = $.post( "php/getSetting.php", function() {
    })
        .done(function(data){
			
            data_json = jQuery.parseJSON(data);
            app.setDirSite(data_json[0]["dirsite"]);
            app.setCurrentStyle(data_json[0]["stylesite"]);
			app.setNameSite(data_json[0]["namesite"]);
			
			$("#inp-text-namesite").val(data_json[0]["namesite"]);
            $("#inp-text-dirsite").val(data_json[0]["dirsite"]);
            $("#inp-text-stylesite").val(data_json[0]["stylesite"]);
			
			
            if(callback!=undefined)
                callback();
        })
        .fail(function() {
            alert( "error" );
        });
};

ManagerFile.prototype.setSetting = function(nameSite, dirSite, currentStyle){
    console.log(dirSite, currentStyle);
    var jqxhr = $.post( "php/setSetting.php",{namesite: nameSite, dirsite: dirSite, stylesite: currentStyle }, function() {
    })
        .done(function(data){		
            data_json = jQuery.parseJSON(data);
			$("#inp-text-namesite").val(data_json["namesite"]);
            $("#inp-text-dirsite").val(data_json["dirsite"]);
            $("#inp-text-stylesite").val(data_json["stylesite"]);
        })
        .fail(function() {
            alert( "error" );
        });
};


ManagerFile.prototype.getListFiles = function(callback){

    var jqxhr = $.post( "php/getListFile.php", function() {
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

    var jqxhr = $.post( "php/getDataFile.php",{filepath: filepath }, function() {
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
};

ManagerFile.prototype.saveFile = function(filepath, title, tag, date,abstract, content){


    var jqxhr = $.post("php/saveFilesPost.php",{filepath:filepath, title:title, tag:tag, date:date, abstract:abstract, content:content}, function() {
    })
    .done(function(data){
         console.log(data)
         app.showListFiles();
    })
    .fail(function() {
            alert( "error" );
    });

};




ManagerFile.prototype.createBlog = function(mode){

    var jqxhr = $.post( "gerator/index.php", {dirsite: app.getDirSite()},  function() {
    })
        .done(function(data){
            if(mode)
                window.open("http://localhost/SoteroGen/gerator/"+app.getDirSite(),'_blank');

            console.log(data);
        })

        .fail(function() {
            alert( "error" );
        });
};

