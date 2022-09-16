CREATE DATABASE IF NOT EXISTS test_shop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Customer table
CREATE TABLE test_shop.`Customer` (
     `id` INT AUTO_INCREMENT NOT NULL,
     `firstname` VARCHAR(100) NOT NULL,
     `lastname` VARCHAR(100) NOT NULL,
     `email` VARCHAR(100) NOT NULL,
     `phone`  VARCHAR(20) NOT NULL,
     `created_at` datetime DEFAULT CURDATE(),
     PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Product table
CREATE TABLE test_shop.`Product` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `title` VARCHAR(100) NOT NULL,
    `category` VARCHAR(100) NOT NULL,
    `description` TINYTEXT NOT NULL,
    `price` DECIMAL(6,2) NOT NULL,
    `imageUrl` VARCHAR(100) NOT NULL,
    `created_at` datetime DEFAULT CURDATE(),
    PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Order table
CREATE TABLE test_shop.`Order` (
    `id` INT AUTO_INCREMENT NOT NULL,
    `customer_id` INT NOT NULL,
    `amount` DECIMAL(6,2) NOT NULL,
    `created_at` datetime DEFAULT CURDATE(),
    PRIMARY KEY(id),
    CONSTRAINT FK_2 FOREIGN KEY (customer_id) REFERENCES `Customer` (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- OrderItem table
CREATE TABLE test_shop.`OrderItem` (
     `id` INT AUTO_INCREMENT NOT NULL,
     `order_id` INT NOT NULL,
     `product_id` INT NOT NULL,
     `quantity` INT NOT NULL,
     `amount` DECIMAL(6,2) NOT NULL,
     `created_at` datetime DEFAULT CURDATE(),
     PRIMARY KEY(id),
     CONSTRAINT FK_3 FOREIGN KEY (order_id) REFERENCES `Order` (id),
     CONSTRAINT FK_4 FOREIGN KEY (product_id) REFERENCES `Product` (id)
) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB;

-- Customer data
INSERT INTO test_shop.`Customer` (`id`, `firstname`, `lastname`, `email`, `phone`) VALUE (1,'Doe', 'John', 'john.doe@mail.fr', '0240404040');
INSERT INTO test_shop.`Customer` (`id`, `firstname`, `lastname`, `email`, `phone`) VALUE (2, 'Curie', 'Mary', 'mary.curie@mail.fr', '0250404040');
INSERT INTO test_shop.`Customer` (`id`, `firstname`, `lastname`, `email`, `phone`) VALUE (3, 'Zinedine', 'Zidane', 'zizou@mail.fr', '0260404040');
INSERT INTO test_shop.`Customer` (`id`, `firstname`, `lastname`, `email`, `phone`) VALUE (4, 'Brigitte', 'Bardot', 'brigitte.bardot@mail.fr', '0760404040');

-- Product data
INSERT INTO test_shop.`Product` (`id`, `title`, `description`, `category`, `price`, `imageUrl`)
    VALUE (1, 'Bouteille chouffe', 'Une bonne bière blonde belge 33cl', 'Bière', 6.99, 'http://fake/image/chouffe.png');

INSERT INTO test_shop.`Product` (`id`, `title`, `description`, `category`, `price`, `imageUrl`)
    VALUE (2, 'Casquette', 'Casquette confortable vous protégeant du soleil', 'Accessoire', 35.99, 'http://fake/image/casquette.png');


-- Order data
INSERT INTO test_shop.`Order` (`id`, `customer_id`, `amount`) VALUE (1, 1, 35.99);
INSERT INTO test_shop.`Order` (`id`, `customer_id`, `amount`) VALUE (2, 2, 69.99);
INSERT INTO test_shop.`Order` (`id`, `customer_id`, `amount`) VALUE (3, 3, 35.99);
INSERT INTO test_shop.`Order` (`id`, `customer_id`, `amount`) VALUE (4, 4, 45.98);

-- Order Item data
INSERT INTO test_shop.`OrderItem` (`order_id`, `product_id`, `quantity`, `amount`) VALUE (1, 2, 1, 35.99);
INSERT INTO test_shop.`OrderItem` (`order_id`, `product_id`, `quantity`, `amount`) VALUE (2, 1, 10, 69.99);
INSERT INTO test_shop.`OrderItem` (`order_id`, `product_id`, `quantity`, `amount`) VALUE (3, 2, 1, 35.99);
INSERT INTO test_shop.`OrderItem` (`order_id`, `product_id`, `quantity`, `amount`) VALUE (4, 2, 1, 35.99);
INSERT INTO test_shop.`OrderItem` (`order_id`, `product_id`, `quantity`, `amount`) VALUE (4, 1, 1, 6.99);


-- 1. Récupérer le prénom et le nom de famille de tous les clients qui ont commandé le produit avec l’ID 1;
-- -- Should return Curie Marie and Brigitte Bardot
SELECT DISTINCT c.firstname, c.lastname
FROM test_shop.Customer c
INNER JOIN test_shop.`Order` o ON o.customer_id  = c.id
INNER JOIN test_shop.OrderItem oi ON oi.order_id = o.id
WHERE oi.product_id = 1;

-- 2. Récupérer tous les noms et quantités des produits vendus sur les 7 derniers jours.
-- -- Should return : Chouffe = 11, Casquette = 3
SELECT p.title, SUM(oi.quantity)
FROM test_shop.Product p
INNER JOIN test_shop.OrderItem oi ON oi.product_id = p.id
WHERE oi.created_at >=  DATE(NOW() - INTERVAL 7 DAY)
GROUP BY p.title