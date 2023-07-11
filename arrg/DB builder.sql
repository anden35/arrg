CREATE TABLE `arrg_PULL_LIST`(
	`site` VARCHAR(30) NOT NULL,
	`tag` VARCHAR(15) NOT NULL PRIMARY KEY,
	`building` VARCHAR(15),
	`room` VARCHAR(30) NOT NULL,
	`model` VARCHAR(30) NOT NULL,
	`status` VARCHAR(30),
	`roll_off` VARCHAR(30),
	`timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE `arrg_MOVES`(
	`id` INT(10) AUTO_INCREMENT NOT NULL  PRIMARY KEY,
	`site` VARCHAR(30)NOT NULL,
	`tag` VARCHAR(15) NOT NULL,
	`old_room` VARCHAR(30),
	`building` VARCHAR(15),
	`new_room` VARCHAR(30),
	`device_type` VARCHAR(2) NOT NULL,
	`status` VARCHAR(30)
)DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE `arrg_PLACEMENT`(
	`id` INT(10) AUTO_INCREMENT NOT NULL  PRIMARY KEY,
	`site` VARCHAR(30) NOT NULL,
	`building` VARCHAR(15),
	`room` VARCHAR(30) NOT NULL,
	`device_type` VARCHAR(2) NOT NULL,
	`tag` VARCHAR(15)
)DEFAULT COLLATE utf8_unicode_ci;

CREATE TABLE `arrg_STAGING`(
	`id` INT(2) AUTO_INCREMENT NOT NULL  PRIMARY KEY,
	`site` VARCHAR(30),
	`cb_incoming` INT(3),
	`lt_incoming` INT(3),
	`dt_incoming` INT(3),
	`cb_labels` INT(3),
	`cb_delivered` INT(3),
	`lt_delivered` INT(3),
	`dt_delivered` INT(3),
	`cb_unboxed` INT(3),
	`lt_unboxed` INT(3),
	`dt_unboxed` INT(3),
	`cb_setup` INT(3),
	`lt_setup` INT(3),
	`dt_setup` INT(3)
)DEFAULT COLLATE utf8_unicode_ci;
