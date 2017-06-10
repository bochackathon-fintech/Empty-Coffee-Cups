import httplib, subprocess

c = httplib.HTTPConnection('localhost', 8081)
c.request('POST', '/return', '{}')
doc = c.getresponse().read()
print doc
