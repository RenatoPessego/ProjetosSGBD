-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 10-Out-2023 às 01:30
-- Versão do servidor: 10.4.28-MariaDB
-- versão do PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `e4`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cliente`
--

CREATE TABLE `cliente` (
  `pessoas_id` int(10) UNSIGNED NOT NULL,
  `projeto_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `cliente`
--

INSERT INTO `cliente` (`pessoas_id`, `projeto_id`) VALUES
(26, 2),
(13, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `contrato`
--

CREATE TABLE `contrato` (
  `id` int(10) UNSIGNED NOT NULL,
  `funcao` enum('programador','tester','designer','gestor') NOT NULL,
  `salario` int(11) NOT NULL,
  `data_inic` varchar(45) NOT NULL,
  `data_fim` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `contrato`
--

INSERT INTO `contrato` (`id`, `funcao`, `salario`, `data_inic`, `data_fim`) VALUES
(1, 'gestor', 3000, '09/10/2023', '09/10/2024'),
(2, 'tester', 2150, '09/11/2022', '09/11/2023'),
(3, 'gestor', 4100, '30/10/2021', '30/10/2023'),
(4, 'gestor', 1700, '30/10/2022', '30/10/2023'),
(5, 'gestor', 1200, '09/10/2022', '09/10/2024'),
(6, 'tester', 1150, '10/11/2022', '10/11/2023'),
(7, 'tester', 3150, '14/11/2022', '14/11/2023'),
(8, 'tester', 950, '09/09/2022', '09/09/2023'),
(9, 'programador', 2000, '09/10/2023', '10/10/2024'),
(10, 'programador', 1400, '09/10/2023', '10/10/2024'),
(11, 'programador', 1500, '09/10/2023', '10/10/2024'),
(12, 'programador', 3200, '09/11/2020', '10/10/2024'),
(13, 'programador', 2000, '09/10/2022', '10/10/2024'),
(14, 'programador', 2222, '09/10/2021', '10/10/2024'),
(15, 'designer', 2300, '09/10/2023', '09/10/2025'),
(16, 'designer', 2000, '09/10/2022', '09/10/2025'),
(17, 'designer', 2100, '09/10/2021', '09/10/2025'),
(18, 'designer', 800, '15/10/2022', '15/10/2025'),
(19, 'designer', 2100, '09/10/2021', '09/10/2022'),
(20, 'tester', 3150, '14/11/2022', '14/11/2023'),
(21, 'gestor', 5000, '09/10/2023', '09/10/2025');

-- --------------------------------------------------------

--
-- Estrutura da tabela `designers`
--

CREATE TABLE `designers` (
  `pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `designers`
--

INSERT INTO `designers` (`pessoas_id`) VALUES
(24),
(27),
(28),
(29);

-- --------------------------------------------------------

--
-- Estrutura da tabela `designers_tem_estilo_art`
--

CREATE TABLE `designers_tem_estilo_art` (
  `designers_pessoas_id` int(10) UNSIGNED NOT NULL,
  `estilo_art_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `designers_tem_estilo_art`
--

INSERT INTO `designers_tem_estilo_art` (`designers_pessoas_id`, `estilo_art_id`) VALUES
(24, 1),
(27, 2),
(28, 1),
(29, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `estilo_art`
--

CREATE TABLE `estilo_art` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(45) NOT NULL,
  `utilidade` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `estilo_art`
--

INSERT INTO `estilo_art` (`id`, `nome`, `utilidade`) VALUES
(1, 'classicismo', 'Geral'),
(2, 'Realismo', '3D');

-- --------------------------------------------------------

--
-- Estrutura da tabela `gestores_projeto`
--

CREATE TABLE `gestores_projeto` (
  `pessoas_id` int(10) UNSIGNED NOT NULL COMMENT 'Apenas são aceites funcionarios altamente qualificados como gestores de projeto'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `gestores_projeto`
--

INSERT INTO `gestores_projeto` (`pessoas_id`) VALUES
(12),
(15),
(29),
(30),
(33);

-- --------------------------------------------------------

--
-- Estrutura da tabela `linguagem`
--

CREATE TABLE `linguagem` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome_linguagem` varchar(45) NOT NULL,
  `tipo_linguagem` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `linguagem`
--

INSERT INTO `linguagem` (`id`, `nome_linguagem`, `tipo_linguagem`) VALUES
(1, 'Java', 'AN'),
(2, 'Assembly', 'BN'),
(3, 'C++', 'AN'),
(4, 'Python', 'AN'),
(5, 'PHP', 'AN'),
(6, 'c#', 'AN'),
(7, 'Ruby', 'AN');

-- --------------------------------------------------------

--
-- Estrutura da tabela `linguagem_tem_programadores`
--

CREATE TABLE `linguagem_tem_programadores` (
  `linguagem_id` int(10) UNSIGNED NOT NULL,
  `programadores_pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `linguagem_tem_programadores`
--

INSERT INTO `linguagem_tem_programadores` (`linguagem_id`, `programadores_pessoas_id`) VALUES
(1, 31),
(3, 10),
(5, 12),
(5, 32),
(6, 15),
(7, 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoas`
--

CREATE TABLE `pessoas` (
  `id` int(10) UNSIGNED NOT NULL,
  `nome` varchar(45) NOT NULL,
  `qualificacao` enum('baixa','media','alta') DEFAULT NULL,
  `cidade` varchar(45) NOT NULL,
  `codigo_postal` varchar(45) NOT NULL,
  `rua` varchar(45) NOT NULL,
  `numero` varchar(45) NOT NULL,
  `empresa/pessoa` enum('empresa','pessoa') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `pessoas`
--

INSERT INTO `pessoas` (`id`, `nome`, `qualificacao`, `cidade`, `codigo_postal`, `rua`, `numero`, `empresa/pessoa`) VALUES
(1, 'Joao', 'baixa', 'Funchal', '9000', 'Rua 31 de Janeiro', '1', 'pessoa'),
(3, 'Maria', 'media', 'Porto', '2000', 'Rua do Ouro', '4', 'pessoa'),
(10, 'Fabio', 'alta', 'Porto', '2500', 'Rua dos Netos', '110', 'pessoa'),
(11, 'Rui', 'media', 'Braga', '3000', 'Rua da Prata', '12', 'pessoa'),
(12, 'Catarina', 'alta', 'Lisboa', '2000', 'Rua 5 de Outubro', '95', 'pessoa'),
(13, 'Arditi', 'alta', 'Funchal', '9000', 'Rua da Penteada', '13', 'empresa'),
(14, 'Luis', 'baixa', 'Faro', '6000', 'Rua das Pretas', '17', 'pessoa'),
(15, 'David', 'alta', 'Coimbra', '2800', 'Rua dos Estudantes', '28', 'pessoa'),
(24, 'Filipe', 'media', 'Caldas da Rainha', '2500', 'Rua José Fuller', '65', 'pessoa'),
(25, 'Mariana', 'alta', 'Beja', '3600', 'Rua dos Ferreiros', '79', 'pessoa'),
(26, 'AKI', 'baixa', 'Leiria', '7000', 'Rua dos Filhos', '4', 'empresa'),
(27, 'Renato', 'media', 'Funchal', '9000', 'Avenida do Mar', '45', 'pessoa'),
(28, 'Antonio', 'baixa', 'Beja', '3600', 'Rua dos Avos', '17', 'pessoa'),
(29, 'Mario', 'alta', 'Lisboa', '2000', 'Avenida da Liberdade', '92', 'pessoa'),
(30, 'Francisco', 'alta', 'Caldas da Rainha', '2500', 'Rua dos Passaros', '58', 'pessoa'),
(31, 'Joao Luis', 'media', 'Funchal', '9000', 'Rua dos Espetos', '45', 'pessoa'),
(32, 'Maria Joao', 'media', 'Lisboa', '2000', 'Rua do Ouro', '67', 'pessoa'),
(33, 'Marta', 'alta', 'Lisboa', '2000', 'Rua do Ouro', '67', 'pessoa');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pessoas_tem_contrato`
--

CREATE TABLE `pessoas_tem_contrato` (
  `pessoas_id` int(10) UNSIGNED NOT NULL,
  `contrato_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `pessoas_tem_contrato`
--

INSERT INTO `pessoas_tem_contrato` (`pessoas_id`, `contrato_id`) VALUES
(1, 6),
(3, 2),
(10, 9),
(11, 11),
(12, 10),
(14, 7),
(15, 12),
(24, 15),
(25, 8),
(27, 16),
(28, 17),
(29, 18),
(29, 19),
(30, 20),
(31, 13),
(32, 14),
(33, 21);

-- --------------------------------------------------------

--
-- Estrutura da tabela `programadores`
--

CREATE TABLE `programadores` (
  `pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `programadores`
--

INSERT INTO `programadores` (`pessoas_id`) VALUES
(10),
(11),
(12),
(15),
(31),
(32);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto`
--

CREATE TABLE `projeto` (
  `id` int(11) NOT NULL,
  `descricao` varchar(45) NOT NULL,
  `data_inicio` varchar(45) NOT NULL,
  `data_fim` varchar(45) NOT NULL,
  `complexidade` enum('baixa','media','alta') NOT NULL,
  `gestores_projeto_pessoas_id` int(10) UNSIGNED NOT NULL,
  `linguagem_id` int(10) UNSIGNED NOT NULL COMMENT 'Apenas sao aceites projetos na linguagem desejada'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `projeto`
--

INSERT INTO `projeto` (`id`, `descricao`, `data_inicio`, `data_fim`, `complexidade`, `gestores_projeto_pessoas_id`, `linguagem_id`) VALUES
(2, 'Criar Jogo', '09/10/2023', '09/12/2023', 'alta', 12, 3),
(3, 'Criar website', '10/02/2023', '10/03/2023', 'baixa', 29, 1),
(4, 'Criar sistema operativo', '10/10/2023', '10/12/2024', 'alta', 33, 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_tem_designers`
--

CREATE TABLE `projeto_tem_designers` (
  `projeto_id` int(11) NOT NULL,
  `designers_pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `projeto_tem_designers`
--

INSERT INTO `projeto_tem_designers` (`projeto_id`, `designers_pessoas_id`) VALUES
(2, 27),
(2, 29),
(3, 24);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_tem_programadores`
--

CREATE TABLE `projeto_tem_programadores` (
  `projeto_id` int(11) NOT NULL,
  `programadores_pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `projeto_tem_programadores`
--

INSERT INTO `projeto_tem_programadores` (`projeto_id`, `programadores_pessoas_id`) VALUES
(2, 10),
(2, 12),
(3, 31);

-- --------------------------------------------------------

--
-- Estrutura da tabela `projeto_tem_testers`
--

CREATE TABLE `projeto_tem_testers` (
  `projeto_id` int(11) NOT NULL,
  `testers_pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `projeto_tem_testers`
--

INSERT INTO `projeto_tem_testers` (`projeto_id`, `testers_pessoas_id`) VALUES
(2, 14),
(3, 25);

-- --------------------------------------------------------

--
-- Estrutura da tabela `sistema_operativo`
--

CREATE TABLE `sistema_operativo` (
  `id` int(10) UNSIGNED NOT NULL,
  `versao` varchar(45) NOT NULL,
  `data_lancamento` varchar(45) NOT NULL,
  `req_minimos` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `sistema_operativo`
--

INSERT INTO `sistema_operativo` (`id`, `versao`, `data_lancamento`, `req_minimos`) VALUES
(1, 'windows 11', '23/04/2020', '8 GB RAM'),
(2, 'Android 12', '01/01/2018', '2 GB RAM'),
(3, 'IOS 17', '01/07/2023', '4 GB RAM'),
(4, 'Linux', '28/10/2022', '6 GB RAM');

-- --------------------------------------------------------

--
-- Estrutura da tabela `testers`
--

CREATE TABLE `testers` (
  `pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `testers`
--

INSERT INTO `testers` (`pessoas_id`) VALUES
(1),
(3),
(14),
(25),
(30);

-- --------------------------------------------------------

--
-- Estrutura da tabela `testes`
--

CREATE TABLE `testes` (
  `id` int(10) UNSIGNED NOT NULL,
  `n_erros` int(11) NOT NULL,
  `data` varchar(45) NOT NULL,
  `testers_pessoas_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `testes`
--

INSERT INTO `testes` (`id`, `n_erros`, `data`, `testers_pessoas_id`) VALUES
(1, 0, '12/05/2021', 1),
(2, 2, '02/06/2021', 3),
(3, 0, '06/06/2021', 1),
(4, 15, '08/08/2021', 25),
(5, 27, '16/09/2022', 14),
(6, 6, '01/03/2023', 3),
(7, 5, '06/06/2021', 1),
(8, 5, '06/06/2021', 1),
(9, 5, '06/06/2022', 1),
(10, 5, '06/06/2023', 1),
(11, 5, '06/05/2023', 1),
(12, 5, '06/04/2023', 1),
(13, 5, '06/03/2023', 1),
(14, 5, '06/02/2023', 1),
(15, 5, '06/01/2023', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `testes_tem_linguagem`
--

CREATE TABLE `testes_tem_linguagem` (
  `testes_id` int(10) UNSIGNED NOT NULL,
  `linguagem_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `testes_tem_linguagem`
--

INSERT INTO `testes_tem_linguagem` (`testes_id`, `linguagem_id`) VALUES
(1, 1),
(2, 3),
(3, 1),
(4, 4),
(5, 5),
(6, 2),
(7, 4),
(8, 7),
(9, 3),
(10, 5),
(11, 6),
(12, 2),
(13, 3),
(14, 5),
(15, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `testes_tem_sistema_operativo`
--

CREATE TABLE `testes_tem_sistema_operativo` (
  `testes_id` int(10) UNSIGNED NOT NULL,
  `sistema_operativo_id` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Extraindo dados da tabela `testes_tem_sistema_operativo`
--

INSERT INTO `testes_tem_sistema_operativo` (`testes_id`, `sistema_operativo_id`) VALUES
(1, 3),
(2, 4),
(3, 3),
(4, 1),
(5, 2),
(6, 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `cliente`
--
ALTER TABLE `cliente`
  ADD KEY `fk_cliente_projeto1_idx` (`projeto_id`),
  ADD KEY `fk_cliente_pessoas1` (`pessoas_id`);

--
-- Índices para tabela `contrato`
--
ALTER TABLE `contrato`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `designers`
--
ALTER TABLE `designers`
  ADD PRIMARY KEY (`pessoas_id`),
  ADD KEY `ix_pessoas_id` (`pessoas_id`);

--
-- Índices para tabela `designers_tem_estilo_art`
--
ALTER TABLE `designers_tem_estilo_art`
  ADD PRIMARY KEY (`designers_pessoas_id`,`estilo_art_id`),
  ADD KEY `fk_designers_has_estilo_art_estilo_art1_idx` (`estilo_art_id`),
  ADD KEY `fk_designers_has_estilo_art_designers1_idx` (`designers_pessoas_id`);

--
-- Índices para tabela `estilo_art`
--
ALTER TABLE `estilo_art`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `gestores_projeto`
--
ALTER TABLE `gestores_projeto`
  ADD PRIMARY KEY (`pessoas_id`),
  ADD KEY `ix_pessoas_id` (`pessoas_id`);

--
-- Índices para tabela `linguagem`
--
ALTER TABLE `linguagem`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `linguagem_tem_programadores`
--
ALTER TABLE `linguagem_tem_programadores`
  ADD PRIMARY KEY (`linguagem_id`,`programadores_pessoas_id`),
  ADD KEY `fk_linguagem_has_programadores_programadores1_idx` (`programadores_pessoas_id`),
  ADD KEY `fk_linguagem_has_programadores_linguagem1_idx` (`linguagem_id`);

--
-- Índices para tabela `pessoas`
--
ALTER TABLE `pessoas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ix_pessoas_id` (`id`);

--
-- Índices para tabela `pessoas_tem_contrato`
--
ALTER TABLE `pessoas_tem_contrato`
  ADD PRIMARY KEY (`pessoas_id`,`contrato_id`),
  ADD KEY `fk_pessoas_has_contrato_contrato1_idx` (`contrato_id`),
  ADD KEY `fk_pessoas_has_contrato_pessoas_idx` (`pessoas_id`);

--
-- Índices para tabela `programadores`
--
ALTER TABLE `programadores`
  ADD PRIMARY KEY (`pessoas_id`),
  ADD KEY `ix_pessoas_id` (`pessoas_id`);

--
-- Índices para tabela `projeto`
--
ALTER TABLE `projeto`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id_UNIQUE` (`id`),
  ADD KEY `fk_projeto_gestores_projeto1_idx` (`gestores_projeto_pessoas_id`),
  ADD KEY `fk_projeto_linguagem1_idx` (`linguagem_id`);

--
-- Índices para tabela `projeto_tem_designers`
--
ALTER TABLE `projeto_tem_designers`
  ADD PRIMARY KEY (`projeto_id`,`designers_pessoas_id`),
  ADD KEY `fk_projeto_has_designers_designers1_idx` (`designers_pessoas_id`),
  ADD KEY `fk_projeto_has_designers_projeto1_idx` (`projeto_id`);

--
-- Índices para tabela `projeto_tem_programadores`
--
ALTER TABLE `projeto_tem_programadores`
  ADD PRIMARY KEY (`projeto_id`,`programadores_pessoas_id`),
  ADD KEY `fk_projeto_has_programadores_programadores1_idx` (`programadores_pessoas_id`),
  ADD KEY `fk_projeto_has_programadores_projeto1_idx` (`projeto_id`);

--
-- Índices para tabela `projeto_tem_testers`
--
ALTER TABLE `projeto_tem_testers`
  ADD PRIMARY KEY (`projeto_id`,`testers_pessoas_id`),
  ADD KEY `fk_projeto_has_testers_testers1_idx` (`testers_pessoas_id`),
  ADD KEY `fk_projeto_has_testers_projeto1_idx` (`projeto_id`);

--
-- Índices para tabela `sistema_operativo`
--
ALTER TABLE `sistema_operativo`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `testers`
--
ALTER TABLE `testers`
  ADD PRIMARY KEY (`pessoas_id`),
  ADD KEY `ix_pessoas_id` (`pessoas_id`);

--
-- Índices para tabela `testes`
--
ALTER TABLE `testes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_testes_testers1_idx` (`testers_pessoas_id`);

--
-- Índices para tabela `testes_tem_linguagem`
--
ALTER TABLE `testes_tem_linguagem`
  ADD PRIMARY KEY (`testes_id`,`linguagem_id`),
  ADD KEY `fk_testes_has_linguagem_linguagem1_idx` (`linguagem_id`),
  ADD KEY `fk_testes_has_linguagem_testes1_idx` (`testes_id`);

--
-- Índices para tabela `testes_tem_sistema_operativo`
--
ALTER TABLE `testes_tem_sistema_operativo`
  ADD PRIMARY KEY (`testes_id`,`sistema_operativo_id`),
  ADD KEY `fk_testes_has_sistema_operativo_sistema_operativo1_idx` (`sistema_operativo_id`),
  ADD KEY `fk_testes_has_sistema_operativo_testes1_idx` (`testes_id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `contrato`
--
ALTER TABLE `contrato`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de tabela `estilo_art`
--
ALTER TABLE `estilo_art`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `linguagem`
--
ALTER TABLE `linguagem`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `pessoas`
--
ALTER TABLE `pessoas`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `projeto`
--
ALTER TABLE `projeto`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `sistema_operativo`
--
ALTER TABLE `sistema_operativo`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `testes`
--
ALTER TABLE `testes`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- Restrições para despejos de tabelas
--

--
-- Limitadores para a tabela `cliente`
--
ALTER TABLE `cliente`
  ADD CONSTRAINT `fk_cliente_pessoas1` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_cliente_projeto1` FOREIGN KEY (`projeto_id`) REFERENCES `projeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `designers`
--
ALTER TABLE `designers`
  ADD CONSTRAINT `fk_designers_pessoas1` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `designers_tem_estilo_art`
--
ALTER TABLE `designers_tem_estilo_art`
  ADD CONSTRAINT `fk_designers_has_estilo_art_designers1` FOREIGN KEY (`designers_pessoas_id`) REFERENCES `designers` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_designers_has_estilo_art_estilo_art1` FOREIGN KEY (`estilo_art_id`) REFERENCES `estilo_art` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `gestores_projeto`
--
ALTER TABLE `gestores_projeto`
  ADD CONSTRAINT `fk_gestores_projeto_pessoas1` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `linguagem_tem_programadores`
--
ALTER TABLE `linguagem_tem_programadores`
  ADD CONSTRAINT `fk_linguagem_has_programadores_linguagem1` FOREIGN KEY (`linguagem_id`) REFERENCES `linguagem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_linguagem_has_programadores_programadores1` FOREIGN KEY (`programadores_pessoas_id`) REFERENCES `programadores` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `pessoas_tem_contrato`
--
ALTER TABLE `pessoas_tem_contrato`
  ADD CONSTRAINT `fk_pessoas_has_contrato_contrato1` FOREIGN KEY (`contrato_id`) REFERENCES `contrato` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_pessoas_has_contrato_pessoas` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `programadores`
--
ALTER TABLE `programadores`
  ADD CONSTRAINT `fk_programadores_pessoas1` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `projeto`
--
ALTER TABLE `projeto`
  ADD CONSTRAINT `fk_projeto_gestores_projeto1` FOREIGN KEY (`gestores_projeto_pessoas_id`) REFERENCES `gestores_projeto` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_projeto_linguagem1` FOREIGN KEY (`linguagem_id`) REFERENCES `linguagem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `projeto_tem_designers`
--
ALTER TABLE `projeto_tem_designers`
  ADD CONSTRAINT `fk_projeto_has_designers_designers1` FOREIGN KEY (`designers_pessoas_id`) REFERENCES `designers` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_projeto_has_designers_projeto1` FOREIGN KEY (`projeto_id`) REFERENCES `projeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `projeto_tem_programadores`
--
ALTER TABLE `projeto_tem_programadores`
  ADD CONSTRAINT `fk_projeto_has_programadores_programadores1` FOREIGN KEY (`programadores_pessoas_id`) REFERENCES `programadores` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_projeto_has_programadores_projeto1` FOREIGN KEY (`projeto_id`) REFERENCES `projeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `projeto_tem_testers`
--
ALTER TABLE `projeto_tem_testers`
  ADD CONSTRAINT `fk_projeto_has_testers_projeto1` FOREIGN KEY (`projeto_id`) REFERENCES `projeto` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_projeto_has_testers_testers1` FOREIGN KEY (`testers_pessoas_id`) REFERENCES `testers` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `testers`
--
ALTER TABLE `testers`
  ADD CONSTRAINT `fk_testers_pessoas1` FOREIGN KEY (`pessoas_id`) REFERENCES `pessoas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `testes`
--
ALTER TABLE `testes`
  ADD CONSTRAINT `fk_testes_testers1` FOREIGN KEY (`testers_pessoas_id`) REFERENCES `testers` (`pessoas_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `testes_tem_linguagem`
--
ALTER TABLE `testes_tem_linguagem`
  ADD CONSTRAINT `fk_testes_has_linguagem_linguagem1` FOREIGN KEY (`linguagem_id`) REFERENCES `linguagem` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_testes_has_linguagem_testes1` FOREIGN KEY (`testes_id`) REFERENCES `testes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `testes_tem_sistema_operativo`
--
ALTER TABLE `testes_tem_sistema_operativo`
  ADD CONSTRAINT `fk_testes_has_sistema_operativo_sistema_operativo1` FOREIGN KEY (`sistema_operativo_id`) REFERENCES `sistema_operativo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_testes_has_sistema_operativo_testes1` FOREIGN KEY (`testes_id`) REFERENCES `testes` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
