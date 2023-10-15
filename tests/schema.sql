CREATE TABLE `files`
(
    `id`      INT          NULL,
    `name` VARCHAR(255) NULL DEFAULT NULL,
    `linkOne` VARCHAR(255) NULL DEFAULT NULL,
    `linkTwo` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `links`
(
    `id`      INT          NULL,
    `name` VARCHAR(255) NULL DEFAULT NULL,
    `link` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE `resources`
(
    `id`      INT          NULL,
    `name` VARCHAR(255) NULL DEFAULT NULL,
    `link` VARCHAR(255) NULL DEFAULT NULL,
    PRIMARY KEY (`id`)
);
