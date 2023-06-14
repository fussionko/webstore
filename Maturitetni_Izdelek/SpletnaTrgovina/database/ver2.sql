-- MySQL Script generated by MySQL Workbench
-- Mon Oct 25 19:01:27 2021
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema WebStore
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema WebStore
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `WebStore` DEFAULT CHARACTER SET utf8 COLLATE utf8_slovenian_ci ;
USE `WebStore` ;

-- -----------------------------------------------------
-- Table `WebStore`.`category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`category` (
  `name_category` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`name_category`),
  UNIQUE INDEX `name_UNIQUE` (`name_category` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`subCategory`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`subCategory` (
  `name_subCategory` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `category_name_category` VARCHAR(60) NOT NULL,
  `subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`name_subCategory`, `category_name_category`),
  UNIQUE INDEX `id_product_subCategory_name_UNIQUE` (`name_subCategory` ASC) VISIBLE,
  INDEX `fk_subCategory_category1_idx` (`category_name_category` ASC) VISIBLE,
  INDEX `fk_subCategory_subCategory1_idx` (`subCategory_name_subCategory` ASC, `subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_subCategory_category1`
    FOREIGN KEY (`category_name_category`)
    REFERENCES `WebStore`.`category` (`name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_subCategory_subCategory1`
    FOREIGN KEY (`subCategory_name_subCategory` , `subCategory_category_name_category`)
    REFERENCES `WebStore`.`subCategory` (`name_subCategory` , `category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`product`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`product` (
  `idItem` BIGINT NOT NULL,
  `itemName` VARCHAR(45) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `description` VARCHAR(80) NULL,
  `subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`idItem`, `subCategory_name_subCategory`, `subCategory_category_name_category`),
  INDEX `fk_product_subCategory1_idx` (`subCategory_name_subCategory` ASC, `subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_product_subCategory1`
    FOREIGN KEY (`subCategory_name_subCategory` , `subCategory_category_name_category`)
    REFERENCES `WebStore`.`subCategory` (`name_subCategory` , `category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`attribute`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`attribute` (
  `name_attribute` VARCHAR(70) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON DELETE CURRENT_TIMESTAMP,
  `description` VARCHAR(80) NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`name_attribute`),
  UNIQUE INDEX `name_UNIQUE` (`name_attribute` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`discount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`discount` (
  `iddiscount` SMALLINT UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `amount` DECIMAL(4,1) NOT NULL,
  `description` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON DELETE CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`iddiscount`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`price`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`price` (
  `idprice` INT UNSIGNED NOT NULL,
  `price` DECIMAL(9,2) UNSIGNED NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NOT NULL,
  `active_stop_time` TIMESTAMP NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`idprice`, `product_idItem`, `product_subCategory_name_subCategory`, `product_subCategory_category_name_category`),
  INDEX `fk_price_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_price_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`storage_location`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`storage_location` (
  `idstorage_location` TINYINT UNSIGNED NOT NULL,
  `address` VARCHAR(70) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `description` VARCHAR(150) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idstorage_location`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`user` (
  `iduser` INT UNSIGNED NOT NULL,
  `name` VARCHAR(25) NOT NULL,
  `lastName` VARCHAR(45) NOT NULL,
  `username` VARCHAR(45) NOT NULL,
  `passwordHash` VARCHAR(80) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  `email` VARCHAR(45) NOT NULL,
  `gender` ENUM('moški', 'ženska', 'neznano') NOT NULL,
  PRIMARY KEY (`iduser`),
  UNIQUE INDEX `email_UNIQUE` (`email` ASC) VISIBLE,
  UNIQUE INDEX `username_UNIQUE` (`username` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`user_address`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`user_address` (
  `iduser_address` INT UNSIGNED NOT NULL,
  `country` VARCHAR(45) NOT NULL,
  `city` VARCHAR(45) NOT NULL,
  `postal_code` INT NOT NULL,
  `address` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  `telephone_number` VARCHAR(15) NOT NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `user_iduser` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`iduser_address`),
  INDEX `fk_user_address_user1_idx` (`user_iduser` ASC) VISIBLE,
  CONSTRAINT `fk_user_address_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `WebStore`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`user_payment_card`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`user_payment_card` (
  `iduser_payment` INT UNSIGNED NOT NULL,
  `card_number` VARCHAR(16) NOT NULL,
  `cv` VARCHAR(3) NOT NULL,
  `card_expires` DATETIME NOT NULL,
  `name` VARCHAR(25) NOT NULL,
  `last_name` VARCHAR(45) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  `description` VARCHAR(45) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `user_iduser` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`iduser_payment`),
  UNIQUE INDEX `card_number_UNIQUE` (`card_number` ASC) VISIBLE,
  INDEX `fk_user_payment_card_user1_idx` (`user_iduser` ASC) VISIBLE,
  CONSTRAINT `fk_user_payment_card_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `WebStore`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`shipping_company`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`shipping_company` (
  `idshipping_company` TINYINT UNSIGNED NOT NULL,
  `name` VARCHAR(45) NOT NULL,
  `price` TINYINT(2) UNSIGNED NOT NULL,
  `time_to_deliver` VARCHAR(10) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `description` VARCHAR(150) NULL,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`idshipping_company`),
  UNIQUE INDEX `name_UNIQUE` (`name` ASC) VISIBLE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`order` (
  `idorder` BIGINT UNSIGNED NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON DELETE CURRENT_TIMESTAMP,
  `price` INT NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delivered` TINYINT(1) NOT NULL DEFAULT 0,
  `shipping_company_idshipping_company` TINYINT UNSIGNED NOT NULL,
  `user_iduser` INT UNSIGNED NOT NULL,
  `user_address_iduser_address` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`idorder`),
  INDEX `fk_order_shipping_company1_idx` (`shipping_company_idshipping_company` ASC) VISIBLE,
  INDEX `fk_order_user1_idx` (`user_iduser` ASC) VISIBLE,
  INDEX `fk_order_user_address1_idx` (`user_address_iduser_address` ASC) VISIBLE,
  CONSTRAINT `fk_order_shipping_company1`
    FOREIGN KEY (`shipping_company_idshipping_company`)
    REFERENCES `WebStore`.`shipping_company` (`idshipping_company`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `WebStore`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_order_user_address1`
    FOREIGN KEY (`user_address_iduser_address`)
    REFERENCES `WebStore`.`user_address` (`iduser_address`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`order_delivery_status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`order_delivery_status` (
  `idorder_delivery_status` BIGINT UNSIGNED NOT NULL,
  `date` DATETIME NOT NULL,
  `location` VARCHAR(150) NOT NULL,
  `order_user_payment_card_iduser_payment` INT UNSIGNED NOT NULL,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`idorder_delivery_status`, `order_idorder`),
  INDEX `fk_order_delivery_status_order1_idx` (`order_idorder` ASC) VISIBLE,
  CONSTRAINT `fk_order_delivery_status_order1`
    FOREIGN KEY (`order_idorder`)
    REFERENCES `WebStore`.`order` (`idorder`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`image`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`image` (
  `idimage` BIGINT UNSIGNED NOT NULL,
  `image_location` VARCHAR(150) NOT NULL,
  `description_alt` VARCHAR(150) NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `active_start_time` TIMESTAMP NOT NULL,
  `active_stop_time` TIMESTAMP NULL,
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`idimage`, `product_idItem`, `product_subCategory_name_subCategory`, `product_subCategory_category_name_category`),
  UNIQUE INDEX `image_location_UNIQUE` (`image_location` ASC) VISIBLE,
  INDEX `fk_image_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_image_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`product_attribute_value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`product_attribute_value` (
  `value` VARCHAR(60) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `active` TINYINT(1) NOT NULL DEFAULT 1,
  `attribute_name_attribute` VARCHAR(70) NOT NULL,
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`attribute_name_attribute`, `product_idItem`, `product_subCategory_name_subCategory`, `product_subCategory_category_name_category`),
  INDEX `fk_product_attribute_value_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_product_attribute_value_attribute1`
    FOREIGN KEY (`attribute_name_attribute`)
    REFERENCES `WebStore`.`attribute` (`name_attribute`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_attribute_value_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`product_check_storage`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`product_check_storage` (
  `type` SET('na zalogi', 'ni v zalogi', 'naročljivo', 'nenaročljivo') NOT NULL,
  `quantity` INT ZEROFILL NOT NULL,
  `storage_location_idstorage_location` TINYINT UNSIGNED NOT NULL,
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`storage_location_idstorage_location`, `product_idItem`, `product_subCategory_name_subCategory`, `product_subCategory_category_name_category`),
  INDEX `fk_product_check_storage_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_product_check_storage_storage_location1`
    FOREIGN KEY (`storage_location_idstorage_location`)
    REFERENCES `WebStore`.`storage_location` (`idstorage_location`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_check_storage_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
ROW_FORMAT = DEFAULT;


-- -----------------------------------------------------
-- Table `WebStore`.`cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`cart` (
  `idcart` INT NOT NULL,
  PRIMARY KEY (`idcart`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`transaction`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`transaction` (
  `idtransaction` BIGINT UNSIGNED NOT NULL,
  `type` ENUM('kartica', 'po_povzetju', 'predračun') NOT NULL,
  `processed` TINYINT(1) NOT NULL DEFAULT 0,
  `failed` TINYINT(1) NOT NULL DEFAULT 0,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  `user_payment_card_iduser_payment` INT UNSIGNED NOT NULL,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  PRIMARY KEY (`idtransaction`, `user_payment_card_iduser_payment`, `order_idorder`),
  INDEX `fk_transaction_user_payment_card1_idx` (`user_payment_card_iduser_payment` ASC) VISIBLE,
  INDEX `fk_transaction_order1_idx` (`order_idorder` ASC) VISIBLE,
  CONSTRAINT `fk_transaction_user_payment_card1`
    FOREIGN KEY (`user_payment_card_iduser_payment`)
    REFERENCES `WebStore`.`user_payment_card` (`iduser_payment`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_transaction_order1`
    FOREIGN KEY (`order_idorder`)
    REFERENCES `WebStore`.`order` (`idorder`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`session`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`session` (
  `idsession` BIGINT UNSIGNED NOT NULL,
  `session_start_time` TIMESTAMP NOT NULL,
  `session_stop_time` TIMESTAMP NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  `user_iduser` INT UNSIGNED NOT NULL,
  `cart_idcart` INT NOT NULL,
  PRIMARY KEY (`idsession`, `user_iduser`),
  INDEX `fk_session_user1_idx` (`user_iduser` ASC) VISIBLE,
  INDEX `fk_session_cart1_idx` (`cart_idcart` ASC) VISIBLE,
  CONSTRAINT `fk_session_user1`
    FOREIGN KEY (`user_iduser`)
    REFERENCES `WebStore`.`user` (`iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_session_cart1`
    FOREIGN KEY (`cart_idcart`)
    REFERENCES `WebStore`.`cart` (`idcart`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`link_visited`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`link_visited` (
  `name_link` VARCHAR(100) NOT NULL,
  `session_start_time` TIMESTAMP NOT NULL,
  `session_stop_time` TIMESTAMP NULL,
  `session_idsession` BIGINT UNSIGNED NOT NULL,
  `session_user_iduser` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`name_link`),
  UNIQUE INDEX `name_link_UNIQUE` (`name_link` ASC) VISIBLE,
  INDEX `fk_link_visited_session1_idx` (`session_idsession` ASC, `session_user_iduser` ASC) VISIBLE,
  CONSTRAINT `fk_link_visited_session1`
    FOREIGN KEY (`session_idsession` , `session_user_iduser`)
    REFERENCES `WebStore`.`session` (`idsession` , `user_iduser`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`product_discount`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`product_discount` (
  `description` VARCHAR(80) NULL,
  `active_start_time` TIMESTAMP NOT NULL,
  `active_stop_time` TIMESTAMP NULL,
  `active` TINYINT(1) NOT NULL,
  `create_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `modified_time` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `delete_time` TIMESTAMP NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `discount_iddiscount` SMALLINT UNSIGNED NOT NULL,
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  PRIMARY KEY (`discount_iddiscount`),
  INDEX `fk_product_discount_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  CONSTRAINT `fk_product_discount_discount1`
    FOREIGN KEY (`discount_iddiscount`)
    REFERENCES `WebStore`.`discount` (`iddiscount`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_discount_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`product_to_order`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`product_to_order` (
  `quantity` TINYINT(3) UNSIGNED NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  `order_idorder` BIGINT UNSIGNED NOT NULL,
  INDEX `fk_order_has_product1_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  PRIMARY KEY (`order_idorder`),
  CONSTRAINT `fk_order_has_product1_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_product_to_order_order1`
    FOREIGN KEY (`order_idorder`)
    REFERENCES `WebStore`.`order` (`idorder`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `WebStore`.`product_to_cart`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `WebStore`.`product_to_cart` (
  `product_idItem` BIGINT NOT NULL,
  `product_subCategory_name_subCategory` VARCHAR(60) NOT NULL,
  `product_subCategory_category_name_category` VARCHAR(60) NOT NULL,
  `cart_idcart` INT NOT NULL,
  `quantity` TINYINT(3) UNSIGNED NOT NULL,
  `deleted` TINYINT(1) NOT NULL DEFAULT 0,
  `delete_time` TIMESTAMP NULL,
  INDEX `fk_table1_product1_idx` (`product_idItem` ASC, `product_subCategory_name_subCategory` ASC, `product_subCategory_category_name_category` ASC) VISIBLE,
  INDEX `fk_table1_cart1_idx` (`cart_idcart` ASC) VISIBLE,
  CONSTRAINT `fk_table1_product1`
    FOREIGN KEY (`product_idItem` , `product_subCategory_name_subCategory` , `product_subCategory_category_name_category`)
    REFERENCES `WebStore`.`product` (`idItem` , `subCategory_name_subCategory` , `subCategory_category_name_category`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_cart1`
    FOREIGN KEY (`cart_idcart`)
    REFERENCES `WebStore`.`cart` (`idcart`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
