USE phpscheduleIt;

# Add summary to reservations #

ALTER TABLE reservations ADD COLUMN is_blackout smallint(1) not null default 0;
CREATE INDEX res_isblackout ON reservations (is_blackout);
ALTER TABLE reservations ADD COLUMN summary text;
ALTER TABLE resources ADD COLUMN autoAssign smallint(1);
CREATE INDEX rs_autoAssign ON resources (autoAssign);
ALTER TABLE resources CHANGE COLUMN status status char(1) not null default 'a';
ALTER TABLE login CHANGE COLUMN e_add e_add char(1) not null default 'y';
ALTER TABLE login CHANGE COLUMN e_mod e_mod char(1) not null default 'y';
ALTER TABLE login CHANGE COLUMN e_del e_del char(1) not null default 'y';
ALTER TABLE login CHANGE COLUMN e_html e_html char(1) not null default 'y';
ALTER TABLE reservations ADD COLUMN scheduleid char(16) not null AFTER memberid;
CREATE INDEX res_scheduleid ON reservations (scheduleid);
ALTER TABLE resources ADD COLUMN scheduleid char(16) not null AFTER machid;
CREATE INDEX rs_scheduleid ON resources (scheduleid);
CREATE TABLE schedules (
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

CREATE INDEX sh_scheduleid ON schedules (scheduleid);
CREATE INDEX sh_hidden ON schedules (isHidden);
CREATE INDEX sh_perms ON schedules (usePermissions);

create table schedule_permission (
  scheduleid char(16) not null,
  memberid char(16) not null,
  primary key(scheduleid, memberid)
);

create index sp_scheduleid on schedule_permission (scheduleid);
create index sp_memberid on schedule_permission (memberid);