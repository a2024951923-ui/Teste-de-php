-- Apaga as tabelas antigas se elas existirem, para começar do zero.
DROP TABLE IF EXISTS `atividades`;
DROP TABLE IF EXISTS `usuarios`;

-- Cria a tabela de usuários (funcional com o PHP)
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` enum('aluno','professor') NOT NULL DEFAULT 'aluno',
  `turma` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Cria a tabela de atividades (funcional com o PHP)
CREATE TABLE `atividades` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titulo` varchar(150) NOT NULL,
  `descricao` text DEFAULT NULL,
  `id_professor` int(11) NOT NULL,
  `id_turma` varchar(50) NOT NULL,
  `data_criacao` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insere um professor para você poder testar
-- E-mail: professor@exemplo.com | Senha: admin123
INSERT INTO `usuarios` (`nome`, `email`, `senha`, `tipo`, `turma`) VALUES
('Professor Admin', 'professor@exemplo.com', '$2y$10$3V.7.Q7G2t1qj/j2vF6E4eUj2/a9a4bS.Z9a.Z9a.Z9a.', 'professor', NULL);
