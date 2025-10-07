document.querySelectorAll(".row .col-lg-3  img").forEach(img => {
    img.addEventListener("click", function () {
        this.src = this.src === this.dataset.bw ? this.dataset.color : this.dataset.bw;
    });
});