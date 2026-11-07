<style>
    table[data-pagination] > tbody > tr[data-pagination-hidden] {
        display: none !important;
    }
</style>

<script>
document.querySelectorAll('table[data-pagination]').forEach(function (table) {
    const body = table.tBodies[0];
    const pageSize = Number(table.dataset.pagination);

    if (!body || !Number.isInteger(pageSize) || pageSize < 1) return;

    const controls = document.createElement('nav');
    const label = table.dataset.paginationLabel || 'Table';
    let page = 1;

    controls.className = 'd-flex flex-wrap align-items-center gap-2 mt-3';
    controls.setAttribute('aria-label', `${label} pagination`);

    controls.innerHTML = `
        <button type="button" class="btn btn-outline-primary btn-sm">Previous</button>
        <span class="small text-muted" aria-live="polite"></span>
        <button type="button" class="btn btn-outline-primary btn-sm">Next</button>
    `;

    const [previous, status, next] = controls.children;
    (table.closest('.table-responsive') || table).after(controls);

    function render() {
        const rows = Array.from(body.rows);
        const matchingRows = rows.filter(row => !row.hidden && row.cells.length > 1);
        const pages = Math.max(1, Math.ceil(matchingRows.length / pageSize));

        page = Math.max(1, Math.min(page, pages));

        const start = (page - 1) * pageSize;
        const end = start + pageSize;
        const pageRows = new Set(matchingRows.slice(start, end));

        for (const row of rows) {
            const hideByPagination = !row.hidden && row.cells.length > 1 && !pageRows.has(row);
            row.toggleAttribute('data-pagination-hidden', hideByPagination);
        }

        matchingRows.forEach(function (row, index) {
            const numberCell = row.cells[0];

            if (numberCell.hasAttribute('data-row-number')) {
                const number = String(index + 1);

                if (numberCell.textContent !== number) {
                    numberCell.textContent = number;
                }
            }
        });

        controls.classList.toggle('d-none', pages < 2);
        previous.disabled = page === 1;
        next.disabled = page === pages;
        status.textContent = `Page ${page} of ${pages} · ${matchingRows.length} records`;
    }

    previous.addEventListener('click', function () {
        page--;
        render();
    });

    next.addEventListener('click', function () {
        page++;
        render();
    });

    new MutationObserver(function (changes) {
        const filterChanged = changes.some(change =>
            change.type === 'attributes' && change.target.parentElement === body
        );

        const rowsChanged = changes.some(change =>
            change.type === 'childList' && change.target === body
        );

        if (filterChanged) page = 1;
        if (filterChanged || rowsChanged) render();
    }).observe(body, {
        subtree: true,
        childList: true,
        attributes: true,
        attributeFilter: ['hidden']
    });

    render();
});
</script>