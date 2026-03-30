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

// Step 1: Handle admin files
console.log('=== Converting Admin Files ===');
Object.entries(adminFileMappings).forEach(([htmlFile, phpFile]) => {
  const sourcePath = path.join(rootDir, htmlFile);
  const targetPath = path.join(adminDir, phpFile);

  if (fs.existsSync(sourcePath)) {
    const content = fs.readFileSync(sourcePath, 'utf8');
    fs.writeFileSync(targetPath, content, 'utf8');
    console.log(`✓ Created admin/${phpFile}`);
    fs.unlinkSync(sourcePath);
  } else {
    console.log(`✗ File not found: ${htmlFile}`);
  }
});

console.log();

// Step 2: Handle regular files
console.log('=== Converting Regular Files ===');
regularFiles.forEach(htmlFile => {
  const sourcePath = path.join(rootDir, htmlFile);
  const phpFile = htmlFile.replace('.html', '.php');
  const targetPath = path.join(rootDir, phpFile);

  if (fs.existsSync(sourcePath)) {
    const content = fs.readFileSync(sourcePath, 'utf8');
    fs.writeFileSync(targetPath, content, 'utf8');
    console.log(`✓ Created ${phpFile}`);
    fs.unlinkSync(sourcePath);
  } else {
    console.log(`✗ File not found: ${htmlFile}`);
  }
});

console.log('\n=== Updating Internal Links ===');

// Step 3: Update links in root PHP files
console.log('\nUpdating root PHP files:');
regularFiles.forEach(originalHtmlFile => {
  const phpFile = originalHtmlFile.replace('.html', '.php');
  const filePath = path.join(rootDir, phpFile);
  
  if (fs.existsSync(filePath)) {
    let content = fs.readFileSync(filePath, 'utf8');
    const originalContent = content;
    
    // Replace .html with .php
    content = content.replace(/\.html(?=["\'\s\)])/g, '.php');
    
    // Replace admin-*.html with admin/*.php
    content = content.replace(/admin\.html/g, 'admin/index.php');
    content = content.replace(/admin-contacts\.php/g, 'admin/contacts.php');
    content = content.replace(/admin-members\.php/g, 'admin/members.php');
    content = content.replace(/admin-events\.php/g, 'admin/events.php');
    content = content.replace(/admin-registrations\.php/g, 'admin/registrations.php');
    content = content.replace(/admin-resources\.php/g, 'admin/resources.php');
    content = content.replace(/admin-settings\.php/g, 'admin/settings.php');
    
    if (content !== originalContent) {
      fs.writeFileSync(filePath, content, 'utf8');
      console.log(`✓ Updated links in ${phpFile}`);
    }
  }
});

// Step 4: Update links in admin PHP files
console.log('\nUpdating admin PHP files:');
fs.readdirSync(adminDir).forEach(file => {
  if (file.endsWith('.php')) {
    const filePath = path.join(adminDir, file);
    let content = fs.readFileSync(filePath, 'utf8');
    const originalContent = content;
    
    // Replace relative paths for files inside admin folder
    // img/ → ../img/
    content = content.replace(/href=["']img\//g, 'href="../img/');
    content = content.replace(/src=["']img\//g, 'src="../img/');
    
    // css/ → ../css/
    content = content.replace(/href=["']\.\/css\//g, 'href="../css/');
    content = content.replace(/href=["']css\//g, 'href="../css/');
    
    // js/ → ../js/
    content = content.replace(/src=["']\.\/js\//g, 'src="../js/');
    content = content.replace(/src=["']js\//g, 'src="../js/');
    
    // Replace admin.html → admin/index.php
    content = content.replace(/href=["']admin\.html/g, 'href="../admin/index.php');
    content = content.replace(/href=["']\.\/admin\.html/g, 'href="../admin/index.php');
    
    // Replace admin-*.html → ../admin/*.php
    content = content.replace(/href=["']admin-contacts\.html/g, 'href="contacts.php');
    content = content.replace(/href=["']admin-members\.html/g, 'href="members.php');
    content = content.replace(/href=["']admin-events\.html/g, 'href="events.php');
    content = content.replace(/href=["']admin-registrations\.html/g, 'href="registrations.php');
    content = content.replace(/href=["']admin-resources\.html/g, 'href="resources.php');
    content = content.replace(/href=["']admin-settings\.html/g, 'href="settings.php');
    
    // Replace links to regular files with relative paths
    content = content.replace(/href=["']index\.html/g, 'href="../index.php');
    content = content.replace(/href=["']contact\.html/g, 'href="../contact.php');
    content = content.replace(/href=["']join\.html/g, 'href="../join.php');
    content = content.replace(/href=["']idea\.html/g, 'href="../idea.php');
    
    // Replace .html with .php for remaining cases
    content = content.replace(/\.html(?=["\'\s\)])/g, '.php');
    
    if (content !== originalContent) {
      fs.writeFileSync(filePath, content, 'utf8');
      console.log(`✓ Updated links in admin/${file}`);
    }
  }
});

console.log('\n✓ All conversions and updates complete!');
