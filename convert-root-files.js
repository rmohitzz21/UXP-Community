const fs = require('fs');
const path = require('path');

// List of root HTML files and their admin file mappings  
const files = [
  { html: 'index.html', php: 'index.php' },
  { html: 'contact.html', php: 'contact.php' },
  { html: 'idea.html', php: 'idea.php' },
  { html: 'join.html', php: 'join.php' }
];

const rootDir = __dirname;
const adminDir = path.join(rootDir, 'admin');

console.log('Converting root HTML files to PHP with updated links...\n');

files.forEach(({ html, php }) => {
  const sourcePath = path.join(rootDir, html);
  const targetPath = path.join(rootDir, php);
  
  if (fs.existsSync(sourcePath)) {
    let content = fs.readFileSync(sourcePath, 'utf8');
    
    // Update all internal .html references
    // Replace direct file references
    content = content.replace(/href=["']([^"']*\.html)["']/g, (match, file) => {
      if (file === 'admin.html') {
        return 'href="admin/index.php"';
      } else if (file === 'index.html') {
        return 'href="index.php"';
      } else if (file === 'contact.html') {
        return 'href="contact.php"';
      } else if (file === 'join.html') {
        return 'href="join.php"';
      } else if (file === 'idea.html') {
        return 'href="idea.php"';
      } else if (file.startsWith('admin-')) {
        const newName = file.replace('admin-', '').replace('.html', '.php');
        return `href="admin/${newName}"`;
      } else {
        return match.replace('.html', '.php');
      }
    });
    
    // Also update src attributes that might reference HTML
    content = content.replace(/src=["']([^"']*\.html)["']/g, (match, file) => {
      return match.replace('.html', '.php');
    });
    
    fs.writeFileSync(targetPath, content, 'utf8');
    console.log(`✓ Created ${php} from ${html}`);
  } else {
    console.log(`✗ File not found: ${html}`);
  }
});

// Now delete the original HTML files
console.log('\nRemoving original HTML files...');
files.forEach(({ html }) => {
  const filePath = path.join(rootDir, html);
  if (fs.existsSync(filePath)) {
    fs.unlinkSync(filePath);
    console.log(`✓ Deleted ${html}`);
  }
});

console.log('\n✓ All root files converted!');
