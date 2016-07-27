import random
import datetime

user_id = 0
date_time = datetime.datetime.now()

def next_day():
	global date_time
	date_time = date_time + datetime.timedelta(days=1)
	return date_time

def get_date_time():
	global date_time
	return date_time

def next_time():
	rand_minutes = random.randint(5, 120)
	login = get_date_time()
	logout = login + datetime.timedelta(minutes=rand_minutes)
	return (login, logout)

def rand_int():
	return random.randint(1, 20)

def next_user_id():
	global user_id;
	user_id += 1
	return user_id



class user:
	user_id = 0
	credit_used = 0
	login_time=0
	logout_time = 0
	successful = True
	service = 1
	ras_id = 1
	def __init__(self):
		self.user_id = next_user_id()
		self.credit_used = rand_int()
		self.login_time, self.logout_time = next_time()

if __name__ == '__main__':
	u = user()
	print u.login_time
	print u.logout_time
	print u.user_id
	print u.credit_used