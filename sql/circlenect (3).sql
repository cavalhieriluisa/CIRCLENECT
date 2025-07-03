-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 03/07/2025 às 06:28
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `circlenect`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `curtidas`
--

CREATE TABLE `curtidas` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_notificacao` int(11) NOT NULL,
  `data_curtida` datetime DEFAULT current_timestamp(),
  `id_material` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `curtidas`
--

INSERT INTO `curtidas` (`id`, `id_usuario`, `id_notificacao`, `data_curtida`, `id_material`) VALUES
(61, 1, 83, '2025-06-30 19:08:08', 3),
(62, 3, 84, '2025-07-02 03:58:32', 2),
(63, 4, 85, '2025-07-03 01:09:44', 3);

--
-- Acionadores `curtidas`
--
DELIMITER $$
CREATE TRIGGER `apagar_notificacao_quando_ultima_curtida` AFTER DELETE ON `curtidas` FOR EACH ROW BEGIN
    DECLARE total INT;

    -- Verifica quantas curtidas ainda existem para essa notificação
    SELECT COUNT(*) INTO total
    FROM curtidas
    WHERE id_notificacao = OLD.id_notificacao;

    -- Se não restar nenhuma curtida, apaga a notificação
    IF total = 0 THEN
        DELETE FROM notificacoes WHERE id = OLD.id_notificacao;
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `materiais`
--

CREATE TABLE `materiais` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
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

INSERT INTO `materiais` (`id`, `id_usuario`, `nome`, `quantidade`, `unidade`, `descricao`, `categoria`, `disponibilidade`, `preco`, `telefone`, `email`, `imagem`) VALUES
(11, 1, 'effwrgfew', 3, 'kg', 'zfxgfzsgwrasgwr', 'plastico', 'doacao', 10.00, '14991556094', 'cavalhieri.luisa@gmail.com', 'uploads/686259ede9326-Logo _EcoContador_ em Tons de Verde.png'),
(12, 2, 'wdqrwqr', 2, 'kg', 'eqffrqeffqe', 'metal', 'doacao', 4.00, '14991556094', 'cavalhieri@gmail.com', 'uploads/68625a4b3346b-ods (2).jpg'),
(13, 3, 'garrafas pet', 100, 'kg', 'garrafas pets coletadas em companhias de reciclagem', 'plastico', 'venda', 5.00, '14991556094', 'julia.silva@gmail.com', 'uploads/6864d883cd7f2-Wherenaturebecomesart.png');

--
-- Acionadores `materiais`
--
DELIMITER $$
CREATE TRIGGER `trg_materiais_after_delete` AFTER DELETE ON `materiais` FOR EACH ROW BEGIN
  DELETE FROM pesquisa_unificada
  WHERE id_usuario = OLD.id_usuario AND nome_material = OLD.nome;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_materiais_after_insert` AFTER INSERT ON `materiais` FOR EACH ROW BEGIN
  INSERT INTO pesquisa_unificada (
    id_usuario, company_name, responsavel, municipio, estado, email,
    nome_material, quantidade, unidade, descricao, categoria, disponibilidade, preco, notificacoes
  )
  SELECT 
    NEW.id_usuario,
    u.company_name,
    u.responsavel,
    u.municipio,
    u.estado,
    NEW.email,
    NEW.nome,
    NEW.quantidade,
    NEW.unidade,
    NEW.descricao,
    NEW.categoria,
    NEW.disponibilidade,
    NEW.preco,
    0
  FROM usuarios u
  WHERE u.id = NEW.id_usuario;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `trg_materiais_after_update` AFTER UPDATE ON `materiais` FOR EACH ROW BEGIN
  UPDATE pesquisa_unificada pu
  SET 
    pu.nome_material = NEW.nome,
    pu.quantidade = NEW.quantidade,
    pu.unidade = NEW.unidade,
    pu.descricao = NEW.descricao,
    pu.categoria = NEW.categoria,
    pu.disponibilidade = NEW.disponibilidade,
    pu.preco = NEW.preco,
    pu.email = NEW.email
  WHERE pu.id_usuario = NEW.id_usuario AND pu.nome_material = OLD.nome;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Estrutura para tabela `notificacoes`
