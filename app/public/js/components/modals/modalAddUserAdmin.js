class ModalUserAdmin extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div id="modal-user" class="modal fade">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header bg-primary bg-opacity-10">
                            <h5 class="modal-title">Nouveau collaborateur</h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close">
                                <span aria-hidden="true"></span>
                            </button>
                        </div>
                        <form action="javascript:addUserAdmin();">
                            <div class="modal-body d-flex flex-row">
                                <div id="modal-body">
                                    <div class="row gap-3">
                                        <div class="col-12 d-flex flex-column">
                                            <label class="fw-bold">Email / login</label>
                                            <input id="inputLoginAdd" type="text" class="modal-add form-control text-muted ps-2">
                                            <div class="invalid-feedback mb-2"></div>
                                        </div>
                                        <div class="col-6">
                                        <div class="d-flex gap-2 mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="civilite"
                                                    id="optionsRadios1"
                                                    value="Mr">
                                                <label class="form-check-label text-dark" for="optionsRadios1">
                                                    Mr.
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="civilite"
                                                    id="optionsRadios2"
                                                    value="Mme">
                                                <label class="form-check-label text-dark" for="optionsRadios2">
                                                    Mme.
                                                </label>
                                            </div>
                                        </div>
                                            <div class="d-flex flex-column">
                                                <label class="fw-bold">Nom</label>
                                                <input id="inputNomAdd" type="text" class="modal-add form-control text-muted ps-2">
                                                <div class="invalid-feedback mb-2"></div>
                                            </div>
                                            <div class="d-flex flex-column my-2">
                                                <label class="fw-bold">Prénom</label>
                                                <input id="inputPrenomAdd" type="text" class="modal-add form-control text-muted ps-2">
                                                <div class="invalid-feedback mb-2"></div>
                                            </div>
                                            <div class="d-flex flex-column">
                                                <label class="fw-bold">Téléphone</label>
                                                <input id="inputTelAdd" type="number" class="modal-add form-control text-muted ps-5">
                                                <div class="d-block invalid-feedback mb-2"></div>
                                            </div>
                                        </div>
                                        <div class="col position-relative">
                                            <div class="d-flex flex-column">
                                                <label class="fw-bold">Adresse</label>
                                                <textarea id="inputAddrAdd" class="modal-add form-control text-muted ps-2"></textarea>
                                                <div class="invalid-feedback"></div>
                                            </div>
                                            <div class='mt-3'>
                                                <div id='passwordContainer' class='input-group'>
                                                    <label for="inputPassword" class="sr-only"></label>
                                                    <input  id="inputPassword"
                                                            name="inputPassword"
                                                            type="password"
                                                            class="inputPassword modal-add field form-control rounded-start"
                                                            placeholder="Mot de passe"/>
                                                    <span class='input-group-text rounded-end' role='button' onclick='showPassword();'>
                                                        <i class='fa-regular fa-eye eyeShow'></i>
                                                    </span>
                                                    <div class="invalid-feedback mb-2"></div>
                                                    <div class="strengthMeter mt-1"></div>
                                                </div>
                                                <div class='mt-4'>
                                                    <label for="inputPassword2" class="sr-only"></label>
                                                    <input  id="inputPassword2"
                                                            name="inputPassword2"
                                                            type="password"
                                                            class="inputPassword modal-add field form-control"
                                                            placeholder="Confirmez mot de passe"/>
                                                            
                                                    <div class="invalid-feedback mb-2"></div>
                                                    
                                                </div>
                                            </div>

                                        </div>
                                        <div class="d-flex gap-2 mb-2">
                                            <div class="form-check">
                                                <input class="typeAccount form-check-input" 
                                                    type="radio" 
                                                    name="optionTypeAdmin"
                                                    id="typeAdmin"
                                                    value="technicien" checked>
                                                <label class="form-check-label text-dark" for="typeAdmin" >
                                                   Technicien
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="typeAccount form-check-input" 
                                                    type="radio" 
                                                    name="optionTypeAdmin"
                                                    id="typeTech"
                                                    value="admin">
                                                <label class="form-check-label text-dark" for="typeTech">
                                                   Administrateur
                                                </label>
                                            </div>
                                            <div class='d-flex'>
                                            <i class="fa-solid fa-triangle-exclamation text-danger ms-3">
                                                <p class="mt-2 text-black fs--1 font-sans-serif" style='font-size: 0.7em;'>Un compte administrateur n'est pas désactivable depuis l'application !</p>
                                            </i>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button
                                    type="button"
                                    class="btn btn-outline-secondary border-0 rounded px-2 py-1"
                                    data-bs-dismiss="modal"
                                >
                                    Annuler
                                </button>
                                <button
                                    type="submit"
                                    class="btn btn-primary rounded px-2 py-1"
                                    data-bs-dismiss="modal"
                                >
                                    Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;
    }
}

customElements.define("modal-user-admin", ModalUserAdmin);

let modalAdduser = function (){
    $("#modal-user").modal("show");
    $("#inputTelAdd").intlTelInput({
        preferredCountries: ["fr", "gb"],
        utilsScript: "../vendor/jackocnr/intl-tel-input/build/js/utils.js",
        initialCountry: "fr",
        placeholder: '',
        geoIpLookup: function (success, failure) {
            $.get("https://ipinfo.io", function () {
            }, "jsonp").always(function (resp) {
                const countryCode = (resp && resp.country) ? resp.country : "fr";
                success(countryCode);
            });
        },
    });
}