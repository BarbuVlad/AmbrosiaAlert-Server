if __name__ == "__main__":
	'''This script tests the database class. Very basic tests'''
	from database_conn import database
	print("Start\n")

	errors=0
	#instanciate a new database
	db = database(db_name="ambrosia__test__")
	
	#initialiaze the strucuture, no data
	db.init_database()

	#test for inserts---------------------------------------
	db.insert_user()
	db.insert_user()
	users = db.count_table("users")
	if users != 2:
		print("-->Users NOT inserted")
		errors+=1
	elif users==2:
		print("-->2 users inserted successfully!")

	db.insert_volunteer()
	db.insert_volunteer()
	volunteers = db.count_table("volunteers")
	if volunteers != 2:
		print("-->volunteers NOT inserted")
		errors+=1
	elif volunteers==2:
		print("-->2 volunteers inserted successfully!")

	db.insert_new_volunteer()
	db.insert_new_volunteer()
	new_volunteers = db.count_table("new_volunteers")
	if new_volunteers != 2:
		print("-->new_volunteers NOT inserted")
		errors+=1
	elif new_volunteers==2:
		print("-->2 new_volunteers inserted successfully!")

	db.insert_red_marker(latitude="10.123",longitude="10.123")
	db.insert_red_marker(latitude="11.123",longitude="11.123")
	red_markers = db.count_table("red_markers")
	if red_markers != 2:
		print("-->red_markers NOT inserted")
		errors+=1
	elif red_markers==2:
		print("-->2 red_markers inserted successfully!")

	db.insert_yellow_marker(latitude="10.123",longitude="10.123")
	db.insert_yellow_marker(latitude="11.123",longitude="11.123")
	yellow_markers = db.count_table("yellow_markers")
	if yellow_markers != 2:
		print("-->yellow_markers NOT inserted")
		errors+=1
	elif yellow_markers==2:
		print("-->2 yellow_markers inserted successfully!")

	db.insert_blue_marker(latitude="10.123",longitude="10.123")
	db.insert_blue_marker(latitude="11.123",longitude="11.123")
	blue_markers = db.count_table("blue_markers")
	if blue_markers != 2:
		print("-->blue_markers NOT inserted")
		errors+=1
	elif blue_markers==2:
		print("-->2 blue_markers inserted successfully!")

	print("\n\n Read:\n")
	#test for reads--------------------------------------------------------

	print("Users:")
	users = db.read_users()
	for x in users:
		print (x)
	print("\n")

	print("Volunteers:")
	volunteers = db.read_volunteers()
	for x in volunteers:
		print (x)
	print("\n")

	print("New volunteers:")
	new_volunteers = db.read_new_volunteers()
	for x in new_volunteers:
		print (x)
	print("\n")

	print("blue_markers:")
	blue_markers = db.read_blue_markers()
	for x in blue_markers:
		print (x)
	print("\n")

	print("red_markers:")
	red_markers = db.read_red_markers()
	for x in red_markers:
		print (x)
	print("\n")

	print("yellow_markers:")
	yellow_markers = db.read_yellow_markers()
	for x in yellow_markers:
		print (x)
	print("\n\n Delete:\n")

	#test for deletes:-----------------------------------------------------------------
	db.delete_data(table="users")
	users = db.count_table("users")
	if users != 0:
		print("-->Users NOT deleted")
		errors+=1
	elif users==0:
		print("-->2 users deleted successfully!")

	db.delete_all_data()

	volunteers = db.count_table("volunteers")
	new_volunteers = db.count_table("new_volunteers")
	red_markers = db.count_table("red_markers")
	yellow_markers = db.count_table("yellow_markers")
	blue_markers = db.count_table("blue_markers")

	if volunteers==new_volunteers==red_markers==yellow_markers==blue_markers==0:
		print("-->All data deleted successfully!")
	else:
		print("-->All data NOT deleted")
		errors+=1

	db.drop_database()

	print("\n\n__________________________________________________________________________________")
	print("-->Test completed! Errors from this script:{}. Errors thrown by the class: {}".format(errors, db._errors))
	