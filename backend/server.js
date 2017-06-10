const cfenv = require('cfenv');
const express = require('express');
const bodyParser = require("body-parser");
const app = express();
app.use(bodyParser.json());
const Cloudant = require('cloudant');


var CronJob = require("cron").CronJob

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

        cloudantDb.insert({ 'birth': req.query.birth, 'goals':[], "registrationdate": req.query.registrationdate, "expectedincome": req.query.expectedincome}, function (er, result) {
            if (er) {
                throw er;
            }
        });

	res.json();
});

app.delete("/customer/:id", function(req, res, next){
        console.log(req.params.id);

	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
        cloudantDb.fetch({keys: [idn.id]}, function(err, body) {
            var doc = body.rows.map(function(row) {
                if (row.doc != null){
                    return row.doc;
                }
            });

            doc = doc[0];

            cloudantDb.destroy(req.params.id,doc._rev,function(err, body) {
                if (!err)
                    console.log(body);
            });

   })


	res.json();
});

app.get("/customer/:id", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
        cloudantDb.fetch({keys: [idn.id]}, function(err, body) {
            var doc = body.rows.map(function(row) {
                if (row.doc != null){
                    return row.doc;
                }
            })
	res.json(doc);
   })
});

app.get("/customer/:id/goals", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
	cloudantDb.fetch({keys: [idn.id]}, function(err, body) {
		var goals = body.rows.map(function(row) {
			if (row.doc != null){
	    	return row.doc.goals;
			}
	  })
	res.json(goals);
   })
});

app.post("/customer/:id/goals", function(req, res, next){


    var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
    cloudantDb.fetch({keys: [idn.id]}, function(err, body) {
        var doc = body.rows.map(function(row) {
            if (row.doc != null){
                return row.doc;
            }
        });


        doc = doc[0];


console.log(req.query);

        doc.goals.push({
            "id":  req.query.id,
            "name":  req.query.name,
            "value":  req.query.value,
            "saved":  req.query.saved,
            "date":  req.query.date,
            "accountid":  req.query.accountid,
            "priority":  req.query.priority}
            );

        cloudantDb.insert(doc, function (er, result) {
            if (er) {
                throw er;
            }
        });


    });


    res.json();
});

app.delete("/customer/:id/goals", function(req, res, next){

    var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
    cloudantDb.fetch({keys: [idn.id]}, function(err, body) {
        var doc = body.rows.map(function(row) {
            if (row.doc != null){
                return row.doc;
            }
        })
        doc = doc[0];

        doc.goals  = doc.goals.filter(function(el) {
            return el.name !== req.query.name;
        });

        cloudantDb.insert(doc, function (er, result) {
            if (er) {
                throw er;
            }
        });


    });

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


// set up cron jobs
console.log("setting up cron jobs");

new CronJob("*/10 * * * * *", everyXseconds(10), null, true);

//------------------------------------------------------------------------------
function everyXseconds(seconds) {
    return function() {
        console.log(new Date() + ": another " + seconds + " seconds have passed!");
				cloudantDb.view("customers","customers",function(err, body){
					if (!err){
						body.rows.forEach(function(doc){
							console.log(doc);
				
						});
					}else{
						console.log("Failed to connect to database.");
					}
				});
    }
}
