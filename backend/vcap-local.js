// This module helps to run the application in local development environment
// by faking CloudFoundry's VCAP_SERVICES environment variable
process.env.VCAP_SERVICES = process.env.VCAP_SERVICES || JSON.stringify({

	"cloudantNoSQLDB": [
		{
			label: "cloudantNoSQLDB",
			name: "Cloudant NoSQL DB-h1",
			credentials: {
				"password": "577a25f9a937896c0f9a19cf8344aee521457cb8f725454bac94ca0c34e8f15a",
				"url": "https://3cd789c3-1cc0-462a-a94f-5639611eb660-bluemix:577a25f9a937896c0f9a19cf8344aee521457cb8f725454bac94ca0c34e8f15a@3cd789c3-1cc0-462a-a94f-5639611eb660-bluemix.cloudant.com",
				"username": "3cd789c3-1cc0-462a-a94f-5639611eb660-bluemix"
			}
		}
	],

});

