(function($) {
    var table = $('.server-datatables').DataTable({
        "sort": false,
        "ordering": false,
        "pagingType": "full_numbers",
        "processing": true,
        "serverSide": true,
        "lengthMenu": [
            [25, 50, 150, -1],
            [25, 50, 150, "All"]
        ],
        ajax: {
            "url" : baseUrl+"/email-lists/fetch",
            // "data" : function(d) {
            //     d.joined_from = $('[name="joined_from"]').val();
            //     d.joined_to = $('[name="joined_to"]').val();
            // }
        },
        columns: [
            {data: 'id', name: 'id'},
            {data: 'name', name: 'name'},
            {data: 'active_subscribers_count', name: 'active_subscribers_count'},
            {data: 'in_active_subscribers_count', name: 'in_active_subscribers_count'},
            {data: 'created_at', name: 'created_at'},
            {
                class: 'td-actions text-end',
                data: 'action',
                name: 'action',
                orderable: true,
                sorting: false,
                searchable: false
            },
        ],
        fnDrawCallback: function (oSettings) {
            var tooltip = document.querySelectorAll('[data-bs-toggle="tooltip"]');
            $(tooltip).each(function (index, element) {
                new bootstrap.Tooltip(element)
            });
        },
        responsive: true,
        language: {
            search: "_INPUT_",
            searchPlaceholder: "Search records",
        }
    });

    $("#search").keyup(function (e) {
        table.search($(this).val()).draw() ;
    });

    $(document).on("click", ".view-item", function(e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).attr('href'),
            success: function (response) {
                $("#view_details_modal .modal-body").html(response);
                $("#view_details_modal").modal('show');
            },
        });
    });
})(jQuery);
