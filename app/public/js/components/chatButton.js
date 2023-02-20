class chatButton extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <a class="chat-container" href="contact-us.html">
                <button class="pulse-button btn btn-success p-1">
                    <i class="fa-regular fa-comment fa-2xl"></i>
                </button>
            </a>
        `;
    }
}

customElements.define("chat-button", chatButton);