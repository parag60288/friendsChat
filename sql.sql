SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS `Users`;
DROP TABLE IF EXISTS `Chat`;
DROP TABLE IF EXISTS `Registercodes`;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE `Users` (
    `userID` SMALLINT NOT NULL,
    `username` VARCHAR(128) NOT NULL,
    `password` CHAR(60) NOT NULL,
    `email` VARCHAR(255),
    `isAdmin` BOOLEAN NOT NULL,
    `enabled` BOOLEAN NOT NULL,
    PRIMARY KEY (`userID`)
);

CREATE TABLE `Chat` (
    `messageNR` BIGINT NOT NULL,
    `userID` SMALLINT NOT NULL,
    `dateTime` DATETIME NOT NULL,
    `message` VARCHAR(2048) NOT NULL,
    PRIMARY KEY (`messageNR`, `userID`),
    UNIQUE (`messageNR`)
);

CREATE TABLE `Registercodes` (
    `code` CHAR(128) NOT NULL,
    `active` BOOLEAN NOT NULL,
    `validUntill` DATETIME NOT NULL,
    PRIMARY KEY (`code`)
);

ALTER TABLE `Chat` ADD FOREIGN KEY (`userID`) REFERENCES `Users`(`userID`);