function sentJsonMessage(action,message,json_resp) {
	$.ajax({
        type: "POST",
        url: "manager/watsonBackend.php",
        data: {action: action, message: message, json_resp: json_resp},
        success: function(data) {
            $('.raw_json').val(data);
            // console.log(data); // show response from the php script.
            jsonData = $.parseJSON(data);
            console.log(jsonData);
            console.log(jsonData.intents);

            var watsonResponse = jsonData.output.text[0];
            $('.response_input').val(watsonResponse);
            $('.conversation_id').val(jsonData.context.conversation_id);
            $(".send_response").click();

            var goalName = jsonData.context.bbGoalName;
            var goalAmount = jsonData.context.bbGoalAmount;
            var goalDate = jsonData.context.bbGoalDate;
            console.log("dets:" + goalName + " " + goalAmount + " " + goalDate);
            if(goalName !='' && goalAmount > 0 && goalDate != '') {
                //add goal!
                // $name = $_POST['goal_name'];
                // $value = $_POST['goal_value'];
                // $saved = $_POST['saved'];
                // $gdate = $_POST['goal_date'];
                // $accountid = $_POST['accountid'];
                // $priority = $_POST['priority'];
                // $custId = $_POST['customer_id'];

                var action = 'setgoal';
                var custId = '304fd2e19f1c14fe3345cca788e4e83d';

                $.ajax({
                    type: "POST",
                    url: "manager/ajaxForms.php",
                    data: {action: action, customer_id: custId, goal_name: goalName, goal_value: goalAmount, goal_date: goalDate, saved: 1, priority: 1, accountid: 'abc123', },
                    success: function(data) {
                        //console.log(data); // show response from the php script.
                        reloadGoalsListWatson();
                    }
                });
            }
        }
    });
}
//reload goals list after adding a new one
function reloadGoalsListWatson() {
    var action = 'updateGoalsList';
    var custId = '304fd2e19f1c14fe3345cca788e4e83d';

    $.ajax({
        type: "POST",
        url: "manager/ajaxForms.php",
        data: {action: action, customer_id: custId},
        success: function(data) {
            // console.log(data); // show response from the php script.
            $(".customer-goals-list").empty();
            $(".customer-goals-list").html(data);
        }
    });
}
//
$(document).ready(function () {

    $('.message_input').keyup(function (e) {
        if (e.which === 13) {
            var action = 'sendMessage';
	    	var message = $('.message_input').val();
	    	var json_resp = $('.raw_json').val();
	    	sentJsonMessage(action,message,json_resp);
        }
    });
    $('.send_message').click(function (e) {
    	var action = 'sendMessage';
    	var message = $('.message_input').val();
    	var json_resp = $('.raw_json').val();
	    sentJsonMessage(action,message,json_resp);
    });
});