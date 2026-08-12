$(document).on('click', '#estados-table tbody tr.load-municipios', function (e) {
    if ($(e.target).closest('tr').hasClass('child') || $(e.target).closest('.dataTables_wrapper').length > 1) {
        return;
    }

    const tr = $(this);
    // Access the main DataTable instance
    const table = window.LaravelDataTables['estados-table'];
    const row = table.row(tr);
    const rowData = row.data();

    if (!rowData) return;

    // Toggle child row if already open
    if (row.child.isShown()) {
        row.child.hide();
        tr.removeClass('shown');
        return;
    }

    // Show loading state in the child row
    row.child('<div class="text-center py-3">Importando y cargando municipios...</div>').show();
    tr.addClass('shown');

    // On successful import, inject a new empty table for the sub-records
    const childTableId = `municipios-table-${rowData.id}`;
    row.child(`<div class="p-2"><table id="${childTableId}" class="table table-sm table-bordered w-100"></table></div>`).show();

    // Initialize a new DataTable on the injected table
    $(`#${childTableId}`).DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `/api/estados/${rowData.id}/municipios`,
            type: 'GET',
        },
        columns: [
            { data: 'cve_mun', name: 'cve_mun', title: 'Clave Mun.', className: 'text-center' },
            { data: 'nomgeo', name: 'nomgeo', title: 'Municipio', className: 'text-center' },
            { data: 'pob_total', name: 'pob_total', title: 'Población', className: 'text-center' }
        ],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
        },
        order: [[0, 'asc']],
        paging: true,
        info: false,
        searching: true
    });
});
