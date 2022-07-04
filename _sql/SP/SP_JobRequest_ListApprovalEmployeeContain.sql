DROP PROCEDURE
IF EXISTS SP_JobRequest_ListApprovalEmployeeContain;

delimiter //
CREATE PROCEDURE SP_JobRequest_ListApprovalEmployeeContain (EmployeeIdParam int, ReqIdParam int)
BEGIN

DROP TABLE IF EXISTS temp_job_request;
DROP TABLE IF EXISTS temp_job_request_approval;
DROP TABLE IF EXISTS temp_job_request_current_approval;

	
CREATE TEMPORARY TABLE temp_job_request (
	ReqId INT,
	DeptId INT,
	EmployeeIdRequestor INT,
	IsPresdirFlag INT
);

CREATE TEMPORARY TABLE temp_job_request_approval (
	Id INT AUTO_INCREMENT,
	ReqId INT,
	EmployeeIdApproval INT,
	GradeIdApproval INT,
	IsHc INT,
	PRIMARY KEY(Id)
);

CREATE TEMPORARY TABLE temp_job_request_current_approval (
	ReqId INT,
	EmployeeIdApproval INT,
	GradeIdApproval INT
);

INSERT INTO temp_job_request
SELECT a.ReqId, b.department_id , a.EmployeeIdRequestor ,0 
FROM t_job_request a
INNER JOIN employees b ON a.EmployeeIdRequestor = b.id 
WHERE a.ReqSts = 0 OR a.ReqSts  = 2;

-- select * from temp_job_request;

INSERT INTO temp_job_request_approval (ReqId,EmployeeIdApproval,GradeIdApproval,IsHc)
SELECT a.ReqId,b.direct_superior,c.grade_title_id,0
FROM temp_job_request a 
INNER JOIN employees b ON a.EmployeeIdRequestor = b.id 
INNER JOIN employees c ON b.direct_superior = c.id ;

UPDATE temp_job_request a
INNER JOIN (
	SELECT a.ReqId, a.GradeIdApproval FROM temp_job_request_approval a
	INNER JOIN temp_job_request b ON a.ReqId = b.ReqId
	WHERE a.GradeIdApproval = 1 OR (a.GradeIdApproval = 1 AND b.DeptId IN (7,10,17))
) b ON a.ReqId = b.ReqId
SET IsPresdirFlag = 1;

UPDATE temp_job_request a
INNER JOIN (
	SELECT a.ReqId, a.GradeIdApproval FROM temp_job_request_approval a
	INNER JOIN temp_job_request b ON a.ReqId = b.ReqId
	WHERE a.GradeIdApproval = 2 AND b.DeptId NOT IN (7,10,17)
) b ON a.ReqId = b.ReqId
SET IsPresdirFlag = 1;

-- begin looping insert approval hierarchy


WHILE EXISTS(SELECT ReqId FROM temp_job_request WHERE IsPresdirFlag != 1) DO
	
	TRUNCATE TABLE temp_job_request_current_approval;

	INSERT INTO temp_job_request_current_approval
	SELECT a.ReqId, a.EmployeeIdApproval, a.GradeIdApproval 
	FROM temp_job_request_approval a
	JOIN (SELECT ReqId, MAX(Id) AS Id FROM temp_job_request_approval GROUP BY ReqId) b
	ON a.ReqId = b.ReqId AND a.Id = b.Id
	INNER JOIN temp_job_request c ON a.ReqId = c.ReqId
	WHERE c.IsPresdirFlag != 1;

	INSERT INTO temp_job_request_approval (ReqId,EmployeeIdApproval,GradeIdApproval,IsHc)
	SELECT a.ReqId,b.direct_superior ,c.grade_title_id, 0
	FROM temp_job_request_current_approval a
	INNER JOIN employees b ON a.EmployeeIdApproval = b.id
	INNER JOIN employees c ON b.direct_superior = c.id;

	UPDATE temp_job_request a
	INNER JOIN (
		SELECT a.ReqId, a.GradeIdApproval FROM temp_job_request_approval a
		INNER JOIN temp_job_request b ON a.ReqId = b.ReqId
		WHERE a.GradeIdApproval = 1 OR (a.GradeIdApproval = 1 AND b.DeptId IN (7,10,17))
	) b ON a.ReqId = b.ReqId
	SET IsPresdirFlag = 1;

	UPDATE temp_job_request a
	INNER JOIN (
		SELECT a.ReqId, a.GradeIdApproval FROM temp_job_request_approval a
		INNER JOIN temp_job_request b ON a.ReqId = b.ReqId
		WHERE a.GradeIdApproval = 2 AND b.DeptId NOT IN (7,10,17)
	) b ON a.ReqId = b.ReqId
	SET IsPresdirFlag = 1;
END WHILE;

-- insert HC Approval

SELECT a.EmployeeId,b.grade_title_id INTO @EmployeeIdHC, @GradeTitleHC
FROM t_job_hc_approval a
INNER JOIN employees b ON a.EmployeeId  = b.id
WHERE RelType = 'SPV';

INSERT INTO temp_job_request_approval (ReqId,EmployeeIdApproval,GradeIdApproval,IsHc)
SELECT ReqId,@EmployeeIdHC,@GradeTitleHC,1
FROM temp_job_request;

SELECT a.EmployeeId,b.grade_title_id INTO @EmployeeIdHC, @GradeTitleHC
FROM t_job_hc_approval a
INNER JOIN employees b ON a.EmployeeId  = b.id
WHERE RelType = 'MGR';

INSERT INTO temp_job_request_approval (ReqId,EmployeeIdApproval,GradeIdApproval,IsHc)
SELECT ReqId,@EmployeeIdHC,@GradeTitleHC,1
FROM temp_job_request;

-- select * from temp_job_request_approval;


IF(ReqIdParam IS NULL) THEN

	DROP TABLE IF EXISTS temp_current_request_approve;
	CREATE TEMPORARY TABLE temp_current_request_approve (
		ReqId INT
	);

	CALL SP_JobRequest_ListPendingApproval(EmployeeIdParam,1);

	-- select * from temp_current_request_approve;
	
	SELECT a.ReqId, c.`ReqNo`, c.JobTitle, e.grade_title_name, f.department_name, c.`ReqQty`, g.fullname, c.`ReqSts`,
	CASE WHEN h.ReqId IS NULL THEN 0
	ELSE 1
	END AS CurrentApprovalFlag
	FROM temp_job_request a
	INNER JOIN temp_job_request_approval b ON a.ReqId = b.ReqId
	INNER JOIN t_job_request c ON a.ReqId = c.ReqId
    INNER JOIN grade_titles e ON e.id = c.`PositionLevel`
    INNER JOIN departments f ON f.id = c.`DeptId`
    INNER JOIN employees g ON g.id = c.`EmployeeIdRequestor`
    LEFT JOIN temp_current_request_approve h ON a.ReqId = h.ReqId
	WHERE b.EmployeeIdApproval = EmployeeIdParam;
	
	DROP TABLE temp_current_request_approve;
	
ELSE 
	SELECT a.*, b.fullname, c.grade_title_name, d.department_name, 
	CASE WHEN e.EmployeeId IS NULL THEN 0
	ELSE 1
	END AS ApprovedFlag
	FROM temp_job_request_approval a
	INNER JOIN employees b ON a.EmployeeIdApproval = b.id
	INNER JOIN grade_titles c ON a.GradeIdApproval = c.id
	INNER JOIN departments d ON b.department_id = d.id 
	LEFT JOIN t_job_request_approval e ON a.EmployeeIdApproval = e.EmployeeId AND a.ReqId = e.ReqId AND a.IsHc = e.IsHcFlag 
	WHERE a.reqid = ReqIdParam
	ORDER BY reqid,id;
END IF;


DROP TABLE temp_job_request;
DROP TABLE temp_job_request_approval;
DROP TABLE temp_job_request_current_approval;

END
//
delimiter ;

