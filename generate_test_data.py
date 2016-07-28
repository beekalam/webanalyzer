import random
import datetime
import os

id = 0
user_id = 0
date_time = datetime.datetime.now()

def next_id():
	global id
	id += 1
	return id

def get_id():
	global id
	return id

def next_day():
	global date_time
	date_time = date_time + datetime.timedelta(days=1)
	return date_time

def get_date_time():
	global date_time
	return date_time

def reset_date_time():
	global date_time
	date_time = datetime.datetime.now()

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

def randomip():
	a = random.randint(100, 200)
	return '192.168.2.' + str(a)


class connection_log:
	def __init__(self,user_id,user_name):
		# self.startdate = datetime.datetime.now()
		self.connection_log_id = next_id()
		self.user_id = user_id
		self.credit_used = rand_int()
		self.login_time, self.logout_time = next_time()
		self.username = user_name
		self.service = 1
		self.ras_id = 1

	def getsql(self):
		ret = []
		ret.append(self.makesql())

		ip = randomip()
		cd = connection_log_details(self.connection_log_id, self.login_time, self.logout_time,self.username,ip)
		ret.append(cd.getsql())
		temp_datetime = self.login_time
		while(temp_datetime < self.logout_time):
			b = log(temp_datetime,str(ip) )
			ret.append(b.getsql())
			temp_datetime += datetime.timedelta(seconds=random.randint(10,15))
		return ret


		# print b.getsql()
		# return self.makesql()

	def makesql(self):
		ret = "insert into connection_log(connection_log_id, user_id, credit_used, login_time, logout_time, successful, service, ras_id) values ("
		ret += "'{0}', '{1}','{2}', '{3}', '{4}', '{5}','{6}','{7}'".format(str(self.connection_log_id), self.user_id, self.credit_used, self.login_time,self.logout_time, True, str(self.service), str(self.ras_id) )
		ret += ");"
		return ret

	def generate_sql_string(self):
		ret = ''
		for item in self.getsql():
				ret += item +  os.linesep
		return ret

class connection_log_details:

	def __init__(self,connection_log_id,login_time, logout_time,username,ip):
		self.connection_log_id = get_id()
		self.login_time  = login_time
		self.logout_time = logout_time
		self.names = []
		self.values =[]
		self.username = username
		self.ip = ip
		self.make_random_name_values()

	def make_random_name_values(self):
		self.names = ['bytes in', 'bytes out','ippool','ip pool assigned ip', 'nas port type','username']
		values = self.values
		values.append(random.randint(8000, 25000))
		values.append(random.randint(8000, 10000))
		values.append('ippool1')
		values.append(str(self.ip))
		values.append('Ethernet')
		values.append(self.username)

	def getnames(self):
		ret_string = ''
		for i,name in enumerate(self.names):
			ret_string += ",'" + str(name) + "'"
		return ret_string

	def getvalues(self):
		ret_string = ''
		for i,value in enumerate(self.values):
			ret_string += ", '" + str(value) + "'"
		return ret_string


	def getsql(self):
		res =[]
		for name,value in zip(self.names, self.values):
			ret = ''
			ret = "insert into connection_log_details(connection_log_id, name, value, login_time, logout_time) values("
			ret += "'{0}', ".format(self.connection_log_id)
			ret += "'{0}', ".format(name)
			ret += "'{0}', ".format(value)
			ret += "'{0}', ".format(self.login_time)
			ret += "'{0}');".format(self.logout_time)
			res.append(ret)

		ret_string = ''	
		for item in res:
			ret_string += item +  os.linesep
		return ret_string

class log:
	def __init__(self,login,ip):
		self.method ="GET"
		self.action ="allow"
		self.url = self.make_random_url()
		self.source = ip
		self.login_time = login

	def make_random_url(self):
		ret_string = "http://wwww."
		ret_string += str(random.randint(500, 5000))
		ret_string += ".com/default.php"
		return ret_string

	def getsql(self):
		ret =  "insert into logs(method, action,url,source, login_time) values ("
		ret +=  "'{0}', '{1}', '{2}', '{3}', '{4}');".format(self.method, self.action, self.url, self.source, self.login_time)
		return ret

if __name__ == '__main__':

	
	
	# print "connection_log_id: " + str(u.connection_log_id)
	# print u.login_time
	# print u.logout_time
	# print "user_id: " + str(u.user_id)
	# print "credit used: " + str(u.credit_used)
	print "delete from connection_log;"
	print "delete from connection_log_details;"
	print "delete from logs;"

	users = [(1,'phasan'), (2,'pali'), (3,'pehsan'), (4,'psaeed'), (5, 'phossein'), (6, 'preza'), (7, 'ptt')]
	for user_id,user in users:
		for i in xrange(1,10):
			u = connection_log(user_id, user)
			print u.generate_sql_string()
			next_day()
		reset_date_time()



