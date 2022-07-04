DELIMITER $$

USE `anekayasin_inaco`$$

DROP PROCEDURE IF EXISTS `SP_LEAVE_IsPengajuan`$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `SP_LEAVE_IsPengajuan`(
EmployeeNo VARCHAR(20),
StartDate DATE,
EndDate DATE
)
BEGIN
DECLARE StartDateComp, EndDateComp DATE;
DECLARE QtyIsPengajuan, QtyWorkingDays, QtyDays, QtyIsHoliday INT;

CREATE TEMPORARY TABLE temp_table (
	AbsentDate DATE,
	EmployeeNo VARCHAR(20),
	IsHoliday TINYINT(1),
	IsPengajuan TINYINT(1),
	IsWorkingDay TINYINT(1),
	QtyIsPengajuan INT,
	QtyWorkingDays INT
);

SET StartDateComp = StartDate;
SET EndDateComp = EndDate;

WHILE StartDateComp <= EndDateComp DO
 INSERT INTO temp_table VALUES(StartDateComp,EmployeeNo,0,0,0,0, 0);
 SET StartDateComp = DATE_ADD(StartDateComp, INTERVAL 1 DAY);
END WHILE;

-- update hari kerja
UPDATE temp_table a
INNER JOIN employee_hks b ON a.EmployeeNo = b.employee_no
SET a.IsHoliday = 
CASE
 WHEN b.hk = 5 AND (DAYNAME(a.AbsentDate) = 'Saturday' OR DAYNAME(a.AbsentDate) = 'Sunday') THEN 1
  WHEN b.hk = 6 AND DAYNAME(a.AbsentDate) = 'Sunday' THEN 1
  ELSE 0
END;

-- update hari libur nasional dan cuti bersama
UPDATE temp_table a
INNER JOIN employee_hks b ON a.EmployeeNo = b.employee_no
INNER JOIN holidays c ON a.AbsentDate = c.date
SET a.IsHoliday = 1
WHERE c.hk = b.hk OR c.hk = 0;

-- update pengajuan
UPDATE temp_table a
INNER JOIN employee_leaves b ON a.EmployeeNo = b.employee_no
INNER JOIN `leaves` c ON c.leave_code = b.leave_type AND c.leave_category = 'cuti' OR b.leave_type IN ('LVAL', 'LVSTD', 'LVUL')
SET a.IsPengajuan = 1
WHERE b.status IN ('new', 'apv') AND a.AbsentDate BETWEEN b.start_date AND b.end_date;

-- update working date
UPDATE temp_table a
SET a.IsWorkingDay = 1
WHERE a.IsHoliday = 0;

SELECT SUM(IsPengajuan), COUNT(AbsentDate), SUM(IsHoliday), SUM(IsWorkingDay)
INTO QtyIsPengajuan, QtyDays, QtyIsHoliday, QtyWorkingDays
FROM temp_table;

SELECT QtyIsPengajuan, QtyDays, QtyIsHoliday, QtyWorkingDays;
-- select * from temp_table;

DROP TABLE temp_table;
END$$

DELIMITER ;