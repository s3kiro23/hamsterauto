let toastMixin =
    Swal.mixin({
        toast: true,
        icon: 'success',
        title: 'General Title',
        animation: false,
        position: 'top',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

class Header extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `

            <nav class='autohide navbar navbar-expand-lg bg-light fixed-top shadow mb-5'>
                <div class='container-xl'>
                    <div class="d-flex align-items-center align-items-lg-end order-0">
                        <button class='navbar-toggler' type='button' data-bs-toggle='collapse' data-bs-target='#navbar-main'
                                aria-controls='navbar-main' aria-expanded='false' aria-label='Toggle navigation'>
                            <span class='navbar-toggler-icon'></span>
                        </button>
                        <a class='navbar-brand'>
                            <a class='logo text-decoration-none text-center' type='button' id='logoHome'>
                                <img id="logo"
                                    src="../public/assets/img/hamsterauto-unscreen.gif" 
                                    class="img-fluid" 
                                    style="height:4rem" 
                                    alt="logo_agence"
                                >
                            </a>
                        </a>
                    </div>
                    <div class='collapse navbar-collapse order-2 d-lg-flex ms-lg-5' id='navbar-main'>
                        <ul id="nav-items-gen" class='navbar-nav mt-3 mt-md-0 d-flex justify-content-center align-items-center'>
                            <!--Génération du menu en fonction du type de compte-->
                        </ul>
                    </div>
                    <div class='d-flex justify-content-end align-items-center order-1 order-lg-last me-xl-3'>                 
                        <div class="theme-control-toggle px-2 me-3 me-md-5">
                            <input class="form-check-input ms-0 theme-control-toggle-input switchLogo" id="themeControlToggle"
                                   type="checkbox" data-theme-control="theme" value="dark"/>
                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-light"
                                   for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                   title="Switch to light theme"><span class="fas fa-sun fs-0"></span></label>
                            <label class="mb-0 theme-control-toggle-label theme-control-toggle-dark"
                                   for="themeControlToggle" data-bs-toggle="tooltip" data-bs-placement="left"
                                   title="Switch to dark theme"><span class="fas fa-moon fs-0"></span></label>
                        </div>
                        <a class="text-decoration-none" role="button" data-bs-toggle="dropdown">
                            <span class="first-name me-md-1"></span>
                            <span class="last-name me-sm-2"></span>
                            <img class="img-profile rounded-circle" width="70" height="70"
                                 src="" alt="70x70">
                        </a>
                        <ul class='dropdown-menu dropdown-menu-end' id="profil_dropdown">
                            <li>
                                <button class='dropdown-item' id='profil' type='button'>Mon profil</button>
                            </li>
                            <li>
                                <button class='dropdown-item' id='pwd-modify' type='button'>Modifier mot de passe</button>
                            </li>
                            <li>
                                <button class='dropdown-item' id='settings' type='button'>Paramètres</button>
                            </li>
                            <div class='dropdown-divider'></div>
                            <li>
                                <button class='dropdown-item' id='logout' type='button'>Se déconnecter</button>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        `;
    }
}

customElements.define("header-component", Header);

let generateNavbar = function () {
    $.ajax({
        url: "../src/Controller/dashboardClient/clientController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "generateNavbar",
        },
        success: function (response) {
            sweetToast();
            if (response['status'] === 0) {
                toastMixin.fire({
                    animation: true,
                    title: response["msg"],
                    icon: error,
                });
                setTimeout(() => {
                    window.location.href = "index.html";
                }, 2000);
            } else if (response['status'] === 2) {
                toastMixin.fire({
                    animation: true,
                    icon: "error",
                    width: 500,
                    title: response['msg']
                });
                setTimeout(() => {
                    window.location.replace('index.html')
                }, 3000);
            } else {
                $("#nav-items-gen").html(response['navbarHTML'][0]);
                $(".logo").attr('id', response['navbarHTML'][1]);
                $('.first-name').html(response['userInfo']['firstName']);
                $('.last-name').html(response['userInfo']['lastName']);
                $('.img-profile').attr("src", response['userInfo']['image']);
                $("#profil").on("click", toProfil);
                $("#pwd-modify").on("click", toPwdModify);
                btnToTop();
                elAutoHide();
                if (response['navbarHTML'][1] === 'client') {
                    $("#client").on("click", toHomeClient);
                    $(".linkToClient").on("click", toHomeClient);
                    $('#formClient').on("click", toFormClient)
                    $("#logout").on("click", logout);
                } else {
                    $("#technicien").on("click", toHomeTech);
                    $("#linkToTech").on("click", toHomeTech);
                    $("#logout").on("click", logoutTech);
                }
            }
        },
        error: function () {
            console.log('errorHeader')
        },
    });
}

let logout = function () {
    $.ajax({
        url: "../src/Controller/index/loginController.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "logout",
        },
        success: function (response) {

            toastMixin.fire({
                animation: true,
                title: response["msg"]
            });
            setTimeout(() => {
                window.location.href = "index.html";
            }, 2000);
        },
        error: function () {
        },
    });
};
let logoutTech = function () {
    $.ajax({
        url: "../src/Controller/index/privateLogin.php",
        dataType: "JSON",
        type: "POST",
        data: {
            request: "logout_tech",
        },
        success: function (response) {

            toastMixin.fire({
                animation: true,
                title: response["msg"]
            });
            setTimeout(() => {
                window.location.href = "private-login.html";
            }, 2000);
        },
        error: function () {
        },
    });
};

let toPwdModify = function () {
    window.location.href = "change-password.html?token=pwd-modify"
}

let toHomeClient = function () {
    window.location.replace("client-dashboard.html");
};

function toHomeTech() {
    window.location.href = "back-office.html";
}

let toProfil = function () {
    window.location.replace("profil.html");
};

let toFormClient = function () {
    window.location.replace("client-form.html");
};

let toHolidayForm = function () {
    window.location.replace("holiday-request.html")
};

let elAutoHide = function () {
    let el_autohide = document.querySelector('.autohide');

// add padding-top to bady (if necessary)
    let navbar_height = document.querySelector('.navbar').offsetHeight;
    $('body').attr('style', 'padding-top:' + navbar_height + 'px !important')


    if (el_autohide) {
        let last_scroll_top = 0;
        window.addEventListener('scroll', function () {
            let scroll_top = window.scrollY;
            if (scroll_top < last_scroll_top) {
                el_autohide.classList.remove('scrolled-down');
                el_autohide.classList.add('scrolled-up');
            } else {
                el_autohide.classList.remove('scrolled-up');
                el_autohide.classList.add('scrolled-down');
            }
            last_scroll_top = scroll_top;
        });
    }
}

