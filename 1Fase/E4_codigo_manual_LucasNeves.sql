#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

# 1. Demonstra todos os funcionários associados aos seus projetos
SELECT DISTINCT p.id, descricao, pessoas.id,nome, contrato.funcao FROM projeto AS p INNER JOIN projeto_tem_testers AS ptt ON p.id = ptt.projeto_id INNER JOIN projeto_tem_programadores AS ptp ON p.id = ptp.projeto_id
INNER JOIN projeto_tem_designers AS ptd ON p.id = ptd.projeto_id INNER JOIN pessoas ON ptt.testers_pessoas_id = pessoas.id OR ptp.programadores_pessoas_id = pessoas.id OR ptd.designers_pessoas_id = pessoas.id INNER JOIN pessoas_tem_contrato ON pessoas.id = pessoas_tem_contrato.pessoas_id
INNER JOIN contrato ON pessoas_tem_contrato.contrato_id = contrato.id ORDER BY p.id;
#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

#2. Seleciona todos os testes realizados por testers que recebem mais de 2200 euros
SELECT DISTINCT testes.data ,testes.n_erros FROM testes,testers WHERE testers.pessoas_id = testes.testers_pessoas_id AND testers.pessoas_id IN (
SELECT pessoas_tem_contrato.pessoas_id FROM pessoas_tem_contrato,contrato WHERE pessoas_tem_contrato.contrato_id = contrato.id AND contrato.salario > 2200 );
#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

#3.  Conta o número de testes por sistema operativo feitos por testers que recebem mais de 2000 euros
SELECT sistema_operativo.versao, COUNT(testes.id) as num_testes FROM sistema_operativo, testes, testes_tem_sistema_operativo WHERE testes.id = testes_tem_sistema_operativo.testes_id AND testes_tem_sistema_operativo.sistema_operativo_id = sistema_operativo.id AND testes.testers_pessoas_id IN (
SELECT pessoas_tem_contrato.pessoas_id FROM pessoas_tem_contrato, contrato WHERE pessoas_tem_contrato.contrato_id = contrato.id AND contrato.salario > 2000 )
GROUP BY sistema_operativo.versao;

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

#4. Aumenta o salário em 100 euros para programadores que usem PHP
UPDATE contrato SET salario = salario + 100 WHERE contrato.id IN (
SELECT pessoas_tem_contrato.contrato_id FROM pessoas_tem_contrato, programadores, linguagem_tem_programadores, linguagem WHERE linguagem.id = linguagem_tem_programadores.linguagem_id AND linguagem_tem_programadores.programadores_pessoas_id = pessoas_tem_contrato.pessoas_id
AND contrato.id = pessoas_tem_contrato.contrato_id AND linguagem.nome_linguagem = "PHP" );
