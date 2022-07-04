DROP PROCEDURE
IF EXISTS SP_JobRequest_ListPendingApproval;

delimiter //
CREATE PROCEDURE SP_JobRequest_ListPendingApproval (EmployeeIdParam int, SPFlag int)
BEGIN

DROP TABLE IF EXISTS temp_data;

CREATE TEMPORARY TABLE temp_data (
	ReqId INT,
	EmployeeIdRequestor INT,
	DeptId INT,
	EmployeeIdCurrentApproves INT,
	GradeIdCurrentApproves INT,
	PresdirIsApprove INT,
	IsHcApproveFlag INT
);

INSERT INTO temp_data 
SELECT a.ReqId, a.EmployeeIdRequestor, b.department_id, NULL, NULL, 0, 0
FROM t_job_request a
INNER JOIN employees b ON a.EmployeeIdRequestor = b.id 
WHERE a.ReqSts != 1 AND a.ApprovedAll IS NULL;

-- select * from temp_data;

-- update approval from history

DROP TABLE IF EXISTS temp_t_job_request_approval;

CREATE TEMPORARY TABLE temp_t_job_request_approval (
	id INT AUTO_INCREMENT,
	ReqId INT,
	IsHcFlag INT,
	EmployeeId INT,
	direct_superior INT DEFAULT NULL,
	grade_title_id INT DEFAULT NULL,
	ApprovalSts INT,
	ApprovalDate DATETIME,
	PRIMARY KEY(id)
);

INSERT INTO temp_t_job_request_approval (ReqId,IsHcFlag,EmployeeId,ApprovalSts,ApprovalDate) 
SELECT ReqId,IsHcFlag,EmployeeId,ApprovalSts,ApprovalDate FROM t_job_request_approval;

UPDATE temp_t_job_request_approval a
INNER JOIN employees b ON a.EmployeeId = b.id 
SET a.direct_superior = b.direct_superior, a.grade_title_id = b.grade_title_id;

-- SELECT a.ReqId, a.EmployeeId, IFNULL(a.direct_superior,a.EmployeeId) AS direct_superior, a.grade_title_id  
-- 	FROM temp_t_job_request_approval a
-- 	JOIN (SELECT ReqId, MAX(Id) AS Id FROM temp_t_job_request_approval GROUP BY ReqId) b
-- 	ON a.ReqId = b.ReqId AND a.Id = b.Id;

-- select * from temp_t_job_request_approval;

-- Cari approval saat ini non HC
UPDATE temp_data a
INNER JOIN (
/*
	select b.ReqId, a.EmployeeId, IFNULL(c.direct_superior,a.EmployeeId) as direct_superior, c.grade_title_id  
	from t_job_request_approval a
	inner join t_job_request b on a.ReqId = b.ReqId 
	inner join employees c on a.EmployeeId = c.id
	where b.ReqSts != 1
	group by a.ReqId 
	-- order by a.ApprovalDate desc limit 1
	*/
	SELECT a.ReqId, a.EmployeeId, IFNULL(a.direct_superior,a.EmployeeId) AS direct_superior, a.grade_title_id  
	FROM temp_t_job_request_approval a
	JOIN (SELECT ReqId, MAX(Id) AS Id FROM temp_t_job_request_approval WHERE IsHcFlag != 1 GROUP BY ReqId) b
	ON a.ReqId = b.ReqId AND a.Id = b.Id
) b ON a.ReqId = b.ReqId 
INNER JOIN employees c ON c.id = b.direct_superior
SET a.EmployeeIdCurrentApproves = 
	CASE 
	WHEN b.grade_title_id = 1 OR (b.grade_title_id = 1 AND a.DeptId IN (7,10,17)) THEN b.EmployeeId
	WHEN b.grade_title_id = 2 AND a.DeptId NOT IN (7,10,17) THEN b.EmployeeId
	ELSE b.direct_superior
	END, 
a.GradeIdCurrentApproves = CASE 
	WHEN b.grade_title_id = 1 OR (b.grade_title_id = 1 AND a.DeptId IN (7,10,17)) THEN b.grade_title_id
	WHEN b.grade_title_id = 2 AND a.DeptId NOT IN (7,10,17) THEN b.grade_title_id
	ELSE c.grade_title_id
	END;

-- SELECT * FROM temp_data;

UPDATE temp_data a
INNER JOIN `t_job_request_approval` b ON b.EmployeeId = a.EmployeeIdCurrentApproves AND a.ReqId = b.ReqId AND b.IsHcFlag != 1
SET PresdirIsApprove = 1
WHERE a.GradeIdCurrentApproves = 1 OR (a.GradeIdCurrentApproves = 1 AND a.DeptId IN (7,10,17));

UPDATE temp_data a
INNER JOIN `t_job_request_approval` b ON b.EmployeeId = a.EmployeeIdCurrentApproves AND a.ReqId = b.ReqId AND b.IsHcFlag != 1
SET PresdirIsApprove = 1
WHERE a.GradeIdCurrentApproves = 2 AND a.DeptId NOT IN (7,10,17);

-- select * from temp_data;
-- update hc approval spv

-- update spv level HC
/*
update temp_data a
inner join (
	select a.ReqId,a.RelType,a.EmployeeId as EmployeeIdCurrentApproves, b.grade_title_id 
	from t_job_hc_approval a
	inner join employees b on a.EmployeeId = b.id
	where a.RelType = 'SPV'
) b on a.ReqId = b.ReqId
set a.EmployeeIdCurrentApproves = b.EmployeeIdCurrentApproves, a.GradeIdCurrentApproves = b.grade_title_id
where a.PresdirIsApprove = 1;
*/

