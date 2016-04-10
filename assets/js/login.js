document.addEventListener("load",bindLoginFuncs());

function bindLoginFuncs(){
    $("form").submit(function(){hashAndSubmit();});
}

function hashAndSubmit(){
    var pwField = $("form input[name=password]");
    var originalPw = pwField.val();
    var username = $("form input[name=username]").val();
    var hasedpw = Sha1.hash(originalPw + username);
    pwField.val(hasedpw);
}