import requests
import lxml

from lxml import html

f = open('/Users/daleharvey/yaynames', 'w')

for i in range(1, 457):
    r = requests.get('http://www.yayhooray.com/users?page=%s' % i)
    tree = lxml.html.fromstring(r.content)
    elements = tree.find_class('user_username')
    for el in elements:
        f.write('INSERT INTO yay_users (username) VALUES (\'%s\');\n' % el.text_content())
        print el.text_content()

f.close()
print "\n\nCompleted!"