SELECT  a.EmployeeId , b.grade_title_id 
INTO @EmployeeIdCurrentApproves, @grade_title_id 
FROM t_job_hc_approval a
INNER JOIN employees b ON a.EmployeeId = b.id
WHERE a.RelType = 'SPV';

UPDATE temp_data SET EmployeeIdCurrentApproves = @EmployeeIdCurrentApproves, GradeIdCurrentApproves = @grade_title_id 
WHERE PresdirIsApprove = 1;

-- update flag hc approve, ketika ada 1 yang is hc flag 1 maka sudah ada spv yang approve, lanjut ke mgr level

UPDATE temp_data a
INNER JOIN (
	SELECT a.ReqId
	FROM t_job_request_approval a
	INNER JOIN t_job_request b ON a.ReqId  = b.ReqId
	INNER JOIN t_job_hc_approval c ON c.EmployeeId = a.EmployeeId AND c.RelType = 'SPV'
	WHERE b.ReqSts != 1 AND a.IsHcFlag = 1 AND b.`ApprovedAll` IS NULL
	GROUP BY a.`ReqId`
	ORDER BY a.ApprovalDate DESC
) b ON a.ReqId = b.ReqId 
SET a.IsHcApproveFlag = 1;

-- 2. select * from temp_data;

-- update mgr approval yang IsHcApproveFlag = 1

/*
update temp_data a
inner join (
	select a.ReqId,a.RelType,a.EmployeeId as EmployeeIdCurrentApproves, b.grade_title_id 
	from t_job_hc_approval a
	inner join employees b on a.EmployeeId = b.id
	where a.RelType = 'MGR'
) b on a.ReqId = b.ReqId
set a.EmployeeIdCurrentApproves = b.EmployeeIdCurrentApproves, a.GradeIdCurrentApproves = b.grade_title_id
where a.IsHcApproveFlag = 1;
*/

SELECT  a.EmployeeId , b.grade_title_id 
INTO @EmployeeIdCurrentApproves, @grade_title_id 
FROM t_job_hc_approval a
INNER JOIN employees b ON a.EmployeeId = b.id
WHERE a.RelType = 'MGR';

UPDATE temp_data SET EmployeeIdCurrentApproves = @EmployeeIdCurrentApproves, GradeIdCurrentApproves = @grade_title_id 
WHERE IsHcApproveFlag = 1;

-- SELECT * FROM temp_data;

-- update yang belum ada approval sama sekali

UPDATE temp_data a
INNER JOIN (
	SELECT a.id, a.direct_superior, b.grade_title_id
	FROM employees a
	INNER JOIN employees  b ON a.direct_superior = b.id
) b 
ON a.EmployeeIdRequestor = b.id
SET a.EmployeeIdCurrentApproves = b.direct_superior, a.GradeIdCurrentApproves = b.grade_title_id
WHERE a.EmployeeIdCurrentApproves IS NULL AND PresdirIsApprove = 0;

-- select * from temp_data;

CASE WHEN SPFlag = 0
THEN
	CASE WHEN EmployeeIdParam IS NULL THEN 
		SELECT a.ReqId, a.EmployeeIdRequestor, c.fullname AS EmployeeRequestorName, 
		a.EmployeeIdCurrentApproves, d.fullname AS EmployeeApprovesName, a.GradeIdCurrentApproves , e.grade_title_name AS GradeNameCurrentApproves,
		b.JobTitle, b.PositionLevel ,b.Grade, a.PresdirIsApprove, a.IsHcApproveFlag
		FROM temp_data a
		INNER JOIN t_job_request b ON a.ReqId = b.ReqId
		INNER JOIN employees c ON a.EmployeeIdRequestor = c.id 
		INNER JOIN employees d ON a.EmployeeIdCurrentApproves = d.id
		INNER JOIN grade_titles e ON a.GradeIdCurrentApproves = e.id;
	ELSE
		SELECT a.ReqId, a.EmployeeIdRequestor, c.fullname AS EmployeeRequestorName, 
		a.EmployeeIdCurrentApproves, d.fullname AS EmployeeApprovesName, a.GradeIdCurrentApproves , e.grade_title_name AS GradeNameCurrentApproves,
		b.JobTitle, b.PositionLevel ,b.Grade, a.PresdirIsApprove,a.IsHcApproveFlag
		FROM temp_data a
		INNER JOIN t_job_request b ON a.ReqId = b.ReqId
		INNER JOIN employees c ON a.EmployeeIdRequestor = c.id 
		INNER JOIN employees d ON a.EmployeeIdCurrentApproves = d.id
		INNER JOIN grade_titles e ON a.GradeIdCurrentApproves = e.id 
		WHERE EmployeeIdCurrentApproves = EmployeeIdParam;
	END CASE;
ELSE 
	DROP TABLE IF EXISTS temp_current_request_approve;
	CREATE TEMPORARY TABLE temp_current_request_approve (
		ReqId INT
	);
	CASE WHEN EmployeeIdParam IS NULL THEN 
		INSERT INTO temp_current_request_approve
		SELECT ReqId
		FROM temp_data;
	ELSE
		INSERT INTO temp_current_request_approve
		SELECT ReqId
		FROM temp_data 
		WHERE EmployeeIdCurrentApproves = EmployeeIdParam;
	END CASE;
-- select * from temp_current_request_approve;
END CASE;

DROP TABLE temp_data;
DROP TABLE temp_t_job_request_approval;


END
//
delimiter ;

