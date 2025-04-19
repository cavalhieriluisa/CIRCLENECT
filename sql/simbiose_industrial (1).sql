-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 16/04/2025 às 11:16
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `simbiose_industrial`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais`
--

CREATE TABLE `materiais` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL CHECK (`quantidade` >= 0),
  `unidade` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `disponibilidade` varchar(50) NOT NULL,
  `preco` decimal(10,2) DEFAULT 0.00,
  `telefone` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `materiais`
--

INSERT INTO `materiais` (`id`, `nome`, `quantidade`, `unidade`, `descricao`, `categoria`, `disponibilidade`, `preco`, `telefone`, `email`, `imagem`) VALUES
(1, 'garrafa', 100, 'kg', 'eqdq', 'plastico', 'doacao', 1.00, '14991556094', 'cavalhieri.luisa@gmail.com', NULL),
(5, 'edef', 12, 'kg', 'fxvxvx', 'plastico', 'doacao', 1.00, '14991556094', 'cavalhieri.luisa@gmail.com', '../uploads/67f130097d577-Adubo.jpg');

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `id_referencia` int(11) NOT NULL,
  `empresa` varchar(255) DEFAULT NULL,
  `material` varchar(255) DEFAULT NULL,
  `data_interesse` datetime DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `cnpj` varchar(18) DEFAULT NULL,
  `responsavel` varchar(100) DEFAULT NULL,
  `telefone_empresa` varchar(20) DEFAULT NULL,
  `municipio` varchar(100) DEFAULT NULL,
  `estado` varchar(2) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `nome_material` varchar(100) DEFAULT NULL,
  `quantidade` decimal(10,2) DEFAULT NULL,
  `unidade` varchar(20) DEFAULT NULL,
  `descricao` text DEFAULT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `disponibilidade` varchar(50) DEFAULT NULL,
  `preco` decimal(10,2) DEFAULT NULL,
  `notificacoes` tinyint(1) DEFAULT 0,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `id_referencia`, `empresa`, `material`, `data_interesse`, `company_name`, `cnpj`, `responsavel`, `telefone_empresa`, `municipio`, `estado`, `email`, `nome_material`, `quantidade`, `unidade`, `descricao`, `categoria`, `disponibilidade`, `preco`, `notificacoes`, `id_usuario`) VALUES
(14, 1, 'Ambientallife', 'garrafa', '2025-04-15 19:31:58', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, 0);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisa_unificada`
--

CREATE TABLE `pesquisa_unificada` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `responsavel` varchar(255) NOT NULL,
  `municipio` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `email` varchar(255) NOT NULL,
  `nome_material` varchar(255) NOT NULL,
  `quantidade` int(11) NOT NULL CHECK (`quantidade` >= 0),
  `unidade` varchar(50) NOT NULL,
  `descricao` text NOT NULL,
  `categoria` varchar(100) NOT NULL,
  `disponibilidade` varchar(50) NOT NULL,
  `preco` decimal(10,2) DEFAULT 0.00,
  `notificacoes` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pesquisa_unificada`
--

INSERT INTO `pesquisa_unificada` (`id`, `company_name`, `responsavel`, `municipio`, `estado`, `email`, `nome_material`, `quantidade`, `unidade`, `descricao`, `categoria`, `disponibilidade`, `preco`, `notificacoes`) VALUES
(1, 'Ambientallife', 'Luísa Gimenes Cavalhieri', 'bauru', 'SP', 'cavalhieri.luisa@gmail.com', 'garrafa', 100, 'kg', 'eqdq', 'plastico', 'doacao', 1.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `cnpj` varchar(18) NOT NULL,
  `responsavel` varchar(255) NOT NULL,
  `telefone` varchar(20) NOT NULL,
  `rua` varchar(255) NOT NULL,
  `municipio` varchar(255) NOT NULL,
  `estado` varchar(2) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `company_name`, `cnpj`, `responsavel`, `telefone`, `rua`, `municipio`, `estado`, `email`, `password`) VALUES
(1, 'Ambientallife', 'XXX. XXX/0001-XX', 'Luísa Gimenes Cavalhieri', '14991556094', 'asdf', 'bauru', 'SP', 'cavalhieri.luisa@gmail.com', '$2y$10$zBF20hXl.dYPI1yR54FOzu6KGlKRXwUXZST/Qv38KLT3ccLnrP8WK'),
(5, 'CarlosRecicla', '9999999999', 'Carlos', '14991556094', 'Rua Tubarão', 'SP', 'SP', 'carlos@gmail.com', '$2y$10$RG35nWAfqFuDP4hApQB.WOqq0EC6cUHrA6Iqx9/BNWSN59sD5aHp.');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `materiais`
--
ALTER TABLE `materiais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_material_email` (`email`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`);

--
-- Índices de tabela `pesquisa_unificada`
--
ALTER TABLE `pesquisa_unificada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `materiais`
--
ALTER TABLE `materiais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT de tabela `pesquisa_unificada`
--
ALTER TABLE `pesquisa_unificada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `materiais`
--
ALTER TABLE `materiais`
  ADD CONSTRAINT `materiais_ibfk_1` FOREIGN KEY (`email`) REFERENCES `usuarios` (`email`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pesquisa_unificada`
--
ALTER TABLE `pesquisa_unificada`
  ADD CONSTRAINT `pesquisa_unificada_ibfk_1` FOREIGN KEY (`email`) REFERENCES `usuarios` (`email`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
