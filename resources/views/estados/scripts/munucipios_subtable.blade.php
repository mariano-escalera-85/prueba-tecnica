$('#estados-table tbody').on('click', 'button.load-municipios', function () {
    const tr = $(this).closest('tr');
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

    // 2. Trigger the backend import action for this specific Estado
    $.ajax({
        url: `/api/estados/${rowData.id}/municipios/import`,
        type: "POST",
        success: function(response) {
            // 3. On successful import, inject a new empty table for the sub-records
            const childTableId = `municipios-table-${rowData.id}`;
            row.child(`<table id="${childTableId}" class="table table-sm table-bordered w-100"></table>`).show();

            // 4. Initialize a new DataTable on the injected table
            $(`#${childTableId}`).DataTable({
                processing: true,
                serverSide: true,
                // Make a GET request to fetch the newly imported data
                ajax: `/api/estados/${rowData.id}/municipios`,
                columns: [
                    { data: 'cve_mun', name: 'cve_mun', title: 'Clave Mun.' },
                    { data: 'nomgeo', name: 'nomgeo', title: 'Municipio' },
                    { data: 'pob_total', name: 'pob_total', title: 'Población' }
                ],
                order: [[0, 'asc']],
                paging: false, // Often disabled for subtables to save space, optional
                info: false,
                searching: false
            });
        },
        error: function(xhr) {
            console.log(xhr);
            row.child(`<div class="alert alert-danger m-2">Error al cargar municipios: ${xhr.statusText}</div>`).show();
        }
    });
});
