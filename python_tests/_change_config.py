def _change_config(database="", user="pcuser", update_ref=False):
	'''This function changes the /config/Database.php script from the REST API
	 It will change the database to which we are connecting

	 ! It won't modify the real REST API used in production !
	 This data generator framework uses a copy of that original API.
	'''
	if update_ref:
		copy_with_structure()

	database_config = """<?php
	class Database {
	  private $host = 'localhost';
	  private $db_name = '""" +database+ """';
	  private $username = '"""+user+ """';
	  private $password = 'Admin1234!';
	  private $conn;

	  public function connect() {
		$this->conn = null;
		try {
		  $this->conn = new PDO ('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
		  $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		} catch (PDOException $e) { echo 'Error at connection. ' . $e->getMessage();}
		return $this->conn;
	  }
	}
	?>
	 """
	try:
		with open("/var/www/html/backend_code/python_tests/copy_REST_API/config/Database.php",'w') as f:
			f.write(database_config)
	except:
		print("Error at open Database.php")


def copy_with_structure(source="/var/www/html/backend_code", destination="/var/www/html/backend_code/python_tests/copy_REST_API"):
    '''Copies all files and the folder structure from source to destination'''
    import os

    fileCrawler = os.walk(source)
    for dirpath, dirnames, filenames in fileCrawler:#copy the folder structure
        if(len(dirnames) == 0):#we are in the last folder 
            folderStructure = dirpath.replace(source, '')#keep basename of structure
            folderStructure = os.path.normpath(folderStructure)
            try:
                os.makedirs(destination+folderStructure)
            except FileExistsError:
                print("FileExistsError\n")
                return
            except:
                #print("Error\n")
                return
    #copy files:
    fileCrawler = os.walk(source) # reset file crawler
    for dirpath, dirnames, filenames in fileCrawler:
        for i in filenames:
            #calculete file destination
            #print(dirpath+'\\'+i)
            destination_to = dirpath.replace(source, destination)
            _copyFileToLocation(dirpath+'\\'+i, destination_to)

def _copy_file_to_location(pathSource, pathDest, overwrite=True):
    '''To be called by copy_with_structure function!
    This method copies a file from pathSource to pathDest and it overwrites that file it the atribute self.overwrite is True, otherwise it dose not'''
    import shutil
    import os
    if overwrite == True:
        try:
            shutil.copy2(pathSource, pathDest)
        except Exception as e:
            print("Error at copy: {}  {}".format(e, pathSource))
            return
    else:#if file exists at destination don't copy
        if os.path.exists(pathDest+'\\'+os.path.basename(pathSource)):
            print("File exists, {}".format(pathDest+'\\'+os.path.basename(pathSource)))
            return
        else:
            try:
                shutil.copy2(pathSource, pathDest)
            except Exception as e:
                #print("Error at copy.{}  {}".format(e,pathSource))
                return