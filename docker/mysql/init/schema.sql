CREATE DATABASE IF NOT EXISTS `ordem`
  DEFAULT CHARACTER SET utf8mb4
  DEFAULT COLLATE utf8mb4_unicode_ci;

USE `ordem`;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id`      INT AUTO_INCREMENT PRIMARY KEY,
  `nome`    VARCHAR(100) NOT NULL,
  `usuario` VARCHAR(50)  NOT NULL UNIQUE,
  `senha`   VARCHAR(255) NOT NULL,
  `nivel`   VARCHAR(20)  DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `clientes` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `nome`          VARCHAR(150) NOT NULL,
  `endereco`      VARCHAR(255) DEFAULT NULL,
  `bairro`        VARCHAR(100) DEFAULT NULL,
  `telefone`      VARCHAR(20)  DEFAULT NULL,
  `data_cadastro` DATETIME     DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `ordens_servico` (
  `id`            INT AUTO_INCREMENT PRIMARY KEY,
  `cliente_id`    INT NOT NULL,
  `aparelho`      VARCHAR(100) DEFAULT NULL,
  `marca`         VARCHAR(100) DEFAULT NULL,
  `modelo`        VARCHAR(100) DEFAULT NULL,
  `defeito`       TEXT         DEFAULT NULL,
  `servico`       TEXT         DEFAULT NULL,
  `observacoes`   TEXT         DEFAULT NULL,
  `status`        VARCHAR(50)  DEFAULT 'Aguardando',
  `valor_servico` DECIMAL(10,2) DEFAULT 0.00,
  `desconto`      DECIMAL(10,2) DEFAULT 0.00,
  `valor_total`   DECIMAL(10,2) DEFAULT 0.00,
  `data_entrada`  DATETIME     DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`cliente_id`) REFERENCES `clientes`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `aparelhos` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `marcas` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `status_opcoes` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `bairros` (
  `id`   INT AUTO_INCREMENT PRIMARY KEY,
  `nome` VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `usuarios` (`nome`, `usuario`, `senha`, `nivel`)
VALUES ('Administrador', 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin');

INSERT INTO `aparelhos` (`nome`) VALUES
  ('Smartphone'), ('Tablet'), ('Notebook'), ('Desktop'), ('TV'), ('Monitor');
INSERT INTO `marcas` (`nome`) VALUES
  ('Samsung'), ('Apple'), ('LG'), ('Sony'), ('Dell'), ('Positivo');
INSERT INTO `status_opcoes` (`nome`) VALUES
  ('Aguardando'), ('Em Andamento'), ('Aguardando Peças'), ('Pronto'), ('Entregue');
INSERT INTO `bairros` (`nome`) VALUES
  ('Centro'), ('Jardim América'), ('Vila Nova'), ('Santa Tereza');
