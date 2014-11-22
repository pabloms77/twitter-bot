import urllib2
content = urllib2.urlopen("http://rootdroid.net/searchTweets.php").read()
print content