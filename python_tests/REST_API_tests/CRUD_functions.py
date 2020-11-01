import requests
def post(link, *data):
	#makes 2 requests, returns as list [req1, req2]
	req=[]
	link_tls=link[:4]+'s'+link[4:]
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

def get(link):
	'''Makes a request to the API link (both TLS and non-TLS).
	It returns a request object (see requests lib)'''
	r=None
	link_tls=link[:4]+'s'+link[4:]
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

def put(link, data):
	#makes 2 requests, returns as list [req1, req2]
	req=[]
	link_tls=link[:4]+'s'+link[4:]
	try:
		r=requests.put(link, data=data)
	except:
		print("(!)ERROR at {}".format(link))
	finally:
		req.append(r)

	try:
		r=requests.put(link_tls, data=data)
	except:
		print("(!)ERROR at {}".format(link_tls))
	finally:
		req.append(r)

	return req

def delete(link, *data):
	#makes 2 requests, returns as list [req1, req2]
	req=[]
	link_tls=link[:4]+'s'+link[4:]
	try:
		r=requests.delete(link, data=data[0])
	except:
		print("(!)ERROR at {}".format(link))
	finally:
		req.append(r)

	try:
		r=requests.delete(link_tls, data=data[1])
	except:
		print("(!)ERROR at {}".format(link_tls))
	finally:
		req.append(r)

	return req