const cfenv = require('cfenv');
const express = require('express');
const bodyParser = require("body-parser");
const app = express();
app.use(bodyParser.json());
const Cloudant = require('cloudant');
var http = require('http')
const plaid = require('plaid');
var moment = require('moment');
var fs = require('fs');


const plaidClient = new plaid.Client("59357581bdc6a401d71d8525",
                                     "d547b8b3b20443694b62b49534f7ac",
                                     "d6e21b64c3f3acad6465d5ed088295",
                                     plaid.environments.sandbox,
                                     {
                                           timeout: 10 * 60 * 1000, // 30 minutes
                                             agent: 'Patient Agent'
                                     }
                                     );



var CronJob = require("cron").CronJob

// Emulating VCAP_VARIABLES if running in local mode
try { require("./vcap-local"); } catch (e) {}
var appEnv = cfenv.getAppEnv();


/// Static magic numbers for BOC

const bocviewuuid = '5710bba5d42604e4072d1e92'
const bocuuid = 'bda8eb884efcef7082792d45'
const bocapikey = 'f62cecf9b2f248f3bb0059fad858949c'
const bocurl = 'http://api.bocapi.net'
const bocurlpath = '/v1/api/'
const bockey = "442c668542704b6a8e238ff08b17e157"



// AppMetrics monitoring instrumentation
require('appmetrics-dash').attach();

// Cloudant instrumentation
var cloudant = Cloudant(appEnv.services['cloudantNoSQLDB'][0].credentials);
var cloudantDb = cloudant.db.use("clients");

// Swagger instrumentation
app.use("/swagger/api", express.static("./public/swagger.yaml"));
app.use("/explorer", express.static("./public/swagger-ui"));

// Basic NoSql API

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

app.get("/customer/:id/goalsmob", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
	cloudantDb.fetch({keys: [idn.id]}, function(err, body) {
		var goals = body.rows.map(function(row) {
			if (row.doc != null){
	    	return row.doc.goals;
			}
	  })
	res.json(goals[0]);
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
                if (  parseFloat( req.query.value) != parseFloat( req.query.saved) ){
                  saved = el.saved;
                }
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


// Mobile

app.get("/customer/:id/catmob", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
   var ret = [
     {"name": "Income", "value":  1200},
     {"name": "Income", "value":  5000},
     {"name": "Income", "value":  9000}
     ];
    res.json(ret);
});

app.get("/customer/:id/stats", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
   var ret = [
       {"name": "Income", "value":  1200},
       {"name": "Income", "value":  5000},
       {"name": "Income", "value":  9000}
     ];
    res.json(ret);
});

// Profitability AMEX

app.get("/customer/:id/amexprofitability", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
  var transactions = getTransactions();
  var resamex = checkForAmex(transactions);
  res.json(resamex);
});


app.get("/customer/:id/schedulingprofitability", function(req, res, next){
	var idn = req.query._id || req.query.id || req.body._id || req.body.id || req.params;
  var transactions = getTransactions();
  var resamex = getRangeForSaving(transactions);
  res.json(resamex);
});

//------------------------------------------------------------------------------

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


        // does not work
        // getAccountValue('','','','');

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
                var transactions = getTransactions();

                goals = doc.goals;

                if (goals == null) return;

                var acctoday = getAmmountToSaveToday(transactions,0.001);

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

eval(fs.readFileSync('example_trasnactions.js')+'');
function getTransactions(){
  return example_transactions;
}
//------------------------------------------------------------------------------

function getAmmountToSaveToday(transactions,perc){

    perc = parseFloat(perc);

    // hardcoded
    //var date = new Date();
    var datenow = ''

    var ammount = 0;
    for(var i = 0; i < transactions.length; i++) {
      var obj = transactions[i];
          if (obj.transaction<0) {
              ammount += parseFloat(obj.transaction);
          }
    }

    ammount = - ammount*perc;

    return ammount;

}

function checkForAmex(transactions){

    // Must go back to year  or divide by months
    // in our case we have information only for 4 months
    // we have to multiply by 3. In practice we have to calculate
    // the defference between first and last date.

    var date1 = moment(transactions[0].Date, "DD.MM.YYYY HH.mm").toDate();
    var date2 = moment(transactions[transactions.length-1].Date, "DD.MM.YYYY HH.mm").toDate();
    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));

    var ammount = 0;
    var total_ammount = 0;

    for(var i = 0; i < transactions.length; i++) {
      var obj = transactions[i];
          if (obj.transaction<0) {
              if (obj.comments == 'Alphamega Supermarket'){
                ammount += parseFloat(obj.transaction);
              }else{
                total_ammount += parseFloat(obj.transaction);
              }

          }
    }

    var year_total_all = - (total_ammount/diffDays)*365;
    var year_shop = - (ammount/diffDays)*365;


    // This is static for Blue AMEX only
    var result = year_total_all*0.004  + year_shop*0.02 - 25;

    return result;
}

//------------------------------------------------------------------------------


function getAccountValue(bankid,accountid,viewid,authid){

    // FIXME: does not work!
    return null;

    var propertiesObject = JSON.stringify({
        'Auth-Provider-Name' : '01460900080600',
        'Auth-ID' : '123456789'
    });

    var murl = bocurl + bocurlpath + "banks/" + bocuuid
          + "/accounts/a746637b91b19a261a67d8bd/5710bba5d42604e4072d1e92"
          + "/account/?subscription-key=" + bockey;

    console.log(murl);
    var request = require('request');
    //var propertiesObject = { field1:'test1', field2:'test2' };

    request({url:murl, qs:propertiesObject}, function(err, response, body) {
      if(err) { console.log(err); return; }
      console.log(response.body);
    });



}


function getRangeForSaving(transactions){



    var date1 = moment(transactions[0].Date, "DD.MM.YYYY HH.mm").toDate();
    var date2 = moment(transactions[transactions.length-1].Date, "DD.MM.YYYY HH.mm").toDate();

    var histogramExp = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,00];
    var histogramIncome = [0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0,0];


    var timeDiff = Math.abs(date2.getTime() - date1.getTime());
    var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
    var diffMonths = Math.ceil(timeDiff / (1000 * 3600 * 24 * 30));


    var expenses = 0;
    var income = 0;
    var ammount = 0;


    for(var i = 0; i < transactions.length; i++) {
      var obj = transactions[i];
      var date = moment(obj.Date, "DD.MM.YYYY HH.mm").date();
          if (obj.transaction<0) {
              histogramExp[ date ]   += parseFloat(obj.transaction);
              ammount += parseFloat(obj.transaction);

          }else{
            histogramIncome[ date ]   += parseFloat(obj.transaction);
          }
    }


    var day_exp = - (ammount/diffDays);
    var month_income =  1450;
    var saved = 0;

    var current = month_income;
    for(var i = 0; i < 30; i++) {
      saved += current*0.016/365; // interest is 1.6% in 180days e-notice
      current = current - day_exp;
    }


    return saved*12;
}
