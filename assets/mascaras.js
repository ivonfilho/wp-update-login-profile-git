/**
 * Todas as validações de Máscara
 */
jQuery(function ($) {

//Inicio Mascara Telefone
$('input[type=tel]').focusout(function () {
var phone, element;
element = $(this);
element.unmask();
phone = element.val().replace(/\D/g, '');
if (phone.length > 10) {
element.mask("(99) 99999-9999",{autoclear: false});
} else {
element.mask("(99) 9999-9999?9",{autoclear: false});
}
}).trigger('focusout');
//Fim Mascara Telefone

//Inicio Mascara CPF ou CNPJ
$('input#form-field-cpf_cnpj').focusout(function () {
var phone, element;
element = $(this);
element.unmask();
phone = element.val().replace(/\D/g, '');
if (phone.length > 11) {
element.mask("99.999.999/9999-99",{autoclear: false});
} else {
element.mask("999.999.999-99?999",{autoclear: false});
}
}).trigger('focusout');
//Fim Mascara CPF ou CNPJ

// Início Máscara de placas
$('.placa').focusout(function() {
$.mask.definitions['h'] = /[A-Za-z0-9]/;
var placa, element;
element = $(this);
element.unmask();
placa = element.val().replace(/\D/g, '');
element.mask('aaa-9h99', { autoclear: false });
}).trigger('focusout');
// Fim Máscara de placas

// Outras Validações
$("#form-field-data").mask("99/99/9999",{autoclear: false});
$("input#cpf").mask("999.999.999-99",{autoclear: false});
$("input#cep").mask("99999-999",{autoclear: false});
$("input#cnpj").mask("99.999.999/9999-99",{autoclear: false});
$(".altura").mask("999 cm");
$(".peso").mask("999 kg");
$(".rg").mask("RG: 9999999 - (aa)");
//$("input[type=tel]").mask("(99) 99999-999?9");
});

document.addEventListener('DOMContentLoaded', function() {
var telefoneInput = document.querySelector('input[name="telefone"]');
var celularInput = document.querySelector('input[name="celular"]');

if (telefoneInput) {
telefoneInput.setAttribute('type', 'tel');
}

if (celularInput) {
celularInput.setAttribute('type', 'tel');
}
});

document.addEventListener('DOMContentLoaded', function() {
var enderecoInput = document.querySelector('input#endereco');
var cidadeInput = document.querySelector('input#cidade');
var bairroInput = document.querySelector('input#bairro');
var estadoInput = document.querySelector('input#estado');

if (enderecoInput) {
enderecoInput.setAttribute('readonly', 'readonly');
}

if (cidadeInput) {
cidadeInput.setAttribute('readonly', 'readonly');
}

if (bairroInput) {
bairroInput.setAttribute('readonly', 'readonly');
}

if (estadoInput) {
estadoInput.setAttribute('readonly', 'readonly');
}
});