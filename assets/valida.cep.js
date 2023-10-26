/* SETUP DOS IDS DO FORM
 *
 * Insira os ID's CSS de acordo com os campos do seu formulário.
 *
 *  */
var rua='input#endereco';
var bairro='input#bairro';
var cidade='input#cidade';
var uf='input#estado';
var cep='input#cep';
//-------------------------------------------
/* CODIGO BRUTO */
var $jq = jQuery.noConflict();
$jq(document).ready(function() {
function limpa_formulário_cep() {
// Limpa valores do formulário de cep.
$jq(rua).val('');
$jq(bairro).val('');
$jq(cidade).val('');
$jq(uf).val('');
}
//Quando o campo cep perde o foco.
$jq(cep).blur(function() {
//Nova variável "cep" somente com dígitos.
var cepx = $jq(this).val().replace(/\D/g, '');
//Verifica se campo cep possui valor informado.
if (cepx !='') {
//Expressão regular para validar o CEP.
var validacep = /^[0-9]{8}$/;
//Valida o formato do CEP.
if(validacep.test(cepx)) {
//Preenche os campos com "..." enquanto consulta webservice.
$jq(rua).val('...carrengando');
$jq(bairro).val('...carrengando');
$jq(cidade).val('...carrengando');
$jq(uf).val('...carrengando');
//Consulta o webservice viacep.com.br/
$jq.getJSON('https://viacep.com.br/ws/'+ cepx +'/json/?callback=?', function(dados) {
if (!('erro' in dados)) {
//Atualiza os campos com os valores da consulta.
$jq(rua).val(dados.logradouro);
$jq(bairro).val(dados.bairro);
$jq(cidade).val(dados.localidade);
$jq(uf).val(dados.uf);
} //end if.
else {
//CEP pesquisado não foi encontrado.
limpa_formulário_cep();
alert('CEP não encontrado.');
}
});
} //end if.
else {
//cep é inválido.
limpa_formulário_cep();
alert('Formato de CEP inválido.');
}
} //end if.
else {
//cep sem valor, limpa formulário.
limpa_formulário_cep();
}
});
});