--

CREATE TABLE `notificacoes` (
  `id` int(11) NOT NULL,
  `id_referencia` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
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
  `notificacoes` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `notificacoes`
--

INSERT INTO `notificacoes` (`id`, `id_referencia`, `id_usuario`, `empresa`, `material`, `data_interesse`, `company_name`, `cnpj`, `responsavel`, `telefone_empresa`, `municipio`, `estado`, `email`, `nome_material`, `quantidade`, `unidade`, `descricao`, `categoria`, `disponibilidade`, `preco`, `notificacoes`) VALUES
(83, 3, 2, 'madeira life', 'wdqrwqr', '2025-06-30 19:08:08', 'madeira life', NULL, 'Luísa Gimenes Cavalhieri', NULL, 'sao paulo', 'SP', 'cavalhieri@gmail.com', 'wdqrwqr', 2.00, 'kg', '0', 'metal', 'doacao', 4.00, 1),
(84, 2, 1, 'Ambiental Life', 'effwrgfew', '2025-07-02 03:58:32', 'Ambiental Life', NULL, 'Luísa Gimenes Cavalhieri', NULL, 'SP', 'SP', 'cavalhieri.luisa@gmail.com', 'effwrgfew', 3.00, 'kg', '0', 'plastico', 'doacao', 10.00, 1),
(85, 3, 2, 'madeira life', 'wdqrwqr', '2025-07-03 01:09:44', 'madeira life', NULL, 'Luísa Gimenes Cavalhieri', NULL, 'sao paulo', 'SP', 'cavalhieri@gmail.com', 'wdqrwqr', 2.00, 'kg', '0', 'metal', 'doacao', 4.00, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `pesquisa_unificada`
--

CREATE TABLE `pesquisa_unificada` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
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

INSERT INTO `pesquisa_unificada` (`id`, `id_usuario`, `company_name`, `responsavel`, `municipio`, `estado`, `email`, `nome_material`, `quantidade`, `unidade`, `descricao`, `categoria`, `disponibilidade`, `preco`, `notificacoes`) VALUES
(2, 1, 'Ambiental Life', 'Luísa Gimenes Cavalhieri', 'SP', 'SP', 'cavalhieri.luisa@gmail.com', 'effwrgfew', 3, 'kg', 'zfxgfzsgwrasgwr', 'plastico', 'doacao', 10.00, 1),
(3, 2, 'madeira life', 'Luísa Gimenes Cavalhieri', 'sao paulo', 'SP', 'cavalhieri@gmail.com', 'wdqrwqr', 2, 'kg', 'eqffrqeffqe', 'metal', 'doacao', 4.00, 1),
(4, 3, 'Eco', 'Julia Oliveira', 'Curitiba', 'SP', 'julia.silva@gmail.com', 'garrafas pet', 100, 'kg', 'garrafas pets coletadas em companhias de reciclagem', 'plastico', 'venda', 5.00, 0);

--
-- Acionadores `pesquisa_unificada`
--
DELIMITER $$
CREATE TRIGGER `criar_notificacao_após_curtida` AFTER UPDATE ON `pesquisa_unificada` FOR EACH ROW BEGIN
  IF NEW.notificacoes = 1 AND OLD.notificacoes = 0 THEN
    INSERT INTO notificacoes (
      id_referencia, empresa, material, data_interesse, company_name, cnpj, responsavel, telefone_empresa, municipio,
      estado, email, nome_material, quantidade, unidade, descricao, categoria, disponibilidade, preco, notificacoes, id_usuario
    )
    SELECT
      NEW.id, u.company_name, NEW.nome_material, NOW(), u.company_name, u.cnpj, u.responsavel, u.telefone, u.municipio,
      u.estado, NEW.email, NEW.nome_material, NEW.quantidade, NEW.unidade, NEW.descricao, NEW.categoria,
      NEW.disponibilidade, NEW.preco, 0, u.id
    FROM usuarios u
    WHERE u.id = NEW.id_usuario;
  END IF;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `criar_notificacao_e_curtida` AFTER UPDATE ON `pesquisa_unificada` FOR EACH ROW BEGIN
  DECLARE nova_notificacao_id INT;

  IF NEW.notificacoes = 1 AND OLD.notificacoes = 0 THEN
    -- Cria notificação
    INSERT INTO notificacoes (
      id_referencia, empresa, material, data_interesse, company_name, cnpj, responsavel,
      telefone_empresa, municipio, estado, email, nome_material, quantidade, unidade,
      descricao, categoria, disponibilidade, preco, notificacoes, id_usuario
    )
    SELECT
      NEW.id, u.company_name, NEW.nome_material, NOW(), u.company_name, u.cnpj, u.responsavel,
      u.telefone, u.municipio, u.estado, NEW.email, NEW.nome_material, NEW.quantidade,
      NEW.unidade, NEW.descricao, NEW.categoria, NEW.disponibilidade, NEW.preco, 0, u.id
    FROM usuarios u
    WHERE u.id = NEW.id_usuario;

    -- Captura o ID da notificação recém-criada
    SET nova_notificacao_id = LAST_INSERT_ID();

    -- Insere curtida associada corretamente
    INSERT IGNORE INTO curtidas (id_usuario, id_material, id_notificacao)
    VALUES (NEW.id_usuario, NEW.id, nova_notificacao_id);
  END IF;
END
$$
DELIMITER ;

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
(1, 'Ambiental Life', 'xxxxxxxxxxx', 'Luísa Gimenes Cavalhieri', '14991556094', 'Tubarão', 'SP', 'SP', 'cavalhieri.luisa@gmail.com', '$2y$10$9XBRCkTCZGofkKz24hOj3utdpfc0omJOGfEjUDk6GuxcurFkFcunW'),
(2, 'madeira life', 'xxxxxxxxx1', 'Luísa Gimenes Cavalhieri', '14991556094', 'tamandua', 'sao paulo', 'SP', 'cavalhieri@gmail.com', '$2y$10$WZe7U/7JKN0VRNru8Zzc6.sBT6U6rdr.KaEfPReQKqCYvBJxcFlUW'),
(3, 'Eco', 'xxxxxxxxx2', 'Julia Oliveira', '14991556094', 'Cascavel', 'Curitiba', 'SP', 'julia.silva@gmail.com', '$2y$10$KbmzO1yCSuckLWKg.DiNrO6Yyi8jnUjfGMXw0RRyeDUki81pVsxnm'),
(4, 'Unesp', 'xxxxxxxxx3', 'Paula', '14991556094', 'X', 'Bauru', 'SP', 'paula@gmail.com', '$2y$10$.BnmkaFt5CsiLiVY.RH1/OnYKymQ1.rb8ETrxoTyMSYy/qtllBfWa');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `curtidas`
--
ALTER TABLE `curtidas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `un_usuario_material` (`id_usuario`,`id_material`),
  ADD KEY `id_notificacao` (`id_notificacao`);

--
-- Índices de tabela `materiais`
--
ALTER TABLE `materiais`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `pesquisa_unificada`
--
ALTER TABLE `pesquisa_unificada`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cnpj` (`cnpj`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `curtidas`
--
ALTER TABLE `curtidas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT de tabela `materiais`
--
ALTER TABLE `materiais`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `notificacoes`
--
ALTER TABLE `notificacoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=88;

--
-- AUTO_INCREMENT de tabela `pesquisa_unificada`
--
ALTER TABLE `pesquisa_unificada`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `curtidas`
--
ALTER TABLE `curtidas`
  ADD CONSTRAINT `curtidas_ibfk_2` FOREIGN KEY (`id_notificacao`) REFERENCES `notificacoes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `materiais`
--
ALTER TABLE `materiais`
  ADD CONSTRAINT `materiais_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `notificacoes`
--
ALTER TABLE `notificacoes`
  ADD CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pesquisa_unificada`
--
ALTER TABLE `pesquisa_unificada`
  ADD CONSTRAINT `pesquisa_unificada_ibfk_1` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
