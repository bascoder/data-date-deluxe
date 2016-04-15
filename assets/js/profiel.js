(function () {
    "use strict";

function bindEditFunctions(){
    $('button').click(buttonHandler);
}

function buttonHandler(){
    if(this.value === "editing") {
        this.value = "static";
        this.innerHTML = "Edit";
        finalize(this);
    }
    else {
        this.value = "editing";
        this.innerHTML = "Save";
        makeEditable(this);
    }
}

function makeEditable(that){
    if(that.id === "editDescription")
    {
        var elem = $("#Description");
        var oldDescript = elem.html();
        var newFilling = "<textarea rows='4' cols='60' id='DescriptionBox'>" + oldDescript + "</textarea>";
        elem.html(newFilling);
    } else if(that.id === "editSexPref") {
        var elem = $('#SexPref');
        var oldPref = elem.attr('editval');
        var newFilling = "<select id='sex-preference' name='sex_preference' required><option value ='m'>mannen</option><option value ='v'>vrouwen</option><option value ='bi'>mannen en vrouwen</option></select>"
        elem.html(newFilling);
        switch(oldPref){
            case "1":
            $("select option[value|='m']", elem).prop("selected",'s');
            break;
            case "2":
            $("select option[value|='v']", elem).prop("selected",'s');
            break;
            case "3":
            $("select option[value|='bi']", elem).prop("selected",'s');
            break;
        }
    } else if(that.id === "editBrands"){
        
    }
}

function finalize(that){
    if(that.id === "editDescription"){
        var value = document.getElementById('DescriptionBox').value;
        doAjaxUpdate('beschrijving', value, $('#Description'));      
    } else if (that.id === "editSexPref"){
        var preference = $("#sex-preference").val();
        doAjaxUpdate('sex_preference', preference, $('#SexPref'));
    }
}

function doAjaxUpdate(field, value, elem){
    $.ajax({
        url: base_url + 'index.php/profile/edit/update_'+field,
        type: 'POST',
        data: {'ci_session' : document.cookie , value:value, plain:1},
        success:function(response){
            elem.html(response);
        }
    })
    .done(function() {
        console.log("success");
    })
    .fail(function() {
        console.log("error");
    })
    .always(function() {
        console.log("complete");
    });
}

$(document).ready(bindEditFunctions);
})();