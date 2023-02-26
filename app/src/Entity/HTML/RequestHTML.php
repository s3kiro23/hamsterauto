<?php

class RequestHTML
{
    public static function newPwd(): string
    {
        return "
            <div class='row min-vh-100 flex-center gx-6'>
                <div class='col-lg-9 col-xxl-6 py-3 position-relative'>
                    <img class='bg-auth-circle-shape' src='../public/assets/img/icons/spot-illustrations/bg-shape.png' alt='' width='250'>
                    <img class='bg-auth-circle-shape-2' src='../public/assets/img/icons/spot-illustrations/shape-1.png' alt='' width='150'>
                    <div class='card overflow-hidden z-index-1'>
                        <div class='card-body p-0'>
                            <div class='row g-0 h-100'>
                                <div class='col-md-5 text-center bg-card-gradient'>
                                    <div class='position-relative p-4 pt-md-5 pb-md-7 light'>
                                        <div class='bg-holder bg-auth-card-shape' style='background-image:url(../public/assets/img/icons/spot-illustrations/half-circle.png);'>
                                          </div>
                                          <!--/.bg-holder-->

                                          <div class='z-index-1 position-relative d-flex justify-content-center align-items-center flex-column gap-5'>
                                            <img src='../public/assets/img/hamsterautoNuit-unscreen.gif'
                                                class='logo_agence img-fluid'
                                                style='width:70%'
                                                alt='logo_agence'>
                                            <p class='text-white'>Ne pensez plus à votre contrôle technique, nous le faisons pour vous!</p>
                                          </div>
                                    </div>
                                    <div class='mt-3 mb-4 mt-md-4 mb-md-5 light'>
                                        <p class='mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-white opacity-75 d-flex flex-column'>
                                            <span>Lisez nos <a class='text-decoration-underline text-white cursor-pointer' id='to-mentions'>mentions légales</a></span>
                                            <span>et restons <a class='text-decoration-underline text-white' id='to-cgu' href='contact'>en contact!</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div id='mail-sending' class='col-md-7 d-flex flex-center'>
                                    <div class='p-4 p-md-5 flex-grow-1'>
                                        <div class='text-center text-md-start'>
                                            <h4 class='mb-4'>Mise à jour <br> mot de passe</h4>
                                        </div>
                                        <div class='row justify-content-center'>
                                            <div class='col-sm-8 col-md p-0'>
                                                <form class='b-3 d-flex flex-column gap-3' action='javascript:newPwd();' method='POST'>
                                                    <div class='input-group'>
                                                        <span class='input-group-text rounded-end' role='button' onclick='showPassword();'>
                                                            <i class='fa-solid fa-at'></i>
                                                        </span>
                                                        <input
                                                            id='inputEmail'
                                                            name='inputEmail'
                                                            type='email'
                                                            autocomplete='current-email'
                                                            required
                                                            class='field form-control rounded-end'
                                                            placeholder=' '
                                                        />
                                                        <label for='inputEmail' class='email-label input-password-field form-label-group m-0'>Email</label>
                                                        <div class='invalid-feedback'></div>          
                                                    </div>      
                                                    <div class='input-group position-relative'>
                                                        <input
                                                            id='inputPassword'
                                                            name='inputPassword'
                                                            type='password'
                                                            data-toggle='tooltip'
                                                            data-placement='bottom'
                                                            title='Minumun 12 caractères, une majuscule, des chiffres et un caractère spécial'
                                                            autocomplete='current-password'
                                                            class='field form-control'
                                                            placeholder=' '
                                                        />
                                                        <label for='inputPassword' class='input-password-field form-label-group m-0'>Nouveau</label>
                                                        <span class='input-group-text rounded-end' role='button' onclick='showPassword();'>
                                                            <i class='fa-regular fa-eye eyeShow'></i>
                                                        </span>
                                                        <div class='invalid-feedback'></div>
                                                    </div>
                                                    <button class='btn btn-primary d-block w-100 mt-3' id='newPwd' type='submit' name='submit'>Modifier</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    public static function secondAuth(): string
    {
        return "
            <div class='row min-vh-100 flex-center gx-6'>
                <div class='col-lg-9 col-xxl-6 py-3 position-relative'>
                    <img class='bg-auth-circle-shape' src='../public/assets/img/icons/spot-illustrations/bg-shape.png' alt='' width='250'>
                    <img class='bg-auth-circle-shape-2' src='../public/assets/img/icons/spot-illustrations/shape-1.png' alt='' width='150'>
                    <div class='card overflow-hidden z-index-1'>
                        <div class='card-body p-0'>
                            <div class='row g-0 h-100'>
                                <div class='col-md-5 text-center bg-card-gradient'>
                                    <div class='position-relative p-4 pt-md-5 pb-md-7 light'>
                                        <div class='bg-holder bg-auth-card-shape' style='background-image:url(../public/assets/img/icons/spot-illustrations/half-circle.png);'>
                                          </div>
                                          <!--/.bg-holder-->

                                          <div class='z-index-1 position-relative d-flex justify-content-center align-items-center flex-column gap-5'>
                                            <img src='../public/assets/img/hamsterautoNuit-unscreen.gif'
                                                class='logo_agence img-fluid'
                                                style='width:70%'
                                                alt='logo_agence'>
                                            <p class='text-white'>Ne pensez plus à votre contrôle technique, nous le faisons pour vous!</p>
                                          </div>
                                    </div>
                                    <div class='mt-3 mb-4 mt-md-4 mb-md-5 light'>
                                        <p class='mb-0 mt-4 mt-md-5 fs--1 fw-semi-bold text-white opacity-75 d-flex flex-column'>
                                            <span>Lisez nos <a class='text-decoration-underline text-white cursor-pointer' id='to-mentions'>mentions légales</a></span>
                                            <span>et restons <a class='text-decoration-underline text-white' id='to-cgu' href='contact'>en contact!</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div id='mail-sending' class='col-md-7 d-flex flex-center'>
                                    <div class='p-4 p-md-5 flex-grow-1'>
                                        <div class='text-center text-md-start'>
                                            <h4 class='mb-3'>Renseignez votre code SMS</h4>
                                        </div>
                                        <div class='row justify-content-center'>
                                            <div class='col-sm-8 col-md p-0'>
                                                <form class='b-3 d-flex flex-column gap-3' action='javascript:smsVerif();'  method='POST'>
                                                    <input
                                                        id='sms_verif'
                                                        name='sms_verif'
                                                        type='text'
                                                        class='field form-control'
                                                        placeholder='Entrez le code sms reçu.'/>
                                                    <button class='btn btn-primary d-block w-100 mt-3' id='sub_sms' type='submit' name='submit'>Valider</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

    public static function mailSending($mail): string
    {
        return "
            <div class='p-4 p-md-5 flex-grow-1'>
                <div class='text-center'><img class='d-block mx-auto mb-4' src='../public/assets/img/icons/spot-illustrations/16.png' alt='Email' width='100' />
                    <h3 class='mb-2'>Merci de consulter vos mails !</h3>
                    <p>Un mail a été envoyé à l'adresse <strong>$mail</strong>. Veuillez cliquer sur le lien <br class='d-none d-sm-block d-md-none' />inclus pour réinitialiser votre mot de passe.</p>
                    <a class='btn btn-primary btn-sm mt-3' id='reload'><span class='fas fa-chevron-left me-1' data-fa-transform='shrink-4 down-1'></span>Retour au login</a>
                </div>
            </div>
        ";
    }

    public static function toRequestMail(): string
    {
        return "
            <div class='row min-vh-100 flex-center'>
                <div class='col-lg-9 col-xxl-6 py-2 position-relative'>
                    <img class='bg-auth-circle-shape' src='../public/assets/img/icons/spot-illustrations/bg-shape.png' alt='' width='250'>
                    <img class='bg-auth-circle-shape-2' src='../public/assets/img/icons/spot-illustrations/shape-1.png' alt='' width='150'>
                    <div class='card overflow-hidden z-index-1'>
                        <div class='card-body p-0'>
                            <div class='row g-0 h-100'>
                                <div class='col-md-5 text-center bg-card-gradient'>
                                    <div class='position-relative p-4 pt-md-5 pb-md-7 light'>
                                        <div class='bg-holder bg-auth-card-shape' style='background-image:url(../public/assets/img/icons/spot-illustrations/half-circle.png);'>
                                          </div>
                                          <!--/.bg-holder-->

                                          <div class='z-index-1 position-relative d-flex justify-content-center align-items-center flex-column gap-4'>
                                            <img src='../public/assets/img/hamsterautoNuit-unscreen.gif'
                                                class='logo_agence img-fluid'
                                                style='width:60%'
                                                alt='logo_agence'>
                                            <p class='text-white'>Ne pensez plus à votre contrôle technique, nous le faisons pour vous!</p>
                                          </div>
                                    </div>
                                    <div class='mb-4 mt-md-4 mb-md-5 light'>
                                        <p class='mb-0 mt-md-5 fs--1 fw-semi-bold text-white opacity-75 d-flex flex-column'>
                                            <span>Lisez nos <a class='text-decoration-underline text-white cursor-pointer' id='to-mentions'>mentions légales</a></span>
                                            <span>et restons <a class='text-decoration-underline text-white' id='to-cgu' href='contact'>en contact!</a></span>
                                        </p>
                                    </div>
                                </div>
                                <div id='mail-sending' class='col-md-7 d-flex flex-center'>
                                    <div class='p-4 p-md-5 flex-grow-1'>
                                        <div class='text-center text-md-start'>
                                            <h4 class='mb-0'> Vous avez oublié votre mot de passe?</h4>
                                            <p class='mb-4'>Renseignez votre mail et nous vous enverrons un lien de réinitialisation.</p>
                                        </div>
                                        <div class='row justify-content-center'>
                                            <div class='col-sm-8 col-md p-0'>
                                                <form action='javascript:genToken();' method='POST'>
                                                    <div class='input-group position-relative'>
                                                        <span class='input-group-text rounded-end' role='button' onclick='showPassword();'>
                                                            <i class='fa-solid fa-at'></i>
                                                        </span>   
                                                        <input id='inputEmail'
                                                            name='inputEmail'
                                                            type='email'
                                                            required
                                                            class='field form-control rounded-end'
                                                            placeholder=' '/>
                                                        <label for='inputEmail' class='email-label form-label-group m-0'>Email</label>
                                                        <div class='invalid-feedback'></div>
                                                    </div> 
                                                    <button class='btn btn-primary d-block w-100 mt-3' id='sendToken' type='submit' name='submit'>Envoyer le lien</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ";
    }

}