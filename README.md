# Empty-Coffee-Cups



====== Backend =======

1. Enter backend folder
2. write: npm install
3. write: npm start
4. Visit localhost to access the API


NOTES:

1. To connect with the Cloudant NoSQL database you need to create a vcap-local.js that includes:


process.env.VCAP_SERVICES = process.env.VCAP_SERVICES || JSON.stringify({

	"cloudantNoSQLDB": [
		{
			label: "cloudantNoSQLDB",
			name: "Cloudant NoSQL DB-h1",
			credentials: {
				"password": "x",
				"url": "https://yyyy,
				"username": "zzzzz"
			}
		}
	],

});

Alternative you can download the example project and copy the file.



2. You must create a view using the web interface of the databe named "customers" with map function:


function (doc) {
  emit(doc._id, {birth: doc.birth, goals: doc.goals, registrationdate: doc.registrationdate, expectedincome: doc.expectedincome});

}



