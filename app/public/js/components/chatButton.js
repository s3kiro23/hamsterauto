class chatButton extends HTMLElement {
    constructor() {
        super();
    }

    connectedCallback() {
        this.innerHTML = `
            <div class="chat-container">
                <button class="pulse-button btn btn-success p-1" href="contact-us.html">
                    <i class="fa-regular fa-comment fa-2xl"></i>
                </button>
            </div>
        `;
    }
}

customElements.define("chat-button", chatButton);