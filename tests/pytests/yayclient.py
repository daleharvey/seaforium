import requests
import lxml

from urlparse import urlparse
from lxml import html

class YayClient:

    def __init__(self, options):
        self.options = options

    @staticmethod
    def register(details, username, email, password, confirm_password):
        creds = dict(username = username,
                     email = email,
                     password = password,
                     password_confirm = confirm_password)
        return requests.post(details['url'] + 'beta/register', creds)

    @staticmethod
    def login(details, username, password):
        creds = dict(username = username, password = password)
        return requests.post(details['url'] + 'beta/login', creds)


    @staticmethod
    def forgot_password(opts, email):
        keyreq = requests.get(opts['url'] + 'beta/forgot_password')
        tree = lxml.html.fromstring(keyreq.content)
        key = tree.get_element_by_id('forgot-key').value
        creds = dict(email = email, key = key)
        return requests.post(opts['url'] + 'beta/forgot_password', creds)


    # Nasty way of checking if we are logged or not, if we can access
    # subpages, we are logged in
    @staticmethod
    def is_logged_in(details, cookies):
        r = requests.get(details['url'] + 'f/discussions', cookies=cookies)
        return urlparse(r.url).path == '/f/discussions'

