import mysql.connector

mydb = mysql.connector.connect(
  host="localhost",
  user="pcuser",
  passwd="Admin1234!",
  database="ambrosia3"
)

mycursor = mydb.cursor()

mycursor.execute("SELECT * FROM blue_markers")

myresult = mycursor.fetchall()
print(type(myresult))
print(myresult[1])
print("--\n")
for x in myresult:
  print(x)
