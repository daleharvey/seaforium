import unittest
import requests
import simplejson

from urlparse import urlparse
from yayclient import YayClient
from ConfigParser import SafeConfigParser

import DbClient
import load_generator


class TestBasicFunctions(unittest.TestCase):

    @classmethod
    def setUpClass(cls):
        cfg = SafeConfigParser()
        cfg.read(['../yay.ini', 'tests/yay.ini'])

        DbClient.reset_database(cfg.get('db', 'host'), cfg.get('db', 'database'),
                                cfg.get('db', 'username'), cfg.get('db', 'password'))

        cls.opts = dict(
            url = cfg.get('site', 'url')
        )

    def test_fail_register(self):
        r = YayClient.register(self.opts, '    ', 'b@a.com', 'a', 'a')
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))
        r = YayClient.register(self.opts, 'my\'name', 'c@a.com', 'a', 'a')
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))
        r = YayClient.register(self.opts, 'a  b', 'd@a.com', 'a', 'a')
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))
        r = YayClient.register(self.opts, 'a_b', 'e@a.com', 'a', 'a')
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))
        r = YayClient.register(self.opts, 'a-b', 'f@a.com', 'a', 'a')
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))


    def test_new_register(self):
        r = YayClient.register(self.opts, 'neudjcshfo', 'a@a.com', 'a', 'a')
        self.assertTrue(YayClient.is_logged_in(self.opts, r.cookies))
        r = YayClient.register(self.opts, 'a b', 'a@b.com', 'a', 'a')
        self.assertTrue(YayClient.is_logged_in(self.opts, r.cookies))


    def test_failed_login(self):
        r = YayClient.login(self.opts, 'madeupname', 'madeuppass')
        self.assertEqual(r.status_code, 401)
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))


    def test_forgot_password(self):
        YayClient.register(self.opts, 'emailtest', 'real@email.com', 'a', 'a')
        r = YayClient.forgot_password(self.opts, 'real@email.com')
        j = simplejson.loads(r.content)
        self.assertTrue(j['ok'])
        self.assertEqual(r.status_code, 200)


    def test_forgot_password_wrong_username(self):
        r = YayClient.forgot_password(self.opts, 'fake@user.com')
        self.assertEqual(r.status_code, 412)


    # def test_too_many_paricipated(self):
    #     user = YayClient.register(self.opts, 'testuser', 'b@a.com', 'a', 'a')
    #     self.assertTrue(YayClient.is_logged_in(self.opts, user.cookies))
    #     load_generator.create_threads(self.opts, [user], 1000)
    #     r = requests.get(self.opts['url'] + 'f/participated', cookies=user.cookies)
    #     self.assertEqual(r.status_code, 200)


if __name__ == '__main__':
    unittest.main()
