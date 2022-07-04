DROP PROCEDURE
IF EXISTS SP_JobRequest_GetRequestHistory;

delimiter //
CREATE PROCEDURE SP_JobRequest_GetRequestHistory (ReqIdParam int)
begin

select a.EmployeeId , b.fullname, c.grade_title_name, d.department_name, a.ApprovalNotes, a.ApprovalSts, a.ApprovalDate 
from 
t_job_request_approval_log a
inner join employees b on a.EmployeeId  = b.id 
inner join grade_titles c on b.grade_title_id = c.id 
inner join departments d on b.department_id  = d.id 
where ReqId = ReqIdParam
union
select a.EmployeeId , b.fullname, c.grade_title_name, d.department_name, a.ApprovalNotes, a.ApprovalSts, a.ApprovalDate 
from 
t_job_request_approval a
inner join employees b on a.EmployeeId  = b.id 
inner join grade_titles c on b.grade_title_id = c.id 
inner join departments d on b.department_id  = d.id 
where ReqId = ReqIdParam;

END
//
delimiter ;