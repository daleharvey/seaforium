import requests
import lxml
import random
import string
import time

from urlparse import urlparse
from lxml import html
from yayclient import YayClient
from ConfigParser import SafeConfigParser

import DbClient

def test_sequence():
    users = []
    threads = []
    for i in range(5):
        (new_users, new_threads) = bootstrap(5, 10, 100, users, threads)
        threads += new_threads
        users += new_users
        time.sleep(5)


def bootstrap(num_users=100, num_threads=100, num_replies=1000, users = [], threads = []):
    opts = read_config()
    users += create_users(opts, num_users)
    threads += create_threads(opts, users, num_threads)
    create_replies(opts, users, threads, num_replies)
    return (users, threads)


def read_config():
    cfg = SafeConfigParser()
    cfg.read(['../yay.ini', 'tests/yay.ini'])
    return dict(
        url = cfg.get('site', 'url')
    )


def create_users(opts, num):
    return [
        YayClient.register(opts, user['name'], user['email'], user['password'], user['password'])
        for user in users_generator(num)
    ]

def create_threads(opts, users, num):
    threads = []
    for i in range(num):
        user = random.choice(users)
        thread = YayClient.post_thread(opts, user.cookies, 2, thread_title_generator(),
                                       reply_generator())
        threads.append(thread)
    return threads


def create_replies(opts, users, threads, num):
    for i in range(num):
        user = random.choice(users)
        thread = urlparse(random.choice(threads).url).path[1:]
        YayClient.post_reply(opts, user.cookies, thread, reply_generator())

def characters():
    return 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789 '

def thread_title_generator():
    return ''.join(random.choice(characters()) for i in xrange(random.randint(10, 30)))

def reply_generator():
    return ''.join(random.choice(characters()) for i in xrange(random.randint(10, 500)))

def users_generator(num):
    return [
        dict(name = "test %d" %i, email = "test_%d@email.com" %i, password = "a")
        for i in range(num)]
