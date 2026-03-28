import re
import os

with open('index.html', 'r', encoding='utf-8') as f:
    content = f.read()

# find header (everything until the end of <nav>)
header_match = re.search(r'(.*?<\/nav>)', content, re.DOTALL)
if header_match:
    header = header_match.group(1)
else:
    header = "<!-- header not found -->"

# find footer (everything from <footer)
footer_match = re.search(r'(<footer.*)', content, re.DOTALL)
if footer_match:
    footer = footer_match.group(1)
else:
    footer = "<!-- footer not found -->"

# find body
body = content
if header_match:
    body = body.replace(header_match.group(1), '')
if footer_match:
    body = body.replace(footer_match.group(1), '')

# Write to files
with open('public/includes/header.php', 'w', encoding='utf-8') as f:
    f.write(header)

with open('public/includes/footer.php', 'w', encoding='utf-8') as f:
    f.write(footer)

index_php_content = """<?php
// Set relative path variable
$base_url = 'assets/'; 
include 'includes/header.php';
?>

""" + body + """

<?php
include 'includes/footer.php';
?>
"""

with open('public/index.php', 'w', encoding='utf-8') as f:
    f.write(index_php_content)

print("Conversion of index.html successful.")
