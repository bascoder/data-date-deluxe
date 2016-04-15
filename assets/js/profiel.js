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
    }
}

function finalize(that){
    if(that.id === "editDescription"){
        var newVal = document.getElementById('DescriptionBox').value;
        $.ajax({
            url: base_url + 'index.php/profile/edit/update',
            type: 'POST',
            data: {'ci_session' : document.cookie , field: 'beschrijving', value:newVal, plain:1},
            success:function(response){
                $('#Description').html(response);
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
}

$(document).ready(bindEditFunctions);
})();