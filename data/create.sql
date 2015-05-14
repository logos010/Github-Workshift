# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          Project1.dez                                    #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database creation script                        #
# Created on:            2013-05-25 09:54                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Tables                                                                 #
# ---------------------------------------------------------------------- #

# ---------------------------------------------------------------------- #
# Add table "employee"                                                   #
# ---------------------------------------------------------------------- #

CREATE TABLE `employee` (
    `emp_id` INTEGER NOT NULL AUTO_INCREMENT,
    `first_name` VARCHAR(40) NOT NULL,
    `last_name` VARCHAR(40) NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `username` VARCHAR(40) NOT NULL,
    `password` VARCHAR(100) NOT NULL,
    `user_type` INTEGER NOT NULL,
    CONSTRAINT `PK_employee` PRIMARY KEY (`emp_id`)
);

# ---------------------------------------------------------------------- #
# Add table "employee_profile"                                           #
# ---------------------------------------------------------------------- #

CREATE TABLE `employee_profile` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `emp_id` INTEGER NOT NULL,
    `dob` VARCHAR(40),
    `phone` VARCHAR(15),
    `address` VARCHAR(255),
    `image` VARCHAR(255),
    CONSTRAINT `PK_employee_profile` PRIMARY KEY (`id`, `emp_id`)
);

# ---------------------------------------------------------------------- #
# Add table "employee_leave_day"                                         #
# ---------------------------------------------------------------------- #

CREATE TABLE `employee_leave_day` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `emp_id` INTEGER NOT NULL,
    `start` INTEGER NOT NULL,
    `end` INTEGER NOT NULL,
    `reason` TEXT NOT NULL,
    `approved` INTEGER DEFAULT 0,
    `approved_by` INTEGER,
    CONSTRAINT `PK_employee_leave_day` PRIMARY KEY (`id`, `emp_id`)
);

# ---------------------------------------------------------------------- #
# Add table "checkin_dairy"                                              #
# ---------------------------------------------------------------------- #

CREATE TABLE `checkin_dairy` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `employee_id` INTEGER NOT NULL,
    `checkin_date` VARCHAR(40) NOT NULL,
    `start_time` INTEGER NOT NULL,
    `end_time` INTEGER NOT NULL,
    `holiday_type` INTEGER NOT NULL DEFAULT 0,
    CONSTRAINT `PK_checkin_dairy` PRIMARY KEY (`id`, `employee_id`)
);
