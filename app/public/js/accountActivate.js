$(function () {
    accountActivation();
    sweetToast();
});

function $_GET(param) {
    const vars = {};
    window.location.href.replace(location.hash, '').replace(
        /[?&]+([^=&]+)=?([^&]*)?/gi, // regexp
        function (m, key, value) { // callback
            vars[key] = value !== undefined ? value : '';
        }
    );

    if (param) {
        return vars[param] ? vars[param] : null;
    }
    return vars;
}

function accountActivation(){
    let tokenFromMail = $_GET('token');
       $.ajax({
           url: "../src/Controller/Index/SignInController.php",
           dataType: "JSON",
           type: "POST",
           data: {
               request: "activateAccount",
               token: tokenFromMail,
           },
           success: function (response) {
               toastMixin.fire({
                   animation: true,
                   timer: 1500,
                   position: 'center',
                   title: response["msg"]
               });
               setTimeout(() => {
                window.location.replace('/')
               }, 1550);
              
           },
   
           error: function () {
           },
       });
   }