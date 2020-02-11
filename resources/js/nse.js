const navSlide = () => {
    const burger = documemt.querySelector(".burger")
    const nav = document.querySelector("nav-links");

    burger.addEventListener("click",() => {
        nav.classList.toggle("nav-active");
    });
}

navSlide();