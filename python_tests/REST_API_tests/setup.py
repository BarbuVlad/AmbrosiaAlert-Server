
def setup():
	print("Creating a test database...")
	from database_conn import database
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
	return db