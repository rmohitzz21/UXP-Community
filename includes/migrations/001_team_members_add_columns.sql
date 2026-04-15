-- Align team_members with admin/team.php (run once in phpMyAdmin if inserts fail).
-- Error example: Unknown column 'linkedin_url' or 'display_order' in ...

ALTER TABLE team_members
  ADD COLUMN linkedin_url VARCHAR(500) DEFAULT NULL,
  ADD COLUMN behance_url VARCHAR(500) DEFAULT NULL,
  ADD COLUMN display_order INT DEFAULT 0;

-- If MySQL reports "Duplicate column name", drop that ADD line and run again.
