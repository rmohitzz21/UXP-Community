-- Align events table with admin/events.php and homepage queries.
-- Error examples:
-- - Unknown column 'event_type' in 'field list'
-- - Unknown column 'event_date' in 'order clause'

ALTER TABLE events
  ADD COLUMN event_type ENUM('Workshop','Masterclass','Meetup','Webinar') DEFAULT 'Workshop',
  ADD COLUMN event_date DATE NULL,
  ADD COLUMN event_time TIME NULL,
  ADD COLUMN location VARCHAR(255) NULL,
  ADD COLUMN max_registrations INT DEFAULT 100,
  ADD COLUMN image VARCHAR(500) NULL,
  ADD COLUMN status ENUM('active','draft','completed','cancelled') DEFAULT 'active',
  ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Backfill from legacy `date` column if present.
UPDATE events
SET event_date = COALESCE(event_date, DATE(`date`)),
    event_time = COALESCE(event_time, TIME(`date`))
WHERE `date` IS NOT NULL;

ALTER TABLE events
  MODIFY event_date DATE NOT NULL,
  MODIFY event_time TIME NOT NULL;

-- If MySQL reports "Duplicate column name", remove already-existing ADD lines and run again.
