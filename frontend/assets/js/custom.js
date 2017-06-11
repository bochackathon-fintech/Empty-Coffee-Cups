$(document).ready(function () {

    $(".maximize").click(function(event) {
        event.stopPropagation();
        $(".watsonChatbot").addClass("full-screen");
    });
    $(".minimize").click(function(event) {
        event.stopPropagation();
        $(".watsonChatbot").removeClass("full-screen");
        $(".watsonChatbot").removeClass("amUp");
        $(".watsonChatbot").css("bottom", -436);
    });

    $(".expand-warnings-dropdown").click(function() {
        if($(".warnings-dropdown-wrapper").hasClass('amhidden')) {
            $(".warnings-dropdown-wrapper").removeClass('amhidden');
            $(".warnings-dropdown-wrapper").slideDown();
            $(this).html('<i class="fa fa-minus-square" aria-hidden="true"></i>');
        } else {
            $(".warnings-dropdown-wrapper").slideUp();
            $(".warnings-dropdown-wrapper").addClass('amhidden');
            $(this).html('<i class="fa fa-plus-square" aria-hidden="true"></i>');
        }
    });

    $(".sortby.priority").click(function() {
        console.log('priority');
        tinysort('.customer-goals-list > .goal-group-details',{data:'sortp'});
    });
    $('.sortby.status').click(function() {
        console.log('status');
        tinysort('.customer-goals-list > .goal-group-details',{order:'desc', data:'sortstats'});
    });
    $('.sortby.ltoh').click(function() {
        console.log('ltoh');
        tinysort('.customer-goals-list > .goal-group-details',{order:'desc', data:'sortp'});
    });
    $(".sortby.htol").click(function() {
        console.log('htol');
        tinysort('.customer-goals-list > .goal-group-details',{order:'asc', data:'sortp'});
    });

    $(".showAddGoalForm").click(function() {
        if($(".setgoals-form").hasClass('amhidden')) {
            $(".setgoals-form").removeClass('amhidden');
            $(".setgoals-form").slideDown();
            $(this).html('<i class="fa fa-minus-square" aria-hidden="true"></i>');
        } else {
            $(".setgoals-form").slideUp();
            $(".setgoals-form").addClass('amhidden');
            $(this).html('<i class="fa fa-plus-square" aria-hidden="true"></i>');
        }
    });
    
    $(".show-sort-settings").click(function() {
        if($(".sortby-settings").hasClass('amhidden')) {
            $(".sortby-settings").removeClass('amhidden');
            $(".sortby-settings").slideDown();
        } else {
            $(".sortby-settings").slideUp();
            $(".sortby-settings").addClass('amhidden');
        }
    });

    var trigger = $('.hamburger'),
    overlay = $('.overlay'),
    isClosed = false;

    trigger.click(function () {
        hamburger_cross();      
    });

    function hamburger_cross() {
        if (isClosed == true) {          
            overlay.hide();
            trigger.removeClass('is-open');
            trigger.addClass('is-closed');
            isClosed = false;
        } else {   
            overlay.show();
            trigger.removeClass('is-closed');
            trigger.addClass('is-open');
            isClosed = true;
        }
    }

    $('[data-toggle="offcanvas"]').click(function () {
        $('#wrapper').toggleClass('toggled');
    });

    jQuery.fn.sortDivs = function sortDivs() {
        $("> div", this[0]).sort(dec_sort).appendTo(this[0]);
        function dec_sort(a, b){ return ($(b).data("sortp")) < ($(a).data("sortp")) ? 1 : -1; }
    }

    // this is the id of the form
    $("#setgoals-form").submit(function(e) {
        var url = $(this).attr('action'); // the script where you handle the form input.
        $.ajax({
            type: "POST",
            url: url,
            data: $(this).serialize(), // serializes the form's elements.
            success: function(data) {
                //console.log(data); // show response from the php script.
                reloadGoalsList();
            }
        });

        e.preventDefault(); // avoid to execute the actual submit of the form.
    });
    //reload goals list after adding a new one
    function reloadGoalsList() {
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
    //delete goal
    $(document).on("click", ".deleteGoal", function(e) {
        var action = 'deleteGoal';
        var custId = '304fd2e19f1c14fe3345cca788e4e83d';
        var goal_name = $(this).find('.goal_name').val();

        $.ajax({
            type: "POST",
            url: "manager/ajaxForms.php",
            data: {action: action, customer_id: custId, goal_name: goal_name},
            success: function(data) {
                //console.log(data); // show response from the php script.
                reloadGoalsList();
            }
        });
    });
    
    $(".pop-up-watson").click(function() {
        if($(".watsonChatbot").hasClass("amUp")) {
            $(".watsonChatbot").removeClass("amUp");
            $(".watsonChatbot").css("bottom", -436);
        } else {
            $(".watsonChatbot").addClass("amUp");
            $(".watsonChatbot").css("bottom", 0);
        }
    });
});