class Footer extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
           <footer class="footer py-3 px-md-5 mt-5 mt-sm-5 mt-md-3 mt-lg-0">
                <nav id="row" class="row list-unstyled gap-md-0 gap-2">
                    <div class="col-12 col-md-4 col-lg-4 text-lg-start text-md-start text-center">
                        <span>Logo © by Prescillia</span>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 d-flex justify-content-center gap-3">
                        <a id="to-mentions" type="button" class="link-primary">Mentions légales</a>
                        <a id="to-cgu" type="button" class="link-primary" href="./contact-us.html">Contact</a>
                    </div>
                    <div class="col-12 col-md-4 col-lg-4 text-md-end text-lg-end text-center">
                        <span>Powered by Team<i class="fw-semibold"> FromScratch</i></span>
                    </div>
                </nav>
            </footer>
        `;
    }
}

customElements.define("footer-component", Footer);

