import requests
from urlparse import urlparse

class YayClient:

    def __init__(self, options):
        self.options = options

    @staticmethod
    def register(details, username, email, password, confirm_password):
        creds = dict(username = username,
                     email = email,
                     password = password,
                     password_confirm = confirm_password)
        r = requests.post(details['url'] + 'beta/register', creds)
        return r

    @staticmethod
    def login(details, username, password):
        creds = dict(username = username, password = password)
        r = requests.post(details['url'] + 'beta/login', creds)
        return r

    # Nasty way of checking if we are logged or not, if we can access
    # subpages, we are logged in
    @staticmethod
    def is_logged_in(details, cookies):
        r = requests.get(details['url'] + 'f/discussions', cookies=cookies)
        return urlparse(r.url).path == '/f/discussions'
