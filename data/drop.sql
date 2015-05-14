# ---------------------------------------------------------------------- #
# Script generated with: DeZign for Databases v6.0.0                     #
# Target DBMS:           MySQL 5                                         #
# Project file:          Project1.dez                                    #
# Project name:                                                          #
# Author:                                                                #
# Script type:           Database drop script                            #
# Created on:            2013-05-25 09:54                                #
# ---------------------------------------------------------------------- #


# ---------------------------------------------------------------------- #
# Drop table "checkin_dairy"                                             #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `checkin_dairy` MODIFY `id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `checkin_dairy` ALTER COLUMN `holiday_type` DROP DEFAULT;

ALTER TABLE `checkin_dairy` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `checkin_dairy`;

# ---------------------------------------------------------------------- #
# Drop table "employee_leave_day"                                        #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `employee_leave_day` MODIFY `id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `employee_leave_day` ALTER COLUMN `approved` DROP DEFAULT;

ALTER TABLE `employee_leave_day` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `employee_leave_day`;

# ---------------------------------------------------------------------- #
# Drop table "employee_profile"                                          #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `employee_profile` MODIFY `id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `employee_profile` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `employee_profile`;

# ---------------------------------------------------------------------- #
# Drop table "employee"                                                  #
# ---------------------------------------------------------------------- #

# Remove autoinc for PK drop #

ALTER TABLE `employee` MODIFY `emp_id` INTEGER NOT NULL;

# Drop constraints #

ALTER TABLE `employee` DROP PRIMARY KEY;

# Drop table #

DROP TABLE `employee`;
