const fs = require('fs');
const path = require('path');

const rootDir = __dirname;
const adminDir = path.join(rootDir, 'admin');

// Ensure admin directory exists
if (!fs.existsSync(adminDir)) {
  fs.mkdirSync(adminDir, { recursive: true });
}

// Admin file mappings: source -> target in admin folder
const adminFileMappings = {
  'admin.html': 'index.php',
  'admin-contacts.html': 'contacts.php',
  'admin-members.html': 'members.php',
  'admin-events.html': 'events.php',
  'admin-resources.html': 'resources.php',
  'admin-registrations.html': 'registrations.php',
  'admin-settings.html': 'settings.php'
};

// Regular HTML files to convert to PHP in root
const regularFiles = ['contact.html', 'index.html', 'idea.html', 'join.html'];

console.log('Starting HTML to PHP conversion...\n');

// Handle admin files
Object.entries(adminFileMappings).forEach(([htmlFile, phpFile]) => {
  const sourcePath = path.join(rootDir, htmlFile);
  const targetPath = path.join(adminDir, phpFile);

  if (fs.existsSync(sourcePath)) {
    // Read the content
    const content = fs.readFileSync(sourcePath, 'utf8');
    
    // Write to new location
    fs.writeFileSync(targetPath, content, 'utf8');
    console.log(`✓ Converted ${htmlFile} → admin/${phpFile}`);
    
    // Delete original file
    fs.unlinkSync(sourcePath);
    console.log(`  Deleted original ${htmlFile}`);
  } else {
    console.log(`✗ File not found: ${htmlFile}`);
  }
});

console.log();

// Handle regular files
regularFiles.forEach(htmlFile => {
  const sourcePath = path.join(rootDir, htmlFile);
  const phpFile = htmlFile.replace('.html', '.php');
  const targetPath = path.join(rootDir, phpFile);

  if (fs.existsSync(sourcePath)) {
    // Read the content
    const content = fs.readFileSync(sourcePath, 'utf8');
    
    // Write to new location
    fs.writeFileSync(targetPath, content, 'utf8');
    console.log(`✓ Converted ${htmlFile} → ${phpFile}`);
    
    // Delete original file
    fs.unlinkSync(sourcePath);
    console.log(`  Deleted original ${htmlFile}`);
  } else {
    console.log(`✗ File not found: ${htmlFile}`);
  }
});

console.log('\n✓ Conversion complete!');
