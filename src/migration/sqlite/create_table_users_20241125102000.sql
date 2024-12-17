CREATE TABLE IF NOT EXISTS `users` (
    `id` INTEGER PRIMARY KEY AUTOINCREMENT,
    `nome` TEXT NOT NULL,
    `cpf` TEXT NOT NULL UNIQUE,
    `valido` INTEGER NOT NULL, -- Usando como booleano, 0/1 somente
    `created_at` TEXT NOT NULL,
    `updated_at` TEXT NULL
);
