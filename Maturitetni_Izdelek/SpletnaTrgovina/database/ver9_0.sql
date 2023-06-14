-- MySQL Script generated by MySQL Workbench
-- Sun Mar 20 21:28:18 2022
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema webstore_ver9_0
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema webstore_ver9_0
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `webstore_ver9_0` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci ;
USE `webstore_ver9_0` ;

-- -----------------------------------------------------
-- Table `category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `category` (
  `name_category` VARCHAR(70) NOT NULL,
  `description` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `name_parent_category` VARCHAR(70) NOT NULL,
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
  `name_parent_category` VARCHAR(70) NULL DEFAULT NULL,
  PRIMARY KEY (`idItem`),
  INDEX `fk_product_category1_idx` (`name_category` ASC),
  INDEX `index3` (`name_parent_category` ASC, `name_category` ASC),
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
  `price` DECIMAL(9,2) UNSIGNED NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active_stop_time` TIMESTAMP NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `product_idItem` INT NOT NULL,
  PRIMARY KEY (`active_start_time`, `product_idItem`),
  INDEX `fk_price_product1_idx` (`product_idItem` ASC),
  CONSTRAINT `fk_price_product1`
    FOREIGN KEY (`product_idItem`)
    REFERENCES `product` (`idItem`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
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
  `password_reset` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`username`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC),
  UNIQUE INDEX `username_UNIQUE` (`username` ASC))
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
-- Table `address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `address` (
  `id_address` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `country` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `postal_code` INT NOT NULL,
  `address` VARCHAR(60) NOT NULL,
  `telephone_number` VARCHAR(20) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_username` VARCHAR(45) NULL,
  PRIMARY KEY (`id_address`),
  INDEX `fk_addres_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_addres_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `order` (
  `idorder` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `sum_price` DECIMAL(9,2) NOT NULL DEFAULT 0,
  `tracking_number` VARCHAR(40) NOT NULL DEFAULT 'x',
  `delivered` TINYINT(1) NOT NULL DEFAULT 0,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `shipping_company_name` VARCHAR(80) NOT NULL,
  `address_id_address` BIGINT UNSIGNED NOT NULL,
  `user_username` VARCHAR(45) NULL,
  PRIMARY KEY (`idorder`),
  INDEX `fk_order_shipping_company1_idx` (`shipping_company_name` ASC),
  INDEX `fk_order_addres1_idx` (`address_id_address` ASC),
  INDEX `fk_order_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_order_shipping_company1`
    FOREIGN KEY (`shipping_company_name`)
    REFERENCES `shipping_company` (`name`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_order_addres1`
    FOREIGN KEY (`address_id_address`)
    REFERENCES `address` (`id_address`)
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
  `date` DATETIME NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  INDEX `fk_order_delivery_status_order1_idx` (`order_idorder` ASC),
  PRIMARY KEY (`date`),
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
-- Table `cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `cart` (
  `idcart` INT NOT NULL AUTO_INCREMENT,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NULL,
  `active_stop_time` TIMESTAMP NULL,
  `user_username` VARCHAR(45) NULL,
  PRIMARY KEY (`idcart`),
  INDEX `fk_cart_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_cart_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
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
  `id_product_link` INT NOT NULL,
  `price` DECIMAL(9,2) NOT NULL DEFAULT 0,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `image_location` VARCHAR(150) NULL,
  `description` VARCHAR(80) NULL,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`idcomponent`),
  UNIQUE INDEX `id_product_link_UNIQUE` (`id_product_link` ASC),
  INDEX `id_group_index` (`id_group` ASC))
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
  `idbuild` INT NOT NULL AUTO_INCREMENT,
  `sum_price` DECIMAL(9,2) NOT NULL DEFAULT 0,
  `power_usage` INT NOT NULL DEFAULT 0,
  `heat` INT NOT NULL DEFAULT 0,
  `name` VARCHAR(60) NULL,
  `email` VARCHAR(100) NULL,
  `build_like` INT NOT NULL DEFAULT 0,
  `build_dislike` INT NOT NULL,
  `public` TINYINT NOT NULL DEFAULT 1,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `description` VARCHAR(500) NULL,
  `user_username` VARCHAR(45) NULL,
  PRIMARY KEY (`idbuild`),
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
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `payment_card`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `payment_card` (
  `id_payment_card` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `card_number` VARCHAR(16) NOT NULL,
  `cvv` VARCHAR(3) NOT NULL,
  `card_expires` CHAR(5) NOT NULL,
  `cardholder_name` VARCHAR(50) NOT NULL,
  `description` VARCHAR(45) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `user_username` VARCHAR(45) NULL,
  PRIMARY KEY (`id_payment_card`),
  INDEX `fk_payment_card_user1_idx` (`user_username` ASC),
  CONSTRAINT `fk_payment_card_user1`
    FOREIGN KEY (`user_username`)
    REFERENCES `user` (`username`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `transaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `transaction` (
  `idtransaction` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `processed` TINYINT(1) NOT NULL DEFAULT 0,
  `failed` TINYINT(1) NOT NULL DEFAULT 0,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  `payment_card_id_payment_card` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`idtransaction`, `order_idorder`, `payment_card_id_payment_card`),
  INDEX `fk_transaction_order1_idx` (`order_idorder` ASC),
  INDEX `fk_transaction_payment_card1_idx` (`payment_card_id_payment_card` ASC),
  CONSTRAINT `fk_transaction_order1`
    FOREIGN KEY (`order_idorder`)
    REFERENCES `order` (`idorder`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_transaction_payment_card1`
    FOREIGN KEY (`payment_card_id_payment_card`)
    REFERENCES `payment_card` (`id_payment_card`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
