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
    function showContreVisite(id) {
        $.ajax({
            url: "../src/Controller/RdvController.php",
            dataType: "JSON",
            type: "POST",
            data: {
                request: "show_contre_visite",
                rdvID: id,
            },
            success: function (response) {
                modalCVisite(response);
            },
            error: function () {
                console.log("errorShow");
            },
        });
    }
    function switchLogo(){
        if ($('#logo').attr('src') === "../public/assets/img/hamsterauto-unscreen.gif"){
            $('#logo').attr('src',"../public/assets/img/hamsterautoNuit-unscreen.gif") 
        }else if ($('#logo').attr('src') === "../public/assets/img/hamsterautoNuit-unscreen.gif"){
            $('#logo').attr('src', "../public/assets/img/hamsterauto-unscreen.gif")
        }
    }


class Header extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `

            <nav class='autohide navbar navbar-expand-lg fixed-top bg-light shadow mb-5' id='navbar-dash-user'>
                <div class='container-xl p-0'>
                    <div class="d-flex align-items-center align-items-lg-end order-0">
                        <button class='navbar-toggler collapsed' type='button' data-bs-toggle='collapse' data-bs-target='#navbar-main'
                                aria-controls='navbar-main' aria-expanded='false' aria-label='Toggle navigation'>
<!--                            <span class='navbar-toggler-icon'></span>-->
                            <span class="icon-bar bg-primary"></span>
                            <span class="icon-bar bg-primary"></span>
                            <span class="icon-bar bg-primary"></span>
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
                        <a class="text-decoration-none fw-bold" role="button" data-bs-toggle="dropdown">
                            <span class="first-name me-md-1"></span>
                            <span class="last-name me-sm-2"></span>
                            <img class="img-profile rounded-circle" width="55" height="55"
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
                                <button class='dropdown-item' id='settings' type='button'>Notifications</button>
                            </li>
                            <div class='dropdown-divider'></div>
                            <li class="d-flex flex-row">
                                <button class='dropdown-item' id='sessionEnding' type='button'>Se déconnecter</button>
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
        url: "../src/Controller/DashboardClient/ClientController.php",
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
                    window.location.href = "/";
                }, 2000);
            } else if (response['status'] === 2) {
                toastMixin.fire({
                    animation: true,
                    icon: "error",
                    width: 500,
                    title: response['msg']
                });
                setTimeout(() => {
                    window.location.replace('/')
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
                    $("#settings").on("click", notificationManager);
                    $("#client").on("click", toHomeClient);
                    $(".linkToClient").on("click", toHomeClient);
                    $('#formClient').on("click", toFormClient)
                    $("#sessionEnding").on("click", sessionEnding);
                    $('#check-all-list').on("click", checkThemAll)
                } else {
                    $("#technicien").on("click", toHomeTech);
                    $("#linkToTech").on("click", toHomeTech);
                    $("#sessionEnding").on("click", sessionEnding);
                }
            }
        },
        error: function () {
            console.log('errorHeader')
        },
    });
}


let toPwdModify = function () {
    window.location.href = "/change-password?token=pwd-modify"
}

let toHomeClient = function () {
    window.location.replace("/dashboards/client");
};

function toHomeTech() {
    window.location.href = "/dashboards/technicien";
}

let toProfil = function () {
    window.location.replace("/profil");
};

let toFormClient = function () {
    window.location.replace("/reservation");
};

// let toHolidayForm = function () {
//     window.location.replace("holiday-request.html")
// };

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

