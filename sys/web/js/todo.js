function brandcategory_list__filter(q) {
    filter(q);

    function filter(q) {
        if (q.length === 0) {
            const bs = Array.from(document.querySelectorAll('tr[data-component="productSearchCommon"][data-purpose="item"]'));
            for (b of bs) {
                b.classList.remove("d-none");
            }
            return;
        }
        q = q.toLowerCase();
        const bs = Array.from(document.querySelectorAll('tr[data-component="productSearchCommon"][data-purpose="item"]'));
        for (b of bs) {
            const name = b.dataset.name.toLowerCase();
            if (name.indexOf(q) !== -1) {
                b.classList.remove("d-none");
            } else {
                b.classList.add("d-none");
            }
        }
    }

}