from .Objects import Objects
from .CRUD_functions import *
from .data import *
class Users(Objects):
	'''

	'''

	def __init__(db, ip):
		super().__init__(db, ip)
		##--------------------------------------------------------------------------------------------------------------
		from database_conn import database
		self.db=database()
		##--------------------------------------------------------------------------------------------------------------
		self.link_read='http://'+self.ip+'/backend_code/python_tests/copy_REST_API/api/user/read.php'
		self.link_read_single='http://'+self.ip+'/backend_code/python_tests/copy_REST_API/api/user/read_single.php?uid='
		self.link_create='http://'+self.ip+'/backend_code/python_tests/copy_REST_API/api/user/create.php'
		self.link_update='http://'+self.ip+'/backend_code/python_tests/copy_REST_API/api/user/update.php'
		self.link_delete='http://'+self.ip+'/backend_code/python_tests/copy_REST_API/api/user/delete.php'

	def read():
		#create data 
		self.db.insert_user(data_users[0])
		self.db.insert_user(data_users[1])
		#call API function 
		req=get(self.link_read)
		

		#Deliver results