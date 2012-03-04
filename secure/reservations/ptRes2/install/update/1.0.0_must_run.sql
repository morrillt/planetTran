USE phpscheduleIt;

ALTER TABLE reservations CHANGE COLUMN is_blackout is_blackout smallint(1) NOT NULL DEFAULT 0;
ALTER TABLE resources CHANGE COLUMN autoAssign autoAssign smallint(1);
ALTER TABLE schedules CHANGE COLUMN usePermissions usePermissions smallint(1);
ALTER TABLE schedules CHANGE COLUMN isHidden isHidden smallint(1);
ALTER TABLE schedules CHANGE COLUMN showSummary showSummary smallint(1);
ALTER TABLE schedules CHANGE COLUMN isDefault isDefault smallint(1);
