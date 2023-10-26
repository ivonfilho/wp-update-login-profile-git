document.addEventListener("DOMContentLoaded", function() {
var form = document.querySelector("form#loginform");
var paragrafos = Array.from(form.getElementsByTagName("p"));
if(paragrafos.length >= 2) {
form.insertBefore(paragrafos[paragrafos.length - 1], paragrafos[paragrafos.length - 2]);
}
});