SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

-- Temporarily disable foreign key checks
SET foreign_key_checks = 0;

-- Drop tables if they exist to avoid conflicts
DROP TABLE IF EXISTS expenses;
DROP TABLE IF EXISTS expense_categories;
DROP TABLE IF EXISTS income;
DROP TABLE IF EXISTS income_categories;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS accounts;
DROP TABLE IF EXISTS categories;
DROP TABLE IF EXISTS time_periods;
DROP TABLE IF EXISTS savings;
DROP TABLE IF EXISTS transactions;
DROP TABLE IF EXISTS family_access;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    user_id INT NOT NULL AUTO_INCREMENT,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into users table
INSERT INTO users (first_name, last_name, email, password) VALUES
('John', 'Doe', 'john.doe@example.com', 'password1'),
('Jane', 'Smith', 'jane.smith@example.com', 'password2');

-- Create accounts table
CREATE TABLE IF NOT EXISTS accounts (
    account_id INT NOT NULL AUTO_INCREMENT,
    account_name VARCHAR(100) NOT NULL,
    user_id INT NOT NULL,
    balance DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (account_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into accounts table
INSERT INTO accounts (account_name, user_id, balance) VALUES
('Savings Account', 1, 1500.00),
('Checking Account', 2, 2500.50);

-- Create categories table
CREATE TABLE IF NOT EXISTS categories (
    category_id INT NOT NULL AUTO_INCREMENT,
    category_name VARCHAR(255) NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (category_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into categories table
INSERT INTO categories (category_name, user_id) VALUES
('Salary', 1),
('Entertainment', 2);

-- Create time_periods table
CREATE TABLE IF NOT EXISTS time_periods (
    period_id INT NOT NULL AUTO_INCREMENT,
    period_type ENUM('Weekly', 'Monthly', 'Yearly') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    PRIMARY KEY (period_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into time_periods table
INSERT INTO time_periods (period_type, start_date, end_date) VALUES
('Monthly', '2023-01-01', '2023-01-31'),
('Monthly', '2023-02-01', '2023-02-28');

-- Create savings table
CREATE TABLE IF NOT EXISTS savings (
    savings_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    period_name VARCHAR(50) NOT NULL,
    date DATE NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    PRIMARY KEY (savings_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into savings table
INSERT INTO savings (user_id, period_name, date, amount) VALUES
(1, 'January Savings', '2023-01-15', 200.00),
(2, 'February Savings', '2023-01-20', 150.00);

-- Create transactions table
CREATE TABLE IF NOT EXISTS transactions (
    transaction_id INT NOT NULL AUTO_INCREMENT,
    period_id INT NOT NULL,
    user_id INT NOT NULL,
    account_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    tdate DATE NOT NULL,
    description VARCHAR(255) NOT NULL,
    PRIMARY KEY (transaction_id),
    FOREIGN KEY (period_id) REFERENCES time_periods(period_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (account_id) REFERENCES accounts(account_id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into transactions table
INSERT INTO transactions (period_id, user_id, account_id, category_id, amount, tdate, description) VALUES
(1, 1, 1, 1, 5000.00, '2023-01-01', 'Salary payment'),
(2, 2, 2, 2, -200.00, '2023-01-15', 'Movie tickets');

-- Create family_access table
CREATE TABLE IF NOT EXISTS family_access (
    access_id INT NOT NULL AUTO_INCREMENT,
    user_id INT NOT NULL,
    access_level ENUM('Read', 'Write') NOT NULL,
    family_id INT NOT NULL,
    PRIMARY KEY (access_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (family_id) REFERENCES users(user_id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Inserting data into family_access table
INSERT INTO family_access (user_id, access_level, family_id) VALUES
(1, 'Read', 2),
(2, 'Write', 1);

-- Commit the transaction
COMMIT;

-- Reset character set settings
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

-- Re-enable foreign key checks after the operation is done
SET foreign_key_checks = 1;