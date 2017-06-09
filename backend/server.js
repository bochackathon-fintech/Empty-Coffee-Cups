const cfenv = require('cfenv');
const express = require('express');
const bodyParser = require("body-parser");
const app = express();
app.use(bodyParser.json());
const Cloudant = require('cloudant');

// Emulating VCAP_VARIABLES if running in local mode
try { require("./vcap-local"); } catch (e) {}
var appEnv = cfenv.getAppEnv();



// AppMetrics monitoring instrumentation
require('appmetrics-dash').attach();

// Cloudant instrumentation
var cloudant = Cloudant(appEnv.services['cloudantNoSQLDB'][0].credentials);
var cloudantDb = cloudant.db.use("clients");

// Swagger instrumentation
app.use("/swagger/api", express.static("./public/swagger.yaml"));
app.use("/explorer", express.static("./public/swagger-ui"));

// Business logic
app.get("/customers", function(req, res, next){
	var responseData    = [];

	cloudantDb.view("customers","customers",function(err, body){
		if (!err){
			body.rows.forEach(function(doc){
				responseData.push(doc);
			});
			res.json(responseData);
		}else{
			console.log("Failed to connect to database.");
		}
	});

});

app.post("/customers", function(req, res, next){
	// Put your business logic here
	res.json();
});



app.delete("/customer/:id", function(req, res, next){
	// Put your business logic here
	res.json();
});

app.get("/customers/:id", function(req, res, next){
	// Put your business logic here
	res.json();
});

app.get("/customer/:id/goals", function(req, res, next){
	// Put your business logic here
	res.json();
});

app.post("/customers/:id/goals", function(req, res, next){
	// Put your business logic here
	res.json();
});

app.delete("/customers/:id/goals", function(req, res, next){
	// Put your business logic here
	res.json();
});


// Starting the server
const port = 'PORT' in process.env ? process.env.PORT : 8080;
app.listen(port, function () {
	const address = (this.address().address === '::') ? 'http://localhost' : this.address().address;
	const port = this.address().port;
	console.log(`testbe listening on ${address}:${port}`)
	console.log(`OpenAPI (Swagger) spec is available at ${address}:${port}/swagger/api`)
	console.log(`Swagger UI is available at ${address}:${port}/explorer`)
});
