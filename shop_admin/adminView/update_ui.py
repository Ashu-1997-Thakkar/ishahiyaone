import os
import glob
import re

directory = 'e:/wamp64/www/ishahiyaone/shop_admin/adminView'
files = glob.glob(os.path.join(directory, '*.php'))

target_thead = '<thead style="background-color: #c59d2f; color: white;">'

for file_path in files:
    with open(file_path, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    # Regex to match any <thead ...> and replace it
    # We want to replace <thead>, <thead class="thead-dark">, <thead class="bg-primary text-white">, etc.
    # Exclude those that already have the exact target or #c59d2f
    if '#c59d2f' not in content:
        # replace <thead ...> with our target
        new_content = re.sub(r'<thead[^>]*>', target_thead, content)
        
        if new_content != content:
            with open(file_path, 'w', encoding='utf-8') as f:
                f.write(new_content)
            print(f'Updated: {os.path.basename(file_path)}')
