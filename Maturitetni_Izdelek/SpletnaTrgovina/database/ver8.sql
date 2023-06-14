-- MySQL Script generated by MySQL Workbench
-- Tue Feb  1 20:33:38 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema webstore_ver8_0
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema webstore_ver8_0
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `webstore_ver8_0` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci;
USE `webstore_ver8_0`;

-- -----------------------------------------------------
-- Table `category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `name_category` VARCHAR(70) NOT NULL,
  `description` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `name_parent_category` VARCHAR(70) NULL,
  PRIMARY KEY (`name_category`),
  INDEX `fk_category_category1_idx` (`name_parent_category` ASC),
  CONSTRAINT `fk_category_category1`
    FOREIGN KEY (`name_parent_category`)
    REFERENCES `category` (`name_category`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product` (
  `idItem` INT NOT NULL AUTO_INCREMENT,
  `itemName` VARCHAR(100) NOT NULL,
  `description` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `name_category` VARCHAR(70) NOT NULL,
  PRIMARY KEY (`idItem`),
  INDEX `fk_product_category1_idx` (`name_category` ASC),
  CONSTRAINT `fk_product_category1`
    FOREIGN KEY (`name_category`)
    REFERENCES `category` (`name_category`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `attribute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `attribute` (
  `name_attribute` VARCHAR(70) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` VARCHAR(80) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`name_attribute`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `discount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `discount` (
  `iddiscount` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `amount` DECIMAL(4,1) NOT NULL,
  `description` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`iddiscount`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `price`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `price` (
  `idprice` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `price` DECIMAL(9,2) UNSIGNED NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_stop_time` TIMESTAMP NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`idprice`, `active_start_time`, `product_idItem`),
  INDEX `fk_price_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_price_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `storage_location`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `storage_location` (
  `name_storage_location` VARCHAR(80) NOT NULL,
  `address` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` VARCHAR(150) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`name_storage_location`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user` (
  `username` VARCHAR(45) NOT NULL,
  `name` VARCHAR(25) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `passwordHash` VARCHAR(255) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `email` VARCHAR(100) NOT NULL,
  `gender` ENUM('man', 'woman', 'unknown') NOT NULL,
  PRIMARY KEY (`username`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_address` (
  `iduser_address` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `postal_code` INT NOT NULL,
  `address` VARCHAR(60) NOT NULL,
  `telephone_number` VARCHAR(15) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`iduser_address`),
  INDEX `fk_user_address_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_user_address_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `user_payment_card`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `user_payment_card` (
  `card_number` VARCHAR(16) NOT NULL,
  `cv` VARCHAR(3) NOT NULL,
  `card_expires` DATETIME NOT NULL,
  `name` VARCHAR(25) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` VARCHAR(45) NULL,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`card_number`),
  INDEX `fk_user_payment_card_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_user_payment_card_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `shipping_company`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `shipping_company` (
  `name` VARCHAR(80) NOT NULL,
  `price` TINYINT(2) UNSIGNED NOT NULL,
  `time_to_deliver` VARCHAR(10) NOT NULL,
  `description` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`name`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `transaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaction` (
  `idtransaction` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `type` ENUM('kartica', 'po_povzetju', 'predračun') NOT NULL,
  `processed` TINYINT(1) NOT NULL DEFAULT 0,
  `failed` TINYINT(1) NOT NULL DEFAULT 0,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_payment_card_card_number` VARCHAR(16) NOT NULL,
  PRIMARY KEY (`idtransaction`),
  INDEX `fk_transaction_user_payment_card1_idx` (`user_payment_card_card_number` ASC),
  CONSTRAINT `fk_transaction_user_payment_card1`
    FOREIGN KEY (`user_payment_card_card_number`)
    REFERENCES `user_payment_card` (`card_number`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order` (
  `idorder` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `price` INT NOT NULL,
  `delivered` TINYINT(1) NOT NULL DEFAULT 0,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipping_company_name` VARCHAR(80) NOT NULL,
  `user_address_iduser_address` INT UNSIGNED NOT NULL,
  `transaction_idtransaction` BIGINT UNSIGNED NOT NULL,
  `user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idorder`, `user_username`),
  INDEX `fk_order_shipping_company1_idx` (`shipping_company_name` ASC),
  INDEX `fk_order_user_address1_idx` (`user_address_iduser_address` ASC),
  INDEX `fk_order_transaction1_idx` (`transaction_idtransaction` ASC),
  INDEX `fk_order_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_order_shipping_company1`
    FOREIGN KEY (`shipping_company_name`)
    REFERENCES `shipping_company` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_order_user_address1`
    FOREIGN KEY (`user_address_iduser_address`)
    REFERENCES `user_address` (`iduser_address`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_order_transaction1`
    FOREIGN KEY (`transaction_idtransaction`)
    REFERENCES `transaction` (`idtransaction`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_order_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `order_delivery_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order_delivery_status` (
  `idorder_delivery_status` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`idorder_delivery_status`),
  INDEX `fk_order_delivery_status_order1_idx` (`order_idorder` ASC),
  CONSTRAINT `fk_order_delivery_status_order1`
    FOREIGN KEY (`order_idorder`)
    REFERENCES `order` (`idorder`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `image` (
  `image_location` VARCHAR(150) NOT NULL,
  `description_alt` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_stop_time` TIMESTAMP NULL,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`image_location`, `active_start_time`, `product_idItem`),
  INDEX `fk_image_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_image_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_attribute_value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_attribute_value` (
  `value` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `name_attribute` VARCHAR(70) NOT NULL,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`name_attribute`, `product_idItem`, `value`),
  INDEX `fk_product_attribute_value_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_product_attribute_value_attribute1`
    FOREIGN KEY (`name_attribute`)
    REFERENCES `attribute` (`name_attribute`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_attribute_value_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_check_storage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_check_storage` (
  `type` SET('na zalogi', 'ni v zalogi', 'naročljivo', 'nenaročljivo') NOT NULL,
  `quantity` INT ZEROFILL NOT NULL,
  `storage_location_name_storage_location` VARCHAR(80) NOT NULL,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`storage_location_name_storage_location`, `product_idItem`),
  INDEX `fk_product_check_storage_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_product_check_storage_storage_location1`
    FOREIGN KEY (`storage_location_name_storage_location`)
    REFERENCES `storage_location` (`name_storage_location`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_check_storage_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart` (
  `idcart` INT NOT NULL AUTO_INCREMENT,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NULL,
  `active_stop_time` TIMESTAMP NULL,
  PRIMARY KEY (`idcart`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `session`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `session` (
  `idsession` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `session_start_time` TIMESTAMP NULL,
  `session_stop_time` TIMESTAMP NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `cart_idcart` INT NOT NULL,
  `user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idsession`, `user_username`),
  INDEX `fk_session_cart1_idx` (`cart_idcart` ASC),
  INDEX `fk_session_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_session_cart1`
    FOREIGN KEY (`cart_idcart`)
    REFERENCES `cart` (`idcart`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_session_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `link_visited`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `link_visited` (
  `name_link` VARCHAR(200) NOT NULL,
  `session_start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `session_stop_time` TIMESTAMP NULL,
  `time_spent` INT NULL DEFAULT TIMESTAMPDIFF(SECOND, session_start_time, session_stop_time),
  `session_idsession` BIGINT UNSIGNED NOT NULL,
  `session_user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`name_link`, `session_start_time`, `session_idsession`, `session_user_username`),
  INDEX `fk_link_visited_session1_idx` (`session_idsession` ASC, `session_user_username` ASC),
  CONSTRAINT `fk_link_visited_session1`
    FOREIGN KEY (`session_idsession` , `session_user_username`)
    REFERENCES `session` (`idsession` , `user_username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_discount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_discount` (
  `active_start_time` TIMESTAMP NOT NULL,
  `description` VARCHAR(150) NULL,
  `active_stop_time` TIMESTAMP NULL,
  `active` TINYINT(1) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `discount_iddiscount` SMALLINT UNSIGNED NOT NULL,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`active_start_time`, `discount_iddiscount`, `product_idItem`),
  INDEX `fk_product_discount_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_product_discount_discount1`
    FOREIGN KEY (`discount_iddiscount`)
    REFERENCES `discount` (`iddiscount`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_discount_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_to_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_to_order` (
  `quantity` TINYINT(3) UNSIGNED NOT NULL,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`order_idorder`, `product_idItem`),
  INDEX `fk_product_to_order_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_product_to_order_order1`
    FOREIGN KEY (`order_idorder`)
    REFERENCES `order` (`idorder`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_to_order_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `product_to_cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `product_to_cart` (
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `quantity` TINYINT(3) UNSIGNED NOT NULL,
  `product_idItem` INT NOT NULL,
  `cart_idcart` INT NOT NULL,
  PRIMARY KEY (`product_idItem`, `cart_idcart`),
  INDEX `fk_product_to_cart_product1_idx` (`product_idItem` ASC),
  INDEX `fk_product_to_cart_cart1_idx` (`cart_idcart` ASC),
  CONSTRAINT `fk_product_to_cart_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_product_to_cart_cart1`
    FOREIGN KEY (`cart_idcart`)
    REFERENCES `cart` (`idcart`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `component`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `component` (
  `idcomponent` INT NOT NULL AUTO_INCREMENT,
  `id_group` VARCHAR(10) NOT NULL,
  `itemName` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_location` VARCHAR(150) NULL,
  `description` VARCHAR(80) NULL,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idcomponent`, `id_group`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `component_attribute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `component_attribute` (
  `idcomponent_attribute` VARCHAR(70) NOT NULL,
  `description` VARCHAR(80) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idcomponent_attribute`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `component_has_component_attribute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `component_has_component_attribute` (
  `value` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `idcomponent_attribute` VARCHAR(70) NOT NULL,
  `component_idcomponent` INT NOT NULL,
  PRIMARY KEY (`value`, `idcomponent_attribute`, `component_idcomponent`),
  INDEX `fk_component_has_component_attributes_component_attributes1_idx` (`idcomponent_attribute` ASC),
  INDEX `fk_component_has_component_attributes_component1_idx` (`component_idcomponent` ASC),
  CONSTRAINT `fk_component_has_component_attributes_component_attributes1`
    FOREIGN KEY (`idcomponent_attribute`)
    REFERENCES `component_attribute` (`idcomponent_attribute`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_component_has_component_attributes_component1`
    FOREIGN KEY (`component_idcomponent`)
    REFERENCES `component` (`idcomponent`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `build`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `build` (
  `idbuild` INT NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` VARCHAR(255) NULL,
  `user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`idbuild`, `user_username`),
  INDEX `fk_build_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_build_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `component_in_build`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `component_in_build` (
  `value` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `build_idbuild` INT NOT NULL,
  `component_idcomponent` INT NOT NULL,
  PRIMARY KEY (`build_idbuild`, `component_idcomponent`),
  INDEX `fk_build_has_component_component1_idx` (`component_idcomponent` ASC),
  INDEX `fk_build_has_component_build1_idx` (`build_idbuild` ASC),
  CONSTRAINT `fk_build_has_component_build1`
    FOREIGN KEY (`build_idbuild`)
    REFERENCES `build` (`idbuild`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_build_has_component_component1`
    FOREIGN KEY (`component_idcomponent`)
    REFERENCES `component` (`idcomponent`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `password_reset`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset` (
  `reset_request` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `reset_completed` TINYINT(1) NULL DEFAULT 0,
  `user_username` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`reset_request`, `user_username`),
  INDEX `fk_password_reset_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_password_reset_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;