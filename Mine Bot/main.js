var div = document.querySelector(".cool-facts-content");
var icon = document.querySelector('.iconify');
var counter = document.querySelector('.counter');


if (window.matchMedia("(max-width: 360px)").matches) {

    div.classList.add('d-flex');
    icon.style.margin ='5px';
    counter.style.fontSize   ='50px';
    
    
  } 