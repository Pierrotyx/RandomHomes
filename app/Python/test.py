import requests
import sys
import random
import json

url = 'https://zillow-com1.p.rapidapi.com/propertyExtendedSearch'

querystring = json.loads( sys.argv[1].replace("\\", "") )

headers = {
	"X-RapidAPI-Key": "2685366d4cmshbf587d1a8086269p165433jsn9e400cd19841",
	"X-RapidAPI-Host": "zillow-com1.p.rapidapi.com"
}

response = requests.get(url, headers=headers, params=querystring)
data = response.json()
if "props" in data:
    results = data["props"]
    addresses = []
    for result in results:
        if result.get( "detailUrl" ):
            addresses.append( { "url" : 'https://www.zillow.com' + result.get( "detailUrl" ), "data" : result } )
        else:
            url = "https://www.zillow.com/homedetails/"
            address = result.get("address").replace(",", "").replace("#", "").replace(" ", "-") + "/"
            zpid = result.get("zpid") + "_zpid/"
            addresses.append( { "url" : url + address + zpid, "data" : result } )
    if len(addresses) == 0:
        sys.exit()
    print(json.dumps(random.choice(addresses)))

