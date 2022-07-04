drop table if exists t_job_request;

create table t_job_request (
	ReqId int auto_increment,
	ReqNo varchar(20),
	JobTitle varchar(50),
	PositionLevel varchar(20),
	Grade varchar(5),
	DeptId int,
	WorkLocation varchar(20),
	ReqQty int,
	EmploymentStatus varchar(20),
	WorkingTime varchar(10),
	QtyMale int,
	QtyFemale int,
	Education varchar(50),
	EducationFocus varchar(100),
	Age varchar(50),
	WorkingExperience varchar(100),
	ActiveDate date,
	Notes varchar(1000),
	HcReceiptDate date,
	EmployeeIdRequestor int,
	ReqSts int(1), -- 0 : new, 1:closed, 2:Reject
	CreatedDate datetime,
	CreatedBy int,
	OutStandMale int,
	OutStandFemale int,
	FilledDate date,
	ApprovedAll tinyint(1),
	Deadline date,
	primary key(ReqId)
);

insert into t_job_request values 
(1,1,'IT Staff',5,'III',7,'HO',1,'Tetap','NONSHIFT',1,0,'S1',30,'',null,'Tolong dicari segera','2020-03-22',184,0,now(),9);
insert into t_job_request values 
(2,2,'Legal Staff',4,'II',9,'HO',1,'Tetap','SHIFT',0,1,'S1',25,'',null,'Yang good looking','2020-03-22',244,0,now(),9);

drop table if exists t_job_reason_hiring;

create table t_job_reason_hiring(
	ReqId int,
	ReasonOfHiring varchar(50)
);

drop table if exists t_job_reason_hiring_replacement;

create table t_job_reason_hiring_replacement(
	ReqId int,
	EmployeeIdReplaced int,
	EmployeeIdReplacement int, -- pengganti dari karyawan yang ada
	primary key(ReqId,EmployeeIdReplaced)
);

drop table if exists t_job_description;

create table t_job_description (
	JobDescId int auto_increment,
	ReqId int,
	JobDesc varchar(1000),
	primary key(JobDescId)
	
);

drop table if exists t_job_equipment_facilities;

-- idenya checkbox valuenya varchar aja langsung nama fasilitas, jadi ketika ada others bisa langsung tampung valuenya jg

create table t_job_equipment_facilities (
	JobDescId int,
	Description varchar(50)
);

drop table if exists t_job_particular_skill;

create table t_job_particular_skill (
	ReqId int,
	SkillDesc varchar(300)
);


drop table if exists t_job_employee_relation;

create table t_job_employee_relation (
	ReqId int,
	RelType varchar(20), -- atasan langsung, bawahan langsung, atasan tidak langsung dll
	EmpoyeeId int
	-- jabatan bisa di join dari employeeid
);

-- table hc approval

drop table if exists t_job_hc_approval;

create table t_job_hc_approval (
	ReqId int,
	RelType varchar(10), -- SPV or MGR
	EmployeeId int
);

insert into t_job_hc_approval values (1,'SPV',14);
insert into t_job_hc_approval values (1,'MGR',9);


drop table if exists t_job_request_approval;

create table t_job_request_approval (
	ReqId int,
	IsHcFlag int(1), -- belum tau kyknya dibutuhkan
	EmployeeId int,
	ApprovalSts int(1),
	ApprovalNotes varchar(200),
	ApprovalDate datetime
);


-- ketika ada reject akan dimasukan semua ke log lalu di table di atas di clear.

drop table if exists t_job_request_approval_log;

create table t_job_request_approval_log (
	ReqId int,
	IsHcFlag int(1), -- belum tau kyknya dibutuhkan
	EmployeeId int,
	ApprovalSts int(1),
	ApprovalNotes varchar(200),
	ApprovalDate datetime
);