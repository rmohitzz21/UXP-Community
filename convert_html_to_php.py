import os
import re
import shutil

root_dir = os.path.dirname(os.path.abspath(__file__))
admin_dir = os.path.join(root_dir, 'admin')

# Ensure admin directory exists
os.makedirs(admin_dir, exist_ok=True)

print("Converting HTML to PHP files...\n")

# Admin file conversions
admin_mappings = {
    'admin.html': 'index.php',
    'admin-contacts.html': 'contacts.php',
    'admin-members.html': 'members.php',
    'admin-events.html': 'events.php',
    'admin-resources.html': 'resources.php',
    'admin-registrations.html': 'registrations.php',
    'admin-settings.html': 'settings.php'
}

# Root file conversions
root_files = [
    'contact.html',
    'index.html',
    'idea.html',
    'join.html'
]

def update_links(content, is_admin_file=False):
    """Update links in HTML content for PHP conversion"""
    
    if is_admin_file:
        # For admin files - update paths to go up one level
        # img/ → ../img/
        content = re.sub(r'href=["\']img/', 'href="../img/', content)
        content = re.sub(r'src=["\']img/', 'src="../img/', content)
        
        # css/ → ../css/
        content = re.sub(r'href=["\']\.\/css/', 'href="../css/', content)
        content = re.sub(r'href=["\']css/', 'href="../css/', content)
        
        # js/ → ../js/
        content = re.sub(r'src=["\']\.\/js/', 'src="../js/', content)
        content = re.sub(r'src=["\']js/', 'src="../js/', content)
        
        # admin-*.html → *.php (same directory)
        content = re.sub(r'href=["\']admin-contacts\.html', 'href="contacts.php', content)
        content = re.sub(r'href=["\']admin-members\.html', 'href="members.php', content)
        content = re.sub(r'href=["\']admin-events\.html', 'href="events.php', content)
        content = re.sub(r'href=["\']admin-registrations\.html', 'href="registrations.php', content)
        content = re.sub(r'href=["\']admin-resources\.html', 'href="resources.php', content)
        content = re.sub(r'href=["\']admin-settings\.html', 'href="settings.php', content)
        content = re.sub(r'href=["\']admin\.html', 'href="index.php', content)
        
        # index.html → ../index.php
        content = re.sub(r'href=["\']index\.html', 'href="../index.php', content)
        content = re.sub(r'href=["\']contact\.html', 'href="../contact.php', content)
        content = re.sub(r'href=["\']join\.html', 'href="../join.php', content)
        content = re.sub(r'href=["\']idea\.html', 'href="../idea.php', content)
    else:
        # For root files - update admin links
        content = re.sub(r'href=["\']admin\.html', 'href="admin/index.php', content)
        content = re.sub(r'href=["\']admin-contacts\.html', 'href="admin/contacts.php', content)
        content = re.sub(r'href=["\']admin-members\.html', 'href="admin/members.php', content)
        content = re.sub(r'href=["\']admin-events\.html', 'href="admin/events.php', content)
        content = re.sub(r'href=["\']admin-registrations\.html', 'href="admin/registrations.php', content)
        content = re.sub(r'href=["\']admin-resources\.html', 'href="admin/resources.php', content)
        content = re.sub(r'href=["\']admin-settings\.html', 'href="admin/settings.php', content)
        
        # Regular .html → .php
        content = re.sub(r'href=["\']contact\.html', 'href="contact.php', content)
        content = re.sub(r'href=["\']index\.html', 'href="index.php', content)
        content = re.sub(r'href=["\']join\.html', 'href="join.php', content)
        content = re.sub(r'href=["\']idea\.html', 'href="idea.php', content)
    
    # General .html → .php replacement
    content = re.sub(r'\.html(["\'\s\)])', r'.php\1', content)
    
    return content

# Convert admin files  
print("Converting admin files...")
for html_file, php_file in admin_mappings.items():
    html_path = os.path.join(root_dir, html_file)
    php_path = os.path.join(admin_dir, php_file)
    
    if os.path.exists(html_path):
        with open(html_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Update links
        content = update_links(content, is_admin_file=True)
        
        # Write PHP file
        with open(php_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        # Delete original HTML
        os.remove(html_path)
        print(f"✓ Converted {html_file} → admin/{php_file}")
    else:
        print(f"✗ File not found: {html_file}")

print()

# Convert root files
print("Converting root files...")
for html_file in root_files:
    html_path = os.path.join(root_dir, html_file)
    php_file = html_file.replace('.html', '.php')
    php_path = os.path.join(root_dir, php_file)
    
    if os.path.exists(html_path):
        with open(html_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        # Update links
        content = update_links(content, is_admin_file=False)
        
        # Write PHP file
        with open(php_path, 'w', encoding='utf-8') as f:
            f.write(content)
        
        # Delete original HTML
        os.remove(html_path)
        print(f"✓ Converted {html_file} → {php_file}")
    else:
        print(f"✗ File not found: {html_file}")

print("\n✓ All conversions complete!")
