var cool_facts_contents = document.querySelectorAll(".cool-facts-content");
var icons = document.querySelectorAll(".iconify");
var counter = document.querySelectorAll(".counter");
var players = document.querySelector(".players");
var titulo = document.querySelector(".titulo");
var subTitulo = document.querySelector(".subtitulo");

if (window.matchMedia("(max-width: 425px)").matches) {
  //Font size del titulo y subtitulo acorde a la pantalla

  titulo.style.fontSize = "50px";
  titulo.style.paddingLeft = "30px";
  subTitulo.style.paddingLeft = "30px";

  //Modifico ancho del ultimo elemento Players
  players.style.width = "129px";

  // Aplico clases a los divs cool.facts
  [].forEach.call(cool_facts_contents, div => {
    div.classList.add("d-flex");
    div.classList.add("justify-content-between");
  });
  //Aplico margen a los iconos
  [].forEach.call(icons, icon => {
    icon.style.margin = "5px";
  });
  //Aplico Estilos y clases a los Counters
  [].forEach.call(counter, counter => {
    counter.style.fontSize = "50px";
    counter.classList.add("d-flex");
    counter.classList.add("justify-content-center");
  });
}
