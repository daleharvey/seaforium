import unittest
import requests

from urlparse import urlparse
from yayclient import YayClient

class TestSequenceFunctions(unittest.TestCase):

    def setUp(self):
        self.opts = dict(
            url = 'http://yayhooray.dev/'
        )

    # Unlogged in users should be sent to the login form for each request
    def test_beta(self):
        r1 = requests.get(self.opts['url'])
        r2 = requests.get(self.opts['url'] + 'f/discussions')

        self.assertEqual(urlparse(r1.url).path, '/beta')
        self.assertEqual(urlparse(r2.url).path, '/beta')


    # Test we can register
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


if __name__ == '__main__':
    unittest.main()
