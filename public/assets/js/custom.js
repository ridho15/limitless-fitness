function toast(message, status) {
    Toastify({
        text: message,
        className: status,
        style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
        },
    }).showToast();
}

// const header = document.querySelector(".page-header");
// const toggleClass = "is-sticky";

// window.addEventListener("scroll", () => {
//     const currentScroll = window.pageYOffset;
//     if (currentScroll > 150) {
//         header.classList.add(toggleClass);
//     } else {
//         header.classList.remove(toggleClass);
//     }
// });
