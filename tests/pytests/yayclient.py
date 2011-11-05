import requests
import lxml
import time

from urlparse import urlparse
from lxml import html


class YayClient:

    def __init__(self, options):
        self.options = options

    @staticmethod
    def post_thread(opts, cookies, cat, subject, content):
        data = {
            "category[]": 1,
            "content": content,
            "subject": subject
        }
        return YayClient.time_req(opts, 'post', 'newthread', data, cookies)

    @staticmethod
    def time_req(opts, method, path, data, cookies=None):
        if ("timer_file" in opts):
            time0 = time.time()
            ret = requests.request(method, opts['url'] + path, data=data, cookies=cookies)
            time1 = time.time()
            opts['timer_file'].write('%f,%f,%s,%s\n' % (time1-time0, time.time(), method, path))
            return ret
        else:
            return requests.request(method, opts['url'] + path, data=data, cookies=cookies)

    @staticmethod
    def post_reply(opts, cookies, thread, content):
        data = {"content": content}
        return YayClient.time_req(opts, 'post', thread, data, cookies)

    @staticmethod
    def register(opts, username, email, password, confirm_password):
        creds = dict(username = username,
                     email = email,
                     password = password)
        creds["confirm-password"] = confirm_password
        return YayClient.time_req(opts, 'post', 'auth/register', data=creds)

    @staticmethod
    def login(opts, username, password):
        creds = dict(username = username, password = password)
        return YayClient.time_req(opts, 'post', 'auth/login', creds)


    @staticmethod
    def forgot_password(opts, email):
        keyreq = requests.get(opts['url'] + 'auth/forgot_password')
        tree = lxml.html.fromstring(keyreq.content)
        key = tree.get_element_by_id('forgot-key').value
        creds = dict(email = email, key = key)
        return YayClient.time_req(opts, 'post', 'auth/forgot_password', creds)


    @staticmethod
    def is_logged_in(details, cookies):
        r = requests.get(details['url'] + 'f/discussions', cookies=cookies)
        tree = lxml.html.fromstring(r.content)
        return not not tree.cssselect(".welcome")
