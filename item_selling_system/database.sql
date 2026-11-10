-- =====================================================================
-- ITEM SELLING MANAGEMENT SYSTEM - DATABASE
-- Import this file in phpMyAdmin (Import tab).
--
-- HOW THE TABLES ARE CONNECTED:
--   categories (1) ---< (many) items      via items.category_id
--   customers  (1) ---< (many) sales      via sales.customer_id
--   items      (1) ---< (many) sales      via sales.item_id
-- "Parent" tables (categories, customers) are created FIRST, then the
-- tables that point to them. Order matters because of foreign keys.
-- =====================================================================

CREATE DATABASE IF NOT EXISTS item_selling_db;
USE item_selling_db;

-- ---------------------------------------------------------------------
-- TABLE 1: categories  (groups items together, e.g. "Drinks", "Snacks")
-- ---------------------------------------------------------------------
CREATE TABLE categories (
    category_id   INT AUTO_INCREMENT PRIMARY KEY,  -- PRIMARY KEY: unique ID of each category
    category_name VARCHAR(100) NOT NULL,           -- name of the category
    description   TEXT                             -- optional notes about the category
);

-- ---------------------------------------------------------------------
-- TABLE 2: items  (the products being sold)
-- ---------------------------------------------------------------------
CREATE TABLE items (
    item_id     INT AUTO_INCREMENT PRIMARY KEY,    -- PRIMARY KEY: unique ID of each item
    category_id INT NOT NULL,                      -- FOREIGN KEY -> categories.category_id
    item_name   VARCHAR(100) NOT NULL,             -- name of the item
    description TEXT,                              -- optional notes about the item
    price       DECIMAL(10,2) NOT NULL,            -- price of ONE piece
    quantity    INT NOT NULL DEFAULT 0,            -- stock available (goes down when sold)

    -- Link to categories. RESTRICT = you cannot delete a category
    -- that still has items.
    FOREIGN KEY (category_id) REFERENCES categories(category_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ---------------------------------------------------------------------
-- TABLE 3: customers  (people who buy items)
-- ---------------------------------------------------------------------
CREATE TABLE customers (
    customer_id    INT AUTO_INCREMENT PRIMARY KEY, -- PRIMARY KEY: unique ID of each customer
    customer_name  VARCHAR(100) NOT NULL,          -- full name
    contact_number VARCHAR(30),                    -- phone number
    email          VARCHAR(100),                   -- email address
    address        TEXT                            -- home address
);

-- ---------------------------------------------------------------------
-- TABLE 4: sales  (one row = one customer buying one item)
-- ---------------------------------------------------------------------
CREATE TABLE sales (
    sale_id       INT AUTO_INCREMENT PRIMARY KEY,  -- PRIMARY KEY: unique ID of each sale
    customer_id   INT NOT NULL,                    -- FOREIGN KEY -> customers.customer_id (who bought)
    item_id       INT NOT NULL,                    -- FOREIGN KEY -> items.item_id (what was bought)
    quantity_sold INT NOT NULL,                    -- how many pieces were sold
    total_amount  DECIMAL(10,2) NOT NULL,          -- item price x quantity_sold (calculated by PHP)
    sale_date     DATETIME DEFAULT CURRENT_TIMESTAMP, -- date/time of the sale (set automatically)

    FOREIGN KEY (customer_id) REFERENCES customers(customer_id)
        ON DELETE RESTRICT ON UPDATE CASCADE,
    FOREIGN KEY (item_id) REFERENCES items(item_id)
        ON DELETE RESTRICT ON UPDATE CASCADE
);

-- ---------------------------------------------------------------------
-- OPTIONAL SAMPLE DATA (delete this part if you want empty tables)
-- ---------------------------------------------------------------------
INSERT INTO categories (category_name, description) VALUES
('Drinks', 'Bottled and canned drinks'),
('Snacks', 'Chips, biscuits and candies');

INSERT INTO items (category_id, item_name, description, price, quantity) VALUES
(1, 'Bottled Water', '500ml', 15.00, 100),
(2, 'Potato Chips', 'Large pack', 45.50, 50);

INSERT INTO customers (customer_name, contact_number, email, address) VALUES
('Juan Dela Cruz', '09171234567', 'juan@example.com', 'Lapu-Lapu City');


-- =====================================================================
-- HOW TO ADD A 5TH TABLE LATER  (example: "suppliers")
-- =====================================================================
-- STEP 1: Create the new table. Give it its own PRIMARY KEY
--         (the convention here is tablename_id).
--
--   CREATE TABLE suppliers (
--       supplier_id    INT AUTO_INCREMENT PRIMARY KEY,  -- PRIMARY KEY
--       supplier_name  VARCHAR(100) NOT NULL,
--       contact_number VARCHAR(30)
--   );
--
-- STEP 2: Connect it to an existing table. Here each ITEM comes from
--         one SUPPLIER, so add a column to items and make it a FOREIGN KEY:
--
--   ALTER TABLE items ADD COLUMN supplier_id INT NULL;
--   ALTER TABLE items ADD FOREIGN KEY (supplier_id)
--       REFERENCES suppliers(supplier_id) ON DELETE RESTRICT;
--
--   (Rule: the "many" side holds the foreign key. Many items -> one supplier,
--    so the foreign key goes in items.)
--
-- STEP 3: Copy the PHP files (e.g. categories.php, add_category.php,
--         edit_category.php, delete_category.php), rename them to
--         suppliers.php, add_supplier.php, etc., and change the table and
--         column names. Add a link to the menu in db.php (page_header).
-- =====================================================================
