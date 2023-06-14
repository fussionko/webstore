INSERT INTO Category (name_category)
	VALUES('aaaaaaaaaaaaaaaaaaaaaaaaaaaa');

INSERT INTO subCategory (name_subCategory ,category_name_category)
	VALUES('zelodolgitekstkerpacje', 'RAM');
	
SET FOREIGN_KEY_CHECKS = 0;
INSERT INTO subCategory (name_subCategory, subCategory_name_subCategory)
	VALUES('pod_pod_Kategorija', 'TV');
SET FOREIGN_KEY_CHECKS = 1;

INSERT INTO product (itemName , subCategory_name_subCategory, subCategory_category_name_category)
	VALUES('dela', 'Lektro','RAM');
	
	SET character_set_server = 'utf8';
	SET collation_server = 'utf8_slovenian_ci';
	ALTER DATABASE Webstore CHARACTER SET = 'utf8' collate = 'utf8_slovenian_ci';
	
SELECT p.itemName FROM category c INNER JOIN product p ON p.subCategory_category_name_category=c.name_category WHERE p.deleted = 0 AND p.active = 1 AND c.deleted = 0 AND c.active = 1 AND p.subCategory_category_name_category='RAM';