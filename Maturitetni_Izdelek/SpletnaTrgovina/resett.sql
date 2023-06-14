
DELETE FROM category;
DELETE FROM product;
ALTER TABLE product auto_increment = 1;
DELETE FROM component;
ALTER TABLE component auto_increment = 1;
DELETE FROM attribute;
DELETE FROM component_attribute;