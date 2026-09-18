document.querySelectorAll(".card-header button").forEach(function(button) {

    button.addEventListener("click", function() {

        const card = this.closest(".card");
        const body = card.querySelector(".card-body");
        const isOpen = this.getAttribute("aria-expanded") === "true";

        // Fecha todos
        document.querySelectorAll(".card-header button").forEach(function(otherButton) {
            otherButton.setAttribute("aria-expanded", "false");

            const otherBody = otherButton
                .closest(".card")
                .querySelector(".card-body");

            otherBody.classList.remove("open");
        });

        // Abre o clicado, caso estivesse fechado
        if (!isOpen) {
            this.setAttribute("aria-expanded", "true");
            body.classList.add("open");
        }

    });

});