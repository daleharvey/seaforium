import unittest
import requests
import simplejson

from urlparse import urlparse
from yayclient import YayClient

class TestSequenceFunctions(unittest.TestCase):

    def setUp(self):
        self.opts = dict(
            url = 'http://yayhooray.dev/'
        )

    def test_unauthenticated_users_access(self):
        r1 = requests.get(self.opts['url'])
        r2 = requests.get(self.opts['url'] + 'f/discussions')
        self.assertEqual(urlparse(r1.url).path, '/beta')
        self.assertEqual(urlparse(r2.url).path, '/beta')


    def test_register(self):
        r = YayClient.register(self.opts, 'a', 'a@a.com', 'a', 'a')
        self.assertEqual(r.status_code, 201)
        self.assertTrue(YayClient.is_logged_in(self.opts, r.cookies))


    def test_failed_login(self):
        r = YayClient.login(self.opts, 'madeupname', 'madeuppass')
        self.assertEqual(r.status_code, 401)
        self.assertFalse(YayClient.is_logged_in(self.opts, r.cookies))



    def test_login(self):
        YayClient.register(self.opts, 'b', 'b@b.com', 'b', 'b')
        r = YayClient.login(self.opts, 'b', 'b')
        self.assertEqual(r.status_code, 200)
        self.assertTrue(YayClient.is_logged_in(self.opts, r.cookies))


    def test_forgot_password(self):
        YayClient.register(self.opts, 'emailtest', 'real@email.com', 'a', 'a')
        r = YayClient.forgot_password(self.opts, 'real@email.com')
        j = simplejson.loads(r.content)
        self.assertTrue(j['ok'])
        self.assertEqual(r.status_code, 200)


    def test_forgot_password_wrong_username(self):
        r = YayClient.forgot_password(self.opts, 'fake@user.com')
        self.assertEqual(r.status_code, 412)


if __name__ == '__main__':
    unittest.main()
