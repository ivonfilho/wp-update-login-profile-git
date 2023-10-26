// Adicionar classes sequenciais a todos os elementos <h2>, <h3>, e <table>

document.addEventListener('DOMContentLoaded', function () {

let h2Counter = 1;
document.querySelectorAll('h2').forEach(function(h2) {
h2.classList.add('class-' + h2Counter);
h2Counter++;
});

let h3Counter = 1;
document.querySelectorAll('h3').forEach(function(h3) {
h3.classList.add('class-' + h3Counter);
h3Counter++;
});

let tableCounter = 1;
document.querySelectorAll('table').forEach(function(table) {
table.classList.add('class-' + tableCounter);
tableCounter++;
});
});