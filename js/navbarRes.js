const primaryNav = document.querySelector(".navItems");
const navToggle = document.querySelector("#respBtn");

navToggle.addEventListener('click', () =>{
    const visibility = primaryNav.getAttribute('data-visible');
    if(visibility === "false"){
        primaryNav.setAttribute("data-visible", true);
    }
    else{
        primaryNav.setAttribute("data-visible", false);
    }
});