import mysql.connector
from mysql.connector import errorcode
class database():
	def __init__(self, db_name="ambrosia3"):
		self.config = {
		'user': 'pcuser1',
		'password': 'Admin1234!',
		'host': 'localhost'
		}
		self._errors=0
		try:#connect to MySQL server
			db = mysql.connector.connect(**self.config)
			#create or connect to database
			db.cursor().execute("CREATE DATABASE IF NOT EXISTS {} DEFAULT CHARACTER SET 'utf8'".format(db_name))
			db.commit()

			db.close()

			self.config['database'] = db_name
			#connect to this database
			self.db = mysql.connector.connect(**self.config) #closed in destructor
			self.cursor = self.db.cursor()
			print("Connection has been established! At database:{}\n".format(db_name))

		except mysql.connector.Error as e:
			print("Error at connection EXIT...\n. ERROR:{}\n".format(e))
			if e.errno == errorcode.ER_ACCESS_DENIED_ERROR:
				print("Something is wrong with your user name or password")
			elif e.errno == errorcode.ER_BAD_DB_ERROR:
				print("Database does not exist")
			self._errors+=1


	def __del__(self):
		try:
			self.db.close()
			print("Connection closed!\n")
		except:
			print("Error at close!\n")
			self._errors+=1
		
	def init_database(self):
		'''For a new database, it creates all generic tables from DB ERD '''
		import generic_structure
		try:
			for sql in generic_structure.generic:
				self.cursor.execute(sql)
				self.db.commit()
		except:
			print("Error at generating generic structure: self.init_database()\n")
			self._errors+=1

	# DELETES -------------------------------------------------------------------------------------------------------------------------------------------
	def drop_database(self):
		'''Drops the currently selected database '''
		try:
			self.cursor.close()
			cursor=self.db.cursor()
			cursor.execute("DROP DATABASE {}".format(self.config['database']))
			self.db.commit()
			print("Database: {} droped!".format(self.config['database']))
			#cursor.close()
		except Exception as e:
			print("Error at dropping database.\n ERROR: {}\n".format(e))
			self._errors+=1

	def delete_data(self, table, condition="1"):
		'''Deletes all data from the specified table (with respect to the condition) '''
		try:
			sql = "DELETE FROM {table} WHERE {condition}".format(table=table, condition=condition)
			self.cursor.execute(sql)
			self.db.commit()
			print("Deleted data from: {}. Condition: {}".format(table, condition))

		except Exception as e:
			print("Error at deleting from table {}.\n ERROR: {}\n".format(table,e))
			self._errors+=1

	def delete_all_data(self):
		'''Deletes all data from ALL tables'''
		sql = """select table_name from information_schema.tables where table_type = 'BASE TABLE' and table_schema = 
		"{}" """.format(self.config['database'])
		try:
			self.cursor.execute(sql)
			tables = self.cursor.fetchall()
			for table in tables:
				table = str(table[0])
				self.delete_data(table)
			self.db.commit()
		except Exception as e:
			print("Error at deleteing all data. ERROR:{}".format(e))
			self._errors+=1


	# INSERTS -------------------------------------------------------------------------------------------------------------------------------------------
	def insert_row(self, table, **columns):
		#'''Inserts a row in the specified table in the specified columns '''
		cols = "("
		for key in columns.keys():
			cols = cols + str(key) + ", "

		cols = cols[:-2] + ")"

		vals = "("
		for key in columns.values():
			vals = vals + str(key) + ", "
		vals = vals[:-2] + ")"

		sql = "INSERT INTO {table}{cols} VALUES {values}".format(table=table, cols=cols, values=values)
		try:
			self.cursor.execute(sql)
			self.db.commit()

		except:
			print("Error at INSERT in table: {}".format(table))
			self._errors+=1

	def insert_user(self, mac_user="NULL", blocked="NULL"):
		'''Inserts a user'''
		sql = """INSERT INTO users(mac_user, blocked) VALUES("{mac_user}",{blocked})""".format(mac_user=mac_user, blocked=blocked)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT user \n. ERROR:{}".format(e))
			self._errors+=1

	def insert_volunteer(self,phone="123", email="123@test.com", password="123",
	first_name="NULL", last_name="NULL", blocked="NULL"):
		'''Inserts a volunteer '''
		sql = """INSERT INTO volunteers(phone, email, password, first_name, last_name, blocked) 
		VALUES("{phone}","{email}","{password}", "{first_name}", "{last_name}", {blocked} )""".format(phone=phone, email=email, password=password, first_name=first_name, last_name=last_name, blocked=blocked)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT volunteer \n. ERROR:{}".format(e))
			self._errors+=1

	def insert_new_volunteer(self,phone="123", email="123@test.com", password="123",
	first_name="NULL", last_name="NULL", blocked="NULL", confirmations="0"):
		'''Inserts a new_volunteer '''
		sql = """INSERT INTO new_volunteers(phone, email, password, first_name, last_name, blocked, confirmations) 
		VALUES("{phone}","{email}","{password}", "{first_name}", "{last_name}", {blocked}, {confirmations} )""".format(phone=phone, email=email, password=password, first_name=first_name, last_name=last_name, blocked=blocked, confirmations=confirmations)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT new_volunteer \n. ERROR:{}".format(e))
			self._errors+=1


	def insert_blue_marker(self, latitude, longitude, uid_user="NULL", time="NULL"):
		'''Inserts a blue marker '''
		sql = """INSERT INTO blue_markers(latitude, longitude, uid_user, time) 
		VALUES({latitude}, {longitude}, {uid_user}, "{time}")""".format(latitude=latitude, longitude=longitude, uid_user=uid_user, time=time)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT blue_marker \n. ERROR:{}".format(e))
			self._errors+=1

	def insert_red_marker(self, latitude, longitude, uid_volunteer="NULL", time="NULL", confirmations="0", radius="50"):
		'''Inserts a red marker '''
		sql = """INSERT INTO red_markers(latitude, longitude, uid_volunteer, time, confirmations, radius) 
		VALUES({latitude}, {longitude}, {uid_volunteer}, "{time}", {confirmations}, {radius})""".format(latitude=latitude, longitude=longitude, uid_volunteer=uid_volunteer, time=time, confirmations=confirmations, radius=radius)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT red_marker \n. ERROR:{}".format(e))
			self._errors+=1


	def insert_yellow_marker(self, latitude, longitude, uid_volunteer="NULL", time="NULL"):
		'''Inserts a yellow marker '''
		#print("Inserting yellow marker...")
		sql = """INSERT INTO yellow_markers(latitude, longitude, uid_volunteer, time) 
		VALUES({latitude}, {longitude}, {uid_volunteer}, "{time}")""".format(latitude=latitude, longitude=longitude, uid_volunteer=uid_volunteer, time=time)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT yellow_marker \n. ERROR:{}".format(e))
			self._errors+=1


	def insert_grey_marker(self, latitude, longitude, uid_volunteer="NULL", time="NULL"):
		'''Inserts a red marker '''
		sql = """INSERT INTO grey_markers(latitude, longitude, uid_volunteer, time) 
		VALUES({latitude}, {longitude}, {uid_volunteer}, "{time}")""".format(latitude=latitude, longitude=longitude, uid_volunteer=uid_volunteer, time=time)
		try:
			self.cursor.execute(sql)
			self.db.commit()
		except Exception as e:
			print("Error at INSERT grey_marker \n. ERROR:{}".format(e))
			self._errors+=1

	# READS -------------------------------------------------------------------------------------------------------------------------------------------
	def read_users(self, condition="1"):
		cursor=self.db.cursor()

		sql = """SELECT * FROM users WHERE {}""".format(condition)

		try:
			cursor.execute(sql)
		except Exception as e:
			print("ERROR at read user(s). ERROR: {}\n".format(e))
			self._errors+=1

		return tuple(cursor)
		
	def read_volunteers(self, condition="1"):
		cursor=self.db.cursor()

		sql = """SELECT * FROM volunteers WHERE {}""".format(condition)

		try:
			cursor.execute(sql)
		except Exception as e:
			print("ERROR at read volunteer(s). ERROR: {}\n".format(e))
			self._errors+=1

		return tuple(cursor)
		
	def read_new_volunteers(self, condition="1"):
		cursor=self.db.cursor()

		sql = """SELECT * FROM new_volunteers WHERE {}""".format(condition)

		try:
			cursor.execute(sql)
		except Exception as e:
			print("ERROR at read new_volunteer(s). ERROR: {}\n".format(e))
			self._errors+=1

		return tuple(cursor)

	def read_blue_markers(self, condition="1"):
		cursor=self.db.cursor()

		sql = """SELECT * FROM blue_markers WHERE {}""".format(condition)

		try:
			cursor.execute(sql)
		except Exception as e:
			print("ERROR at read blue_marker(s). ERROR: {}\n".format(e))
			self._errors+=1

		return tuple(cursor)

	def read_red_markers(self, condition="1"):
		cursor=self.db.cursor()

		sql = """SELECT * FROM red_markers WHERE {}""".format(condition)

		try:
			cursor.execute(sql)
		except Exception as e:
			print("ERROR at read red_marker(s). ERROR: {}\n".format(e))
			self._errors+=1

		return tuple(cursor)

	def read_yellow_markers(self, condition="1"):
		cursor=self.db.cursor()

		sql = """SELECT * FROM yellow_markers WHERE {}""".format(condition)

		try:
			cursor.execute(sql)
		except Exception as e:
			print("ERROR at read yellow_marker(s). ERROR: {}\n".format(e))
			self._errors+=1

		return tuple(cursor)

	def count_table(self, table):
		cursor=self.db.cursor()

		sql = """SELECT COUNT(*) FROM {}""".format(table)

		try:
			cursor.execute(sql)
			c = tuple(cursor)[0][0]
			return c
		except Exception as e:
			print("ERROR at read count {}. ERROR: {}\n".format(table,e))
			self._errors+=1

#------------------------------------------------------------------------
	def _sql_command(self, sql="", commit=False):
		cursor=self.db.cursor()

		try:
			cursor.execute(sql)
			if commit:
				self.db.commit()
			return cursor
		except Exception as e:
			print("Error at custom sql command. ERROR: {}".format(e))
			self._errors+=1