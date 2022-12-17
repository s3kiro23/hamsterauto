class BackTopBtn extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <button
                type='button'
                class='btn btn-success btn-floating btn-lg rounded-circle py-xl-2 px-xl-3 py-md-2 px-md-3 py-lg-2 px-lg-3 py-2 px-3'
                id='btn-back-to-top'
            >
                <i class='fas fa-arrow-up'></i>
            </button>
        `;
    }
}

customElements.define("btn-top-component", BackTopBtn);

let btnToTop = function () {
    let mybutton = document.getElementById("btn-back-to-top");
    // When the user scrolls down 20px from the top of the document, show the button
    window.onscroll = function () {
        scrollFunction();
    };

    function scrollFunction() {
        if (
            document.body.scrollTop > 20 ||
            document.documentElement.scrollTop > 20
        ) {
            mybutton.style.display = "block";
        } else {
            mybutton.style.display = "none";
        }
    }

    // When the user clicks on the button, scroll to the top of the document
    mybutton.addEventListener("click", backToTop);

    function backToTop() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
    }

}
