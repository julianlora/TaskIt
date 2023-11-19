
//import hamburgerMenu from "./dom/menu_hamburguesa.js";
import smartVideo from "./modulos/video.js";
import fLinks from "./modulos/focusLinks.js";
import toogleI from "./modulos/toogleIcon.js";

const d = document;

d.addEventListener("DOMContentLoaded", (e)=>{
    
    smartVideo();
    //hamburgerMenu(".panel-btn", ".panel", ".menu a");
    fLinks();
    toogleI();
   
});

