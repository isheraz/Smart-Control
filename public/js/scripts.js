$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function () {
    "use strict";
    disabled();

    /* change state of a single node inside a device*/
    $('.node-state').on('change', function () {
        var node;
        node = this.id;
        update_state(node);
    });

    /* Update Node */
    $('#node_update').submit(function (event) {
        event.preventDefault();
        var el = $('#node_name');
        var id = $('#nodeid');
        var val = $('#nodevalue');
        var icon = $('#node_icon').val();
        var node = '{ "id":' + id.val() + ', "name":"' + el.val() + '", "value":"' + val.val() + '", "state"' +
            ' : ' + false + ', "icon":"' + icon + '"}';

        $('<input />').attr('type', 'hidden')
            .attr('name', "node")
            .attr('value', node)
            .appendTo(this);
        var link = $('#node_update').attr('action');
        $.ajax({
            url: link,
            dataType: "json",
            async: true,
            method: 'POST',
            data: {node: node},
            success: function (response) {
                Swal({
                    type: 'success',
                    text: response.message,
                    title: "Node Updated",
                    showCancelButton: false,
                });
            }
        });
    });

    /*Add New Chart*/
    add_new_chart();
});

function update_state(node) {

    var link = $('#device_node').attr('action');
    $.ajax({
        url: link,
        dataType: "json",
        async: true,
        method: 'POST',
        data: {node: node},
        success: function (response) {
            Swal({
                type: 'success',
                text: response.message,
                title: "Device Updated",
                showCancelButton: false,
            });
        }
    });
}

function add_new_chart() {
    if ($('#graph-type').length > 0) {


        $('#graph-type').on('submit', function (e) {
            e.preventDefault();
            var data = $('#graph-type').serializeArray();
            console.log(data);

            $.ajax({
                url: $(this).attr('action'),
                method: 'post',
                accept: 'json',
                data: data,
                success: function(response){
                    // console.log(response);
                }
            })
        });

        add_new_y_axis();
        delete_y_axis();
    }
}

function add_new_y_axis() {
    $('.btn_chart_y').on('click', function (e) {
        e.preventDefault();

        let val = ($(this).siblings().val());
        if(val.length > 0 ){

            $(this).siblings().val(null);
            let new_elem = "<div class=\"input-group pt-2\" id='" + val + "'>\n" +
                "<input type=\"text\" value='" + val + "' class=\"form-control disabled\" disabled name=\"chart_y_labels[]\">\n" +
                "<button class=\"btn btn-outline-danger btn_chart_y_del\"><i class=\"fa fa-trash\"></i></button>\n" +
                "</div>"

            $('.chart-y-axises').append(new_elem);
        }else{
            swal("ADD A NAME FOR THE TARGET");
        }
    })

}

function delete_y_axis() {
    $('.btn_chart_y_del').on('click', function (e) {
        e.preventDefault();
        $(this).parent().remove();
    })
}

function display_chart() {
    var MONTHS = [
        'January',
        'February',
        'March',
        'April',
        'May',
        'June',
        'July',
        'August',
        'September',
        'October',
        'November',
        'December'
    ];

    var COLORS = [
        '#4dc9f6',
        '#f67019',
        '#f53794',
        '#537bc4',
        '#acc236',
        '#166a8f',
        '#00a950',
        '#58595b',
        '#8549ba'
    ];

}

function disabled() {
    $('.disabled').bind('click', function () {
        return false;
    });
}