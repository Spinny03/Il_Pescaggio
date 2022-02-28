
function footerHeight(){
    cont = document.getElementsByClassName("container");
    console.log(cont[0].clientHeight + 80);
    console.log(window.innerHeight);
    if(cont[0].clientHeight + 80 < window.innerHeight){
        a = document.getElementsByTagName("footer")[0];
        a.style.bottom = "-350px";
        console.log("ciao1");
        if((cont[0].clientHeight + 80) == 80 && window.innerWidth <= 900){
            a.style.bottom = "-1000px";
        }
    }
    else{
        a = document.getElementsByTagName("footer")[0];
        a.style.bottom = "auto";
        console.log("ciao2");
    }
}