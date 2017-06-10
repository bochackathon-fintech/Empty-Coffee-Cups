$(document).ready(function () {
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
                console.log(data); // show response from the php script.
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
});