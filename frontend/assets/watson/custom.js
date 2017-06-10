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

            var watsonResponse = jsonData.output.text[0];
            $('.response_input').val(watsonResponse);
            $('.conversation_id').val(jsonData.context.conversation_id);
            $(".send_response").click();
        }
    });
}

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