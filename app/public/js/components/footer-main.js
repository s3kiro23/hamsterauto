class FooterMain extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
           <footer class="container py-3 px-4 mt-auto">
                <nav id="row" class="row list-unstyled">
                    <div class="col-12 col-md-6 text-center text-lg-start">
                        <i class="fa-solid fa-code"></i><span> with lots of ☕ by Team<i class="fw-semibold"> FromScratch</i></span>               
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-end">
                        <span class="fw-bold">V1.1</span>
                    </div>
                </nav>
            </footer>
        `;
    }
}

customElements.define("footer-component-main", FooterMain);

