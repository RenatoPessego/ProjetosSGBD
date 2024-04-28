#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122422  Leonardo Ferreira
#---------------------------------------------------------------------------------------------------------------------------------------------------

# 1. Mostra a qualifiacacao e a linguagem de cada programador que tem joao no nome:
SELECT nome,qualificacao,linguagem.nome_linguagem FROM pessoas, programadores, linguagem_tem_programadores, linguagem WHERE programadores.pessoas_id=pessoas.id AND linguagem_tem_programadores.programadores_pessoas_id=programadores.pessoas_id AND linguagem.id=linguagem_tem_programadores.linguagem_id AND programadores.pessoas_id IN (SELECT pessoas.id FROM pessoas WHERE nome like "%Joao%");

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122422  Leonardo Ferreira
#---------------------------------------------------------------------------------------------------------------------------------------------------

#2. Mostra o nº de erros por teste e quem os fez e a data:
SELECT testes.id, nome, n_erros, testes.data FROM pessoas INNER JOIN testers ON pessoas.id=testers.pessoas_id INNER JOIN testes ON testes.testers_pessoas_id=testers.pessoas_id;

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122422  Leonardo Ferreira
#---------------------------------------------------------------------------------------------------------------------------------------------------

#3. Devolve a soma do numero de erros dos testes por sistema operativo
SELECT SUM(n_erros) AS n_erros, sistema_operativo.versao AS sistema_operativo FROM testes INNER JOIN testes_tem_sistema_operativo ON testes.id = testes_tem_sistema_operativo.testes_id INNER JOIN sistema_operativo ON testes_tem_sistema_operativo.sistema_operativo_id=sistema_operativo.id
GROUP BY sistema_operativo.id;

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122422  Leonardo Ferreira
#---------------------------------------------------------------------------------------------------------------------------------------------------

#4. coloca o numero de erros igual 0 nos testes que têm sistema operativo IOS 17
UPDATE testes
SET n_erros=0 WHERE testes.id IN (SELECT testes_tem_sistema_operativo.testes_id FROM testes_tem_sistema_operativo INNER JOIN sistema_operativo ON testes_tem_sistema_operativo.sistema_operativo_id=sistema_operativo.id WHERE sistema_operativo.versao LIKE "IOS 17");

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2121922  Renato Pessego
#---------------------------------------------------------------------------------------------------------------------------------------------------

#5. Mostra todos os gestores de projeto que têm um projeto de complexidade alta.
SELECT gestores_projeto.pessoas_id, nome, qualificacao FROM pessoas, gestores_projeto WHERE gestores_projeto.pessoas_id = pessoas.id AND gestores_projeto.pessoas_id IN (SELECT projeto.gestores_projeto_pessoas_id FROM projeto WHERE projeto.complexidade LIKE "alta");

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2121922  Renato Pessego
#---------------------------------------------------------------------------------------------------------------------------------------------------

#6. Mostra o contrato de todas as pessoas com alguma funcao na empresa
SELECT contrato.salario, contrato.funcao, pessoas.nome, pessoas.qualificacao, pessoas.cidade FROM contrato INNER JOIN pessoas_tem_contrato ON contrato.id = pessoas_tem_contrato.contrato_id INNER JOIN pessoas ON pessoas_tem_contrato.pessoas_id = pessoas.id

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2121922  Renato Pessego
#---------------------------------------------------------------------------------------------------------------------------------------------------

#7. Mostra as despesas em salarios por mes, por qualificacao

SELECT SUM(contrato.salario) AS despesas, pessoas.qualificacao AS qualificacao FROM contrato INNER JOIN pessoas_tem_contrato on contrato.id = pessoas_tem_contrato.contrato_id INNER JOIN pessoas ON pessoas_tem_contrato.pessoas_id = pessoas.id GROUP BY qualificacao;

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2121922  Renato Pessego
#---------------------------------------------------------------------------------------------------------------------------------------------------

#8. Aumentar o salario em 50 se um tester fizer mais de 10 testes

UPDATE contrato SET salario = salario + 50 WHERE contrato.id IN (SELECT contrato.id FROM contrato INNER JOIN pessoas_tem_contrato ON contrato.id = pessoas_tem_contrato.contrato_id INNER JOIN pessoas ON pessoas_tem_contrato.pessoas_id = pessoas.id INNER JOIN testers ON pessoas.id = testers.pessoas_id WHERE (SELECT COUNT(testes.id)>10 FROM testes));

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

#9 Mostra a soma das dificuldades e o numero de projetos
SELECT COUNT(projeto.complexidade) FROM projeto WHERE projeto.complexidade = 'Alta'; 

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------
#10 Conta o numero de funcionários por qualificacao
SELECT COUNT(pessoas.id), qualificacao FROM pessoas GROUP BY pessoas.qualificacao;

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

#11 Junta os funcionários com a sua linguagem de programação 
SELECT pessoas.id, nome, linguagem_tem_programadores.programadores_pessoas_id, linguagem.nome_linguagem FROM linguagem_tem_programadores INNER JOIN linguagem ON linguagem_tem_programadores.linguagem_id = linguagem.id INNER JOIN pessoas ON pessoas.id = linguagem_tem_programadores.programadores_pessoas_id; 

#---------------------------------------------------------------------------------------------------------------------------------------------------
#--- 2122722  Lucas Neves
#---------------------------------------------------------------------------------------------------------------------------------------------------

#12 Mete todos os funcionários com baixa qualificação com o salario de 800 euros 
UPDATE contrato SET salario = 800 WHERE contrato.id IN (SELECT contrato.id FROM contrato INNER JOIN pessoas_tem_contrato ON contrato.id = pessoas_tem_contrato.contrato_id INNER JOIN pessoas ON pessoas_tem_contrato.pessoas_id = pessoas.id WHERE pessoas.qualificacao = "Baixa");
