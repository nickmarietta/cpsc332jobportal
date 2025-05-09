SET
  FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `application`;

DROP TABLE IF EXISTS `interview`;

DROP TABLE IF EXISTS `jobs`;

DROP TABLE IF EXISTS `employer`;

DROP TABLE IF EXISTS `applicant`;

DROP TABLE IF EXISTS `account`;

SET
  FOREIGN_KEY_CHECKS = 1;

USE `testdb`;

CREATE TABLE
  `account` (
    `account_id` INT NOT NULL AUTO_INCREMENT,
    `role` ENUM ('applicant', 'employer') NOT NULL,
    `username` VARCHAR(45) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`account_id`),
    UNIQUE INDEX `username_UNIQUE` (`username`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE
  `applicant` (
    `applicant_id` INT NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(45),
    `last_name` VARCHAR(45),
    `email` VARCHAR(100),
    `phone_number` VARCHAR(15),
    `account_id` INT NOT NULL,
    `education` VARCHAR(100),
    `experience` TEXT,
    `street_name` VARCHAR(100),
    `city` VARCHAR(45),
    `state` CHAR(2),
    `zip_code` VARCHAR(45),
    PRIMARY KEY (`applicant_id`),
    CONSTRAINT `fk_applicant_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE
  `employer` (
    `employer_id` INT NOT NULL AUTO_INCREMENT,
    `company` VARCHAR(45),
    `account_id` INT NOT NULL,
    `company_name` VARCHAR(100),
    `email` VARCHAR(100),
    `phone_number` VARCHAR(15),
    `first_name` VARCHAR(45),
    `last_name` VARCHAR(45),
    `street_name` VARCHAR(100),
    `city` VARCHAR(45),
    `state` CHAR(2),
    `zip_code` VARCHAR(45),
    PRIMARY KEY (`employer_id`),
    CONSTRAINT `fk_employer_account` FOREIGN KEY (`account_id`) REFERENCES `account` (`account_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE
  `jobs` (
    `job_id` INT NOT NULL AUTO_INCREMENT,
    `employer_id` INT NOT NULL,
    `title` VARCHAR(45),
    `location` VARCHAR(100),
    `salary` DECIMAL(10, 2),
    `description` TEXT,
    `job_type` ENUM ('part-time', 'full-time', 'intern'),
    `date_posted` DATE,
    PRIMARY KEY (`job_id`),
    CONSTRAINT `fk_job_employer` FOREIGN KEY (`employer_id`) REFERENCES `employer` (`employer_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE
  `application` (
    `application_id` INT NOT NULL AUTO_INCREMENT,
    `applicant_id` INT NOT NULL,
    `job_id` INT NOT NULL,
    `status` ENUM ('Pending', 'Accepted', 'Rejected') NOT NULL,
    PRIMARY KEY (`application_id`),
    CONSTRAINT `fk_application_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `applicant` (`applicant_id`),
    CONSTRAINT `fk_application_job` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`job_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

CREATE TABLE
  `interview` (
    `interview_id` INT NOT NULL AUTO_INCREMENT,
    `applicant_id` INT NOT NULL,
    `location` VARCHAR(45) NOT NULL,
    `time` DATETIME NOT NULL,
    PRIMARY KEY (`interview_id`),
    CONSTRAINT `fk_interview_applicant` FOREIGN KEY (`applicant_id`) REFERENCES `applicant` (`applicant_id`)
  ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;