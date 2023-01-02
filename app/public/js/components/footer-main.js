class FooterMain extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
           <footer class="container py-3 px-4">
                <nav id="row" class="row list-unstyled justify-content-between">
                    <div class="col-12 col-md-6 text-center text-lg-start ">
                        <i class="fa-solid fa-code"></i><span> with lots of ☕ by Team<i class="fw-semibold"> FromScratch</i></span>
                        <span>| Logo © by Prescillia</span>
                    </div>
                    <div class="col-12 col-md-6 text-end gap-3">
                        <span>V1.0.75</span>
                    </div>
                </nav>
            </footer>
        `;
    }
}

customElements.define("footer-component-main", FooterMain);

