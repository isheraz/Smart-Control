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

function disabled() {
    $('.disabled').bind('click', function () {
        return false;
    });
}