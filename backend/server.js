const cfenv = require('cfenv');
const express = require('express');
const bodyParser = require("body-parser");
const app = express();
app.use(bodyParser.json());
const Cloudant = require('cloudant');
var http = require('http')




var CronJob = require("cron").CronJob

// Emulating VCAP_VARIABLES if running in local mode
try { require("./vcap-local"); } catch (e) {}
var appEnv = cfenv.getAppEnv();


/// Static magic numbers for BOC

const bocviewuuid = '5710bba5d42604e4072d1e92'
const bocuuid = 'bda8eb884efcef7082792d45'
const bocapikey = 'f62cecf9b2f248f3bb0059fad858949c'
const bocurl = 'api.bocapi.net'
const bocurlpath = '/v1/api/'



/// Static magic numbers for PLAID




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

				var saved = 0;
        doc.goals  = doc.goals.filter(function(el) {
					  if (el.name == req.query.name){
							saved = el.saved;
						}
            return el.name !== req.query.name;
        });

			 doc.goals = updateGoals(doc.goals,saved);

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

new CronJob("*/5 * * * * *", everyXseconds(5), null, true);

//------------------------------------------------------------------------------
function everyXseconds(seconds) {
    return function() {
        console.log(new Date() + ": another " + seconds + " seconds have passed!");

        getAccountValue('','','','');
        return;
        cloudantDb.fetch({}, function(err, body) {
            var docs = body.rows.map(function(row) {
                if (row.doc != null){
                    return row.doc;
                }
            })

            docs.forEach(function(doc){

                var limitRoundup = doc.limitRoundup;
                var montlyFixedAmmount = doc.montlyFixedAmmount;
                var bankuuid = doc.bankuuid;
                var bankview = doc.bankview;
                var bankid = doc.bankid;

                var acctoday = 2120;

                goals = doc.goals;

                if (goals == null) return;

                goals = updateGoals(goals,acctoday);

                doc.goals = goals;
                cloudantDb.insert(doc, function (er, result) {
                    if (er) {
                        throw er;
                    }
                });
            });
        });
        return;
    }
}

//------------------------------------------------------------------------------

function updateGoals(goals,acctoday){
					if (goals == null) return null;
					if (acctoday == 0) return goals;

					acctoday = parseFloat(acctoday);

					var arrayLength = goals.length;
					for (var i = 0; i < arrayLength; i++) {

						if ( parseFloat(goals[i].saved) >= parseFloat(goals[i].value)) continue;

						if ( parseFloat(goals[i].value) < (acctoday+parseFloat(goals[i].saved)) ){

							var diff = (parseFloat(goals[i].value)  - parseFloat(goals[i].saved));
							goals[i].saved = goals[i].value;
							acctoday = acctoday - diff;
							continue;
						}
						goals[i].saved = acctoday + parseFloat(goals[i].saved);
						acctoday = 0;
					}
					return goals;
}

//------------------------------------------------------------------------------


function getAccountValue(bankid,accountid,viewid,authid){

    var body = JSON.stringify({
        BANK_ID : bocuuid,
        ACCOUNT_ID : 'a746637b91b19a261a67d8bd',
        VIEW_ID : bocviewuuid
    });

    var vpath = bocurlpath + "banks/" + bocuuid + "/accounts/a746637b91b19a261a67d8bd/5710bba5d42604e4072d" +"/account"
    var request = new http.ClientRequest({
        hostname: bocurl,
        port: 80,
        path: vpath,
        method: "GET",
        headers: {
            "Auth-Provider-Name":"01460900080600",
            "Auth-ID":"123456789",
            "Ocp-Apim-Subscription-Key":"f62cecf9b2f248f3bb0059fad858949c",
            "Content-Type": "application/json",
            "Content-Length": Buffer.byteLength(body)
        }
    },
    function(res) {

        res.setEncoding('utf8');
        res.on('data', function (data) {
            console.log("data:"+ data); 
        });
        res.on('end', function (data) {
            console.log("end: " + data); 
        });
        res.on('error', function(data) {
              console.log('problem with request: ' + data);
        });
    }

    
    );




}




