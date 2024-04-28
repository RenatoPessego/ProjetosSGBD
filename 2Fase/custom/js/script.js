function primeiravalidacao()
{
    alert("Ta a funcionar!");
    var nome = document.getElementById("nome");
    var data = document.getElementById("data_de_nascimento");
    var data_f = new Date(data);
    const apenas_letras = /^[\p{L}]*$/u;
    if(!apenas_letras.test(nome))
    {
        alert("O nome a pesquisar não pode conter números ou caracteres especiais (que não sejam acentos)!")
        return false;
    }
    else if(isNaN(data_f))
    {
        alert("A data não é do tipo AAAA-MM-DD!")
        return false;
    }
        return true;

}