document.addEventListener('DOMContentLoaded', function () {
    let common_cart__data = []; //not closure 
    site__cart_render_init();
});

function site__cart_render_init() {
    $.get(commonData__cartGet_url, {}).done(function (data) {
        common_cart__data = data;
        render(common_cart__data);
    });

    function inProcessSpinner_show(productId) {
        const els = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="inProcessSpinnerContainer"][data-product-id="' + productId + '"]'));
        for (el of els) {
            helper_show(el);
        }

        hideControls(productId);
    }
    function inProcessSpinner_hideAll() {
        const els = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="inProcessSpinnerContainer"]'));
        for (el of els) {
            helper_hide(el);
        }
    }
    function getQuantity(productId) {
        let q = 0;
        common_cart__data.items.forEach(function (item) {
            if (parseInt(productId) === parseInt(item.productId)) {
                q = parseInt(item.quantity);
            }
        });
        return q;
    }
    function setQuantity(productId, quantity) {
        quantity = parseInt(quantity) || 0;
        quantity = (quantity < 0) ? 0 : quantity;
        const dataPost = {
            productId: productId,
            quantity: quantity
        };
        $.post(commonData__cartSet_url, dataPost).done(function (response) {
            site__cart_render_init();
        });
    }

    function renderControlsBefore() {
        const puts = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerPut"]'));
        for (tmp of puts) {
            tmp.dataset.show = 1;
        }

        const sets = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerSet"]'));
        for (tmp of sets) {
            tmp.dataset.show = 0;
        }
    }
    function swithFromPutToControl(productId, quantity) {
        const puts = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerPut"][data-product-id="' + productId + '"]'));
        for (tmp of puts) {
            tmp.dataset.show = 0;
        }

        const sets = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerSet"][data-product-id="' + productId + '"]'));
        for (tmp of sets) {
            tmp.dataset.show = 1;
        }

        const inps = Array.from(document.querySelectorAll('input[data-component="cart"][data-purpose="controlBtn"][data-action="set"][data-product-id="' + productId + '"]'));
        for (tmp of inps) {
            tmp.value = quantity;
        }
    }
    function renderControlsAfter() {
        const puts = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerPut"]'));
        for (tmp of puts) {
            if (parseInt(tmp.dataset.show) === 1) {
                helper_show(tmp);
            } else {
                helper_hide(tmp);
            }
        }

        const sets = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerSet"]'));
        for (tmp of sets) {
            if (parseInt(tmp.dataset.show) === 1) {
                helper_show(tmp);
            } else {
                helper_hide(tmp);
            }
        }
    }

    function hideControls(productId) {
        const puts = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerPut"][data-product-id="' + productId + '"]'));
        for (tmp of puts) {
            tmp.dataset.show = 0;
        }

        const sets = Array.from(document.querySelectorAll('div[data-component="cart"][data-purpose="controlsContainerSet"][data-product-id="' + productId + '"]'));
        for (tmp of sets) {
            tmp.dataset.show = 0;
        }

        renderControlsAfter();
    }

    function renderPrice(productId, priceInitial, priceFinal) {
        if (priceInitial !== priceFinal) {
            const priceInitials = Array.from(document.querySelectorAll('span[data-component="cart"][data-purpose="priceInitial"][data-product-id="' + productId + '"]'));
            for (item of priceInitials) {
                item.innerHTML = '<s><span numeral="my10k">' + (priceInitial / 100) + '</span> р.</s>';
            }
        }
        const priceFinals = Array.from(document.querySelectorAll('span[data-component="cart"][data-purpose="priceFinal"][data-product-id="' + productId + '"]'));
        for (item of priceFinals) {
            item.innerHTML = '<span numeral="my10k">' + (priceFinal / 100) + '</span> р.';
        }

    }


    function render(common_cart__data) {
        const countTotal = common_cart__data.quantity;
        const priceTotal = common_cart__data.cost;

        inProcessSpinner_hideAll();
        renderControlsBefore();
        common_cart__data.items.forEach(function (item) {
            swithFromPutToControl(item.productId, item.quantity);
            renderPrice(item.productId, item.priceInitial, item.priceFinal);
        });
        renderControlsAfter();

        if (countTotal > 0) {
            $('span[cart__top_nav_count_total]').text(countTotal).show();
            $('span[cart__top_nav_icon]').show(); //.removeClass('text-secondary').addClass('text-danger')
        } else {
            $('span[cart__top_nav_count_total]').text(countTotal).hide();
            $('span[cart__top_nav_icon]').hide();
        }

        const cart__countTotal_wrapper = Array.from(document.querySelectorAll('span[cart-count-total]'));
        for (item of cart__countTotal_wrapper) {
            item.innerHTML = countTotal;
        }

        const cart__priceTotal_wrapper = Array.from(document.querySelectorAll('span[cart-price-total]'));
        for (item of cart__priceTotal_wrapper) {
            item.innerHTML = '<span numeral="my10k">' + (priceTotal / 100) + '</span>';
        }

        const spandelivnames = Array.from(document.querySelectorAll('span[data-component="cart"][data-purpose="deliveryName"][data-todo="1"]'));
        for (spandelivname of spandelivnames) {
            spandelivname.dataset.todo = 0;
            const deliveryTypeId = parseInt(spandelivname.dataset.deliveryTypeId) || 0;
            let deliveryName = '?';
            switch (deliveryTypeId) {
                case 1:
                    deliveryName = 'Самовывоз';
                    break;
                case 2:
                    deliveryName = 'СДЭК';
                    break;
                case 3:
                    deliveryName = 'Деловые линии';
                    break;
            }
            spandelivname.innerHTML = deliveryName;
        }        

        helper_numeral();
        events();
    }

    function events() {
        const actionClick = ['put', 'dec', 'inc', 'del'];
        const actionChange = ['set'];
        const cntrls = Array.from(document.querySelectorAll('[data-component="cart"][data-purpose="controlBtn"][data-todo="1"]'));
        for (btn of cntrls) {
            btn.dataset.todo = 0;

            //click
            if (actionClick.includes(btn.dataset.action)) {
                btn.addEventListener('click', (e) => {
                    const el = e.currentTarget;
                    const productId = parseInt(el.dataset.productId) || 0;
                    inProcessSpinner_show(productId);

                    let quantity = getQuantity(productId);
                    switch (el.dataset.action) {
                        case 'put':
                            quantity = 1;
                            break;
                        case 'dec':
                            quantity -= 1;
                            break;
                        case 'inc':
                            quantity += 1;
                            break;
                        case 'del':
                            quantity = 0;
                            const lines = Array.from(document.querySelectorAll('[data-component="cart"][data-purpose="item"][data-product-id="' + productId + '"]'));
                            for (line of lines) {
                                helper_hide(line);
                            }
                            break;
                    }
                    setQuantity(productId, quantity);
                });
            }

            //change
            if (actionChange.includes(btn.dataset.action)) {
                btn.addEventListener('change', (e) => {
                    const el = e.currentTarget;
                    const productId = parseInt(el.dataset.productId) || 0;
                    inProcessSpinner_show(productId);

                    let quantity = parseInt(el.value);

                    setQuantity(productId, quantity);
                });
            }
        }
    }
}

