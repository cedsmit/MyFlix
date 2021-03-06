-- ---------------------------
-- table Account
-- ---------------------------
CREATE TABLE IF NOT EXISTS `account`
(
    `id`           INT         NOT NULL AUTO_INCREMENT,
    `accountLevel` INT         NOT NULL DEFAULT 0,
    `firstName`    varchar(20) NULL,
    `lastName`     varchar(20) NULL,
    `username`     VARCHAR(20) NOT NULL UNIQUE,
    `password`     VARCHAR(64) NOT NULL,
    `email`        VARCHAR(64) NOT NULL UNIQUE,
    `companyId`    INT         NULL,
    `verified`     TINYINT(1)  NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = INNODB;

-- ---------------------------
-- table accountType
-- ---------------------------
CREATE TABLE IF NOT EXISTS `company`
(
    `id`         INT          NOT NULL AUTO_INCREMENT,
    `studioName` VARCHAR(50)  NOT NULL UNIQUE,
    `genre`      INT          NOT NULL,
    `iban`       VARCHAR(35)  NOT NULL UNIQUE,
    `address`    VARCHAR(100) NOT NULL,
    `city`       VARCHAR(35)  NOT NULL,
    `approved`   TINYINT(1)   NOT NULL DEFAULT 0,
    primary key (`id`)

) ENGINE = INNODB;

-- ---------------------------
-- table accountType
-- ---------------------------
CREATE TABLE IF NOT EXISTS `accountType`
(
    `name`  VARCHAR(30) NOT NULL,
    `level` INT         NOT NULL,
    PRIMARY KEY (`level`),
    UNIQUE (`level`, `name`)
) ENGINE = INNODB;

-- ---------------------------
-- fill table accountType
-- ---------------------------
INSERT INTO `accountType`(`name`, `level`)
VALUES ('viewer', 0),
       ('content creator', 1),
       ('moderator', 2);

-- ---------------------------
-- table genre
-- ---------------------------
CREATE TABLE IF NOT EXISTS `genre`
(
    `id`          INT         NOT NULL AUTO_INCREMENT,
    `name`        VARCHAR(20) NOT NULL,
    `description` VARCHAR(40) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = INNODB;

-- ---------------------------
-- table film
-- ---------------------------
CREATE TABLE IF NOT EXISTS `film`
(
    `id`        INT         NOT NULL AUTO_INCREMENT,
    `accountId` INT         NOT NULL,
    `path`      VARCHAR(40) NOT NULL,
    `thumbnail` VARCHAR(50) NOT NULL,
    `genreId`   INT         NOT NULL,
    `length`    TIME        NULL,
    `name`      VARCHAR(30) NOT NULL,
    `accepted`  TINYINT(1)  NOT NULL DEFAULT 0,
    PRIMARY KEY (`id`)
) ENGINE = INNODB;

-- ---------------------------
-- table rating
-- ---------------------------
CREATE TABLE IF NOT EXISTS `rating`
(
    `id`        INT     NOT NULL AUTO_INCREMENT,
    `filmId`    INT     NOT NULL,
    `accountId` INT     NOT NULL,
    `review`    TINYINT NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE = INNODB;

-- ---------------------------
-- table nameChange
-- ---------------------------
CREATE TABLE IF NOT EXISTS `nameChange`
(
    `accountId`   INT         NOT NULL,
    `pendingName` VARCHAR(50) NOT NULL,
    PRIMARY KEY (accountId)
) ENGINE = INNODB;


-- ---------------------------
-- add foreign keys account table
-- ---------------------------
ALTER TABLE `account`
    ADD
        FOREIGN KEY (`accountLevel`) REFERENCES accountType (`level`);

ALTER TABLE `account`
    ADD
        FOREIGN KEY (`companyId`) REFERENCES company (`id`) ON DELETE CASCADE;

-- ---------------------------
-- add foreign keys account table
-- ---------------------------
ALTER TABLE `company`
    ADD
        FOREIGN KEY (`genre`) REFERENCES genre (`id`);

-- ---------------------------
-- add foreign keys rating table
-- ---------------------------
ALTER TABLE `rating`
    ADD
        FOREIGN KEY (`filmId`) REFERENCES film (`id`);

ALTER TABLE `rating`
    ADD
        FOREIGN KEY (`accountId`) REFERENCES account (`id`);

-- ---------------------------
-- add foreign keys film table
-- ---------------------------
ALTER TABLE `film`
    ADD
        FOREIGN KEY (`accountId`) REFERENCES account (`id`) ON DELETE CASCADE;

ALTER TABLE `film`
    ADD
        FOREIGN KEY (`genreId`) REFERENCES genre (`id`);

-- ---------------------------
-- add foreign keys namechange table
-- ---------------------------
ALTER TABLE `nameChange`
    ADD
        FOREIGN KEY (`accountId`) REFERENCES account (`id`);