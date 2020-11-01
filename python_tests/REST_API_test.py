'''TO DO: connect to the copy API, not the production ready-api (to change the config/Database.php connection) 
[see doc. for this framework]
'''
from database_conn import database
from time import time

start_time=time()
errors=0#number of failed requests
results={}#key-value like "table action": False/True

print("_____________________TEST REST API_____________________\n")
#---------------------------SETUP-------------------------
#create/connect to a test database
print("\t\t\t...Creating a test database...")

from random import randint
#randomize name of db
db_name="_test_ambrosia"
for i in range(0,5):
	db_name+=str(randint(0,9))

try:
	db=database(db_name=db_name)
	db.init_database()
except:
	print("Error at creating database. EXIT")
	exit(1)#setup fail

#copy the REST API strucuture:
import _change_config
try:
	_change_config.copy_with_structure()
	_change_config._change_config(database=db_name, user="pcuser1")
	print("Config file created.")
except:
	print("Error at config file setup. EXIT")
	exit(2)

print("Database: {} created!".format(db_name))
#--------------------------------------------------------
#---------------------------POST-------------------------
print("\n\t\t\t->Testing for POST...\n")
import requests
def post(link,link_tls, *data):
	#makes 2 requests, returns as list [req1, req2]
	req=[]
	try:
		r=requests.post(link, data=data[0])
	except:
		print("(!)ERROR at {}".format(link))
	finally:
		req.append(r)

	try:
		r=requests.post(link_tls, data=data[1])
	except:
		print("(!)ERROR at {}".format(link_tls))
	finally:
		req.append(r)

	return req
#call post for all tables
print("--Users--")
data_users=[{"mac_user": "00-00-00-00-00-00"},{"mac_user": "11-11-11-11-11-11"}]
users_post=post("http://92.87.91.16/backend_code/api/user/create.php",
	 "https://92.87.91.16/backend_code/api/user/create.php",
	 data_users[0],data_users[1])

print("--Volunteers--")
data_volunteers=[{
				"phone": "test-val",
				"email": "test-val",
				"password": "test-val",
				"first_name": "test-val",
				"last_name": "test-val",
				"address": "test-val"
	},{
				"phone": "test-val2",
				"email": "test-val2",
				"password": "test-val2",
				"first_name": "test-val2",
				"last_name": "test-val2",
				"address": "test-val2"
	}]
volunteers_post=post("http://92.87.91.16/backend_code/api/volunteer/create.php",
				"https://92.87.91.16/backend_code/api/volunteer/create.php",
				data_volunteers[0],data_volunteers[1])


print("--New_Volunteers--")
data_new_volunteers=[{
				"phone": "test-val_new",
				"email": "test-val_new",
				"password": "test-val_new",
				"first_name": "test-val_new",
				"last_name": "test-val_new",
				"address": "test-val_new"
	},{
				"phone": "test-val_new2",
				"email": "test-val_new2",
				"password": "test-val_new2",
				"first_name": "test-val_new2",
				"last_name": "test-val_new2",
				"address": "test-val_new2"
	}]
new_volunteers_post=post("http://92.87.91.16/backend_code/api/new_volunteer/create.php",
					"https://92.87.91.16/backend_code/api/new_volunteer/create.php",
					data_new_volunteers[0],data_new_volunteers[1])

print("--Admins--")
data_admins=[	{
		"name": "adminX",
		"password": "123"
	},	{
		"name": "adminY",
		"password": "123"
	}]
admins_post=post("http://92.87.91.16/backend_code/api/admins/read.php",
			"https://92.87.91.16/backend_code/api/admins/read.php",
			data_admin[0],data_admin[1])

#---Verify data
print("\n\t\t\t->Testing for READ (part1)...\n")
print("-->READ data for: users, volunteers, new_volunteers, admins...--")
def read(link, lint_tls):
	r=None
	try:
		r = requests.get(link)
	except:
		print("(!)ERROR at {}".format(link))
	try:
		r = requests.get(link_tls)
	except:
		print("(!)ERROR at {}".format(link_tls))
	return r
	#c=str(r.content,'utf-8')
	#from json import loads
	#e=loads(c)
	#print(e["data"])

users_read=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/user/read.php","https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/user/read.php")
volunteers_read=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/volunteer/read.php","https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/volunteer/read.php")
new_volunteers_read=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/new_volunteer/read.php","https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/new_volunteer/read.php")
admins_read=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/admins/read.php","https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/admins/read.php")
print("-->Test done! See the report for details.")

print("\n\t\t\t->Testing for READ_SINGLE (part1)...\n")
print("-->READ data for: users, volunteers, new_volunteers, admins...--")

user_uid=db.read_users()[0][0] #get uid
volunteer_uid=db.read_volunteers()[0][0] #get uid
new_volunteer_uid=db.read_new_volunteers()[0][0] #get uid
admin_uid=db.read_admins()[0][0] #get uid

users_read_single=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/user/read.php?uid={}".format(user_uid),
				"https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/user/read.php")

volunteers_read_single=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/volunteer/read.php",
					 "https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/volunteer/read.php")

new_volunteers_read_single=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/new_volunteer/read.php",
						 "https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/new_volunteer/read.php")

admins_read_single=read("http://92.87.91.16/backend_code/python_tests/copy_REST_API/api/admins/read.php",
					"https://92.87.91.16/backend_code/python_tests/copy_REST_API/api/admins/read.php")

print("-->Test done! See the report for details.")