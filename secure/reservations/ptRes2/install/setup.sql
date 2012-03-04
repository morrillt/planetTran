# phpScheduleIt 1.0.0 #
drop database if exists phpScheduleIt;
create database phpScheduleIt;
use phpScheduleIt;

# Create login table #
create table login (
  memberid char(16) not null primary key,
  email char(75) not null,
  password char(32) not null,
  fname char(30) not null,
  lname char(30) not null,
  phone char(16) not null,
  institution char(255),
  position char(100),
  e_add char(1) not null default 'y',
  e_mod char(1) not null default 'y',
  e_del char(1) not null default 'y',
  e_html char(1) not null default 'y'
  );

# Create indexes on login table #
create index login_memberid on login (memberid);
create index login_email on login (email);
create index login_password on login (password);


# Create reservations table #  
create table reservations (
  resid char(16) not null primary key,
  machid char(16) not null,
  memberid char(16) not null,
  scheduleid char(16) not null,
  date integer not null,
  startTime integer not null,
  endTime integer not null,
  created integer not null,
  modified integer,
  parentid char(16),
  is_blackout smallint(1) not null default 0,
  summary text
  );

# Create indexes on reservations table #
create index res_resid on reservations (resid);
create index res_machid on reservations (machid);
create index res_memberid on reservations (memberid);
create index res_scheduleid on reservations (scheduleid);
create index res_date on reservations (date);
create index res_startTime on reservations (startTime);
create index res_endTime on reservations (endTime);
create index res_created on reservations (created);
create index res_modified on reservations (modified);
create index res_parentid on reservations (parentid);
create index res_isblackout on reservations (is_blackout);

# Create resources table #
create table resources (
  machid char(16) not null primary key,
  scheduleid char(16) not null,
  name char(75) not null,
  location char(250),
  rphone char(16),
  notes text,
  status char(1) not null default 'a',
  minRes integer not null,
  maxRes integer not null,
  autoAssign smallint(1)
  );

# Create indexes on resources table #
create index rs_machid on resources (machid);
create index rs_scheduleid on resources (scheduleid);
create index rs_name on resources (name);
create index rs_status on resources (status);
  
# Create permission table #
create table permission (
  memberid char(16) not null,
  machid char(16) not null,
  primary key(memberid, machid)
  );
  
# Create indexes on permission table #
create index per_memberid on permission (memberid);
create index per_machid on permission (machid);

# Create schedules table #
create table schedules (
  scheduleid char(16) not null primary key,
  scheduleTitle char(75),
  dayStart integer not null,
  dayEnd integer not null,
  timeSpan integer not null,
  timeFormat integer not null,
  weekDayStart integer not null,
  viewDays integer not null,
  usePermissions smallint(1),
  isHidden smallint(1),
  showSummary smallint(1),
  adminEmail char(75),
  isDefault smallint(1),
  dayOffset integer
  );

# Create indexes on schedules table #
create index sh_scheduleid on schedules (scheduleid);
create index sh_hidden on schedules (isHidden);
create index sh_perms on schedules (usePermissions);

# Create schedule permission tables
create table schedule_permission (
  scheduleid char(16) not null,
  memberid char(16) not null,
  primary key(scheduleid, memberid)
  );

# Create schedule permission indexes #
create index sp_scheduleid on schedule_permission (scheduleid);
create index sp_memberid on schedule_permission (memberid);

grant select, insert, update, delete
on phpScheduleIt.*
to schedule_user@localhost identified by 'password';