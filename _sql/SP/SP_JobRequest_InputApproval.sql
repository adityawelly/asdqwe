DROP PROCEDURE
IF EXISTS SP_JobRequest_InputApproval;

delimiter //
CREATE PROCEDURE SP_JobRequest_InputApproval (ReqIdParam int, EmployeeIdParam int, StsApprovalParam int, NotesParam varchar(300), 
IsHcFlagParam int)
BEGIN

INSERT INTO t_job_request_approval (ReqId, IsHcFlag, EmployeeId, ApprovalSts, ApprovalNotes, ApprovalDate)
VALUES 
(ReqIdParam,IsHcFlagParam,EmployeeIdParam,StsApprovalParam,NotesParam,NOW());

IF(StsApprovalParam = 2)
THEN
	 -- reject
	INSERT INTO t_job_request_approval_log 
	SELECT * FROM t_job_request_approval WHERE ReqId = ReqIdParam;
	DELETE FROM t_job_request_approval WHERE ReqId = ReqIdParam;
	UPDATE t_job_request SET ReqSts = 2 WHERE ReqId = ReqIdParam;
ELSE 
	IF(IsHcFlagParam = 1)
	THEN
		-- check klo mgr tutup requestnya
		SET @RelTypeParam = (SELECT RelType FROM t_job_hc_approval WHERE EmployeeId = EmployeeIdParam);
		IF(@RelTypeParam = 'MGR')
		THEN
			-- approval adalah manager, update flag
			-- generate PTK No
			-- generate tanggal deadline
			SELECT CASE
			    WHEN COALESCE(MAX(LEFT(`ReqNo`, 3)), 0)+1 = 1000 THEN 1
			    ELSE COALESCE(MAX(LEFT(`ReqNo`, 3)), 0)+1
			    END AS maxID INTO @NextReqNo FROM t_job_request;
			UPDATE t_job_request SET `ApprovedAll` = 1,
			`ReqNo` = CONCAT(LPAD(@NextReqNo, 3, '0'), '/PTK/', LPAD(MONTH(CURDATE()), 2, '0'), '/', YEAR(CURDATE())),
			`Deadline` = (CASE
				WHEN `PositionLevel` = 6 THEN DATE_ADD(CURDATE(), INTERVAL 14 DAY)
				WHEN `PositionLevel` = 4 OR `PositionLevel` = 5 THEN DATE_ADD(CURDATE(), INTERVAL 30 DAY)
				WHEN `PositionLevel` = 3 THEN DATE_ADD(CURDATE(), INTERVAL 45 DAY)
				WHEN `PositionLevel` = 2 THEN DATE_ADD(CURDATE(), INTERVAL 60 DAY)
				ELSE CURDATE()
				END) WHERE ReqId = ReqIdParam;
		ELSE 
			SELECT 'do nothing';
		END IF;
	ELSE
		SELECT 'do nothing';
	END IF;
END IF;

END
//
delimiter ;

