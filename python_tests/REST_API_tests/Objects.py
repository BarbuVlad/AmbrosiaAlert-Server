from abc import ABC
class Objects(ABC):
	'''All functions are designed to be a black-box testing.
	That means that we are setting up a database with all necessary  data.
	After this setup a certain request is made to the API.

	!RULE: all the seting up and mock-data will be made using
	the testing Framework, in a CRUD test we only call the API once
	for that specific test. 
	EX.: if you test for read, never call the API to create data,
	use the testing framework instead'''

	def __init__(db,ip='92.87.91.16'):
		self.db=db
		self.ip=ip

	def read():
		'''This function  '''

	def read_single():
		pass

	def create():
		pass

	def update():
		pass

	def delete():
		pass

	def login():
		pass