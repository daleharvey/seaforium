import requests
import lxml

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
        return requests.post(opts['url'] + 'newthread', data, cookies=cookies)

    @staticmethod
    def post_reply(opts, cookies, thread, content):
        data = {"content": content}
        return requests.post(opts['url'] + thread, data, cookies=cookies)

    @staticmethod
    def register(details, username, email, password, confirm_password):
        creds = dict(username = username,
                     email = email,
                     password = password)
        creds["confirm-password"] = confirm_password;
        return requests.post(details['url'] + 'auth/register', creds)

    @staticmethod
    def login(details, username, password):
        creds = dict(username = username, password = password)
        return requests.post(details['url'] + 'auth/login', creds)


    @staticmethod
    def forgot_password(opts, email):
        keyreq = requests.get(opts['url'] + 'auth/forgot_password')
        tree = lxml.html.fromstring(keyreq.content)
        key = tree.get_element_by_id('forgot-key').value
        creds = dict(email = email, key = key)
        return requests.post(opts['url'] + 'auth/forgot_password', creds)


    @staticmethod
    def is_logged_in(details, cookies):
        r = requests.get(details['url'] + 'f/discussions', cookies=cookies)
        tree = lxml.html.fromstring(r.content)
        return not not tree.cssselect(".welcome")


class OldYayClient:

    @staticmethod
    def read_last_pm_link(username, password):
        host = 'http://yayhooray.com'
        r = requests.post(host, dict(username=username,
                                     password=password,
                                     action='login'))
        messages = requests.get("%s/messages" % host, cookies=r.cookies)
        tree = lxml.html.fromstring(messages.content)
        message_url = tree.find_class("message")[0].cssselect("a")[0].get('href')
        message = requests.get("%s/%s" % (host, message_url), cookies=r.cookies)
        tree2 = lxml.html.fromstring(message.content)
        return tree2.cssselect(".messageoriginal a")[0].get('href')
