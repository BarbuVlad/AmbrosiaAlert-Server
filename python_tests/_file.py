if __name__=='__main__':
	'''Do not use this in producton. Delete this file! '''
	print("file start\n")
	from database_conn import database
	try:
		db = database("ambrosia3")
		c= db.read_users()[0]
		print(c)
		print(type(c))
	except Exception as e:
		print(e)

	