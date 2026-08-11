$(document).on('click', '#estados-table .load-municipios', function (e) {
    const tr = $(this).closest('tr');
    console.log(this, tr);

    // Access the main DataTable instance
    const table = window.LaravelDataTables['estados-table'];
    const row = table.row(tr);
    const rowData = row.data(); // Gets the JSON data for this specific row (including ID)

    // Toggle child row if already open
    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        return;
    }

    // 1. Show loading state in the child row
    row.child('<div class="text-center py-3">Importando y cargando municipios...</div>').show();
    tr.addClass('shown');

    // 2. On successful import, inject a new empty table for the sub-records
    const childTableId = `municipios-table-${rowData.id}`;
    row.child(`<table id="${childTableId}" class="table table-sm table-bordered w-100"></table>`).show();

    // 3. Initialize a new DataTable on the injected table
    $(`#${childTableId}`).DataTable({
        processing: true,
        serverSide: true,
        // Make a GET request to fetch the subtable data
        ajax: `/api/estados/${rowData.id}/municipios`,
        columns: [
            { data: 'cve_mun', name: 'cve_mun', title: 'Clave Mun.', className: 'text-center' },
            { data: 'nomgeo', name: 'nomgeo', title: 'Municipio', className: 'text-center' },
            { data: 'pob_total', name: 'pob_total', title: 'Población', className: 'text-center' }
        ],
        order: [[0, 'asc']],
        paging: true,
        info: false,
        searching: true
    });
});
