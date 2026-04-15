-- Align resources table with admin/resources.php and homepage queries.
-- Error examples:
-- - Unknown column 'thumbnail' in 'field list'
-- - Unknown column 'is_active' in 'where clause'

ALTER TABLE resources
  ADD COLUMN thumbnail VARCHAR(500) DEFAULT NULL,
  ADD COLUMN downloads INT DEFAULT 0,
  ADD COLUMN is_active TINYINT(1) DEFAULT 1,
  ADD COLUMN updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- If MySQL reports "Duplicate column name", remove already-existing ADD lines and run again.
