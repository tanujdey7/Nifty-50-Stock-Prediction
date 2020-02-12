const navSlide= ()=> {
    const burger = document.querySelector('.burger');
    const nav = document.querySelector('.nav-links');
    const navLink = document.querySelectorAll('.nav-links li');
    burger.addEventListener('click', () =>{
        nav.classList.toggle('nav-active');
        //animate Lines
        navLink.forEach((link, index)=> {
          if(link.style.animation) {
            link.style.animation = "";
          }
          else {
            link.style.animation = `navLinkFade 0.5s ease forwards ${index/7 + 0.5}s`;
          }
        });
        //animate burger
        burger.classList.toggle('toggle');
      });

}
navSlide();