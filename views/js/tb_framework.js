window.tb_framework = {};

addEventListener('DOMContentLoaded', (event) => {

    Object.entries(window.tb_framework).forEach(component_names => {

        const [component_name, components] = component_names;

        components.forEach(function (component) {
            initComponent(component);
        });

    });

});

addEventListener('click', function(e){
    var clicked_element = e.target;
    if (clicked_element.classList.contains('prevent-double-click')) {
        if (isDoubleClick(clicked_element)) {
            e.preventDefault(); // Don't follow the link if 1000 ms weren't gone from the last click
        }
    }
});

function isDoubleClick(clicked_element, blockingTime = 1000) {
    // Make sure that double clicks don't get trough
    if (clicked_element.getAttribute('data-last-click') && (+new Date() - clicked_element.getAttribute('data-last-click')) < blockingTime) {
        return true;
    }

    clicked_element.setAttribute('data-last-click', +new Date());
    return false;
}

function getJsComponentByUniqueId(id) {

    var componentFound = false;

    Object.keys(window.tb_framework).forEach(key => {
        window.tb_framework[key].forEach(function (component) {
            if (component.id===id) {
                componentFound = component;
            }
        });
    });

    return componentFound;
}

function addComponent(js_component) {

    if (!window.tb_framework[js_component.name]) {
        window.tb_framework[js_component.name] = [];
    }

    var blocksOfComponent = document.querySelectorAll('[data-id-component="'+js_component.id+'"]');
    if (blocksOfComponent) {
        js_component.htmlTags = blocksOfComponent; // Note: this concept only works with ajax or if the data-id-component is used in tpl files as well
    }

    window.tb_framework[js_component.name].push(js_component);
}

function removeComponent(js_component) {

    // Todo: while it's nice to have such a function: atm it doesn't work in combination with data-ajax-confirmation, as this would be triggered by the component

    if (window.tb_framework[js_component.name]) {

        // Remove old object if the new id does match an old id
        const indexOfComponent = window.tb_framework[js_component.name].findIndex(componentByType => componentByType.id === js_component.id);

        // Check if object was found
        if (indexOfComponent !== -1) {

            // Remove related style/script tags of the component
            if (window.tb_framework[js_component.name][indexOfComponent].htmlTags) {
                window.tb_framework[js_component.name][indexOfComponent].htmlTags.forEach(function (htmlTag) {
                    htmlTag.remove();
                });
            }
            // Remove the htmlElement of the component
            window.tb_framework[js_component.name][indexOfComponent].htmlElement.remove();

            // Remove the component in the component collection
            window.tb_framework[js_component.name].splice(indexOfComponent, 1);
        }
    }
}


function initComponent(js_component) {

    // Check if the component contains the init function -> if yes trigger it
    if (js_component.init && typeof js_component.init === 'function') {
        js_component.init();
    }

}


function renderComponentWithAjax(
    component_name,
    data = {},
    style = '',
    relative_element = document.body,
    relative_position = 'append',
    callback_function = false) {

    // Todo: add some first validation

    var request = new XMLHttpRequest();
    request.open('POST', '/modules/tb_framework/tb_framework_ajax.php?renderComponentWithAjax', true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.setRequestHeader("ACCEPT", "image/webp"); // Important that tb returns webp images
    request.send(
        'component_name=' + component_name +
        '&data=' + encodeURIComponent(JSON.stringify(data)) +
        '&style=' + style
    );

    request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(this.response);
            if (response) {
                var js_component = initAjaxComponent(response, relative_element, relative_position);
                if (typeof callback_function == "function") {
                    if (js_component) {
                        callback_function(js_component);
                    }
                }
            }
        }
    }

}

function initAjaxComponent(component, relative_element = document.body, relative_position = 'append') {

    removeComponent(component);

    initHtmlContent(component.htmlElement, relative_element, relative_position, component.id);
    var js_component = window[component.id];
    // Note: there is no need to use addComponent() as this is already executed in the tpl file
    initComponent(js_component);

    if (component.name==='toast') {
        setMaxZIndex(js_component); // This is needed in an edge case, when a toast is triggered on a modal, that has already used setMaxZIndex
    }

    return js_component;
}

// Note: when you load html by ajax, the script tags and external files aren't executed/loaded by just adding the html div
// This function does handle this "problem".
function initHtmlContent(content, relative_element = document.body, relative_position = 'append', id_component = '') {

    // First we need create an element that is readable
    var template = document.createElement('template');
    template.innerHTML = content;

    // Find all external files and remove it from html content
    var js_files = [];
    var css_files = [];
    var js_blocks = [];
    var css_blocks = [];

    var link_tags = template.content.querySelectorAll('link');

    if (link_tags) {
        link_tags.forEach(function (link_tag) {

            link_tag.remove(); // Remove it from html content

            if (link_tag.href && link_tag.href.includes('.css')) {
                css_files.push(link_tag.href);
            } else if (link_tag.src && link_tag.src.includes('.js')) {
                js_files.push(link_tag.src);
            }
        });
    }

    // Find all style tags and remove it from html content
    var style_tags = template.content.querySelectorAll('style');

    if (style_tags) {
        style_tags.forEach(function (style_tag) {
            style_tag.remove(); // Remove it from html content
            css_blocks.push(style_tag.innerHTML);
        });
    }

    // Find all script tags and remove it from html content
    var script_tags = template.content.querySelectorAll('script');

    if (script_tags) {
        script_tags.forEach(function (script_tag) {

            script_tag.remove(); // Remove it from html content

            if (script_tag.src) {
                js_files.push(script_tag.src);
            } else {
                js_blocks.push(script_tag.innerHTML);
            }
        });
    }


    // Adding the clean html block (needs to happen before we add the script tags -> otherwise a lot of error will happen as the selectors are not working)
    var component_helper = document.createElement('div');
    component_helper.innerHTML = template.innerHTML.trim(); // Never return a text node of whitespace as the result

    var component = component_helper;
    var childNodes = Array.from(component.childNodes);

    // Note: insertAdjacentHTML has the drawback, that the callback element is not usable
    if (relative_position === 'prepend') {

        // Changing the order of the child array. We need to prepend the last child first.
        childNodes = childNodes.reverse();

        // Making sure, that we don't have the 'div' around but hold all javascript functionality (innerHtml/outerHtml does lose it)
        childNodes.forEach(function (children) {
            relative_element.prepend(children);
        });

    } else if (relative_position === 'append') {
        // Making sure, that we don't have the 'div' around but hold all javascript functionality (innerHtml/outerHtml does lose it)
        childNodes.forEach(function (children) {
            relative_element.appendChild(children);
        });
    }
    else if (relative_position === 'replace') {
        relative_element.after(component);
        relative_element.remove();
    }

    css_files.forEach(function (href) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;

        if (id_component) {
            // This attribute helps to understand, which component added which tags
            link.setAttribute('data-id-component', id_component);
        }

        document.head.appendChild(link);
    });

    // Add css blocks
    css_blocks.forEach(function (css_code) {
        var style_inner = document.createElement('style');
        if (id_component) {
            // This attribute helps to understand, which component added which tags
            style_inner.setAttribute('data-id-component', id_component);
        }
        style_inner.innerHTML = css_code;
        document.body.appendChild(style_inner);
    });

    // Now we read the script and link tags, so that they are executed.
    if (js_files.length) {
        // In case there are external js files, we first need to load them all
        js_files.forEach(function (src, index, array) {

            var script = document.createElement('script');
            script.src = src;
            document.body.appendChild(script);

            script.addEventListener('load', function () {

                array.splice(index, 1); // Remove the js file from the array

                // Once all js files are loaded we execute the js blocks
                if (!js_files.length) {
                    loadJsBlocks(js_blocks, id_component);
                }
            });

        });
    }
    else {
        loadJsBlocks(js_blocks, id_component);
    }

    // Return clean htmlElement
    return component;
}

function loadJsBlocks(js_blocks, id_component) {
    if (js_blocks.length) {
        js_blocks.forEach(function (js_code) {
            var script_inner = document.createElement('script');

            if (id_component) {
                // This attribute helps to understand, which component added which tags
                script_inner.setAttribute('data-id-component', id_component);
            }

            script_inner.innerHTML = js_code;
            document.body.appendChild(script_inner);
        });
    }
}

// Make sure that a new component has the highest z-index (modals)
function setMaxZIndex(component) {

    var z_index_max = Math.max(
        ...Array.from(document.querySelectorAll('body *'), el =>
            parseFloat(window.getComputedStyle(el).zIndex),
        ).filter(zIndex => !Number.isNaN(zIndex)),
        0,
    );

    component.htmlElement.style.zIndex = z_index_max+1;
}

// Used for outside click handling
function hasSomeParentTheId(element, id) {
    if (element.id && typeof element.id==='string' && element.id.split(' ').indexOf(id)>=0) return element;
    return element.parentNode && hasSomeParentTheId(element.parentNode, id);
}

function hasSomeParentTheClass(element, classname) {
    if (element.className && typeof element.className==='string' && element.className.split(' ').indexOf(classname)>=0) return element;
    return element.parentNode && hasSomeParentTheClass(element.parentNode, classname);
}

// Modal handling
function checkIfAnyModalIsOpen() {

    var anyModalOpen = false;

    if (window.tb_framework.modal_default) {
        window.tb_framework.modal_default.forEach(function (modal_default) {
            if (modal_default.isOpen) {
                anyModalOpen = true;
            }
        });
    }

    if (!anyModalOpen && window.tb_framework.modal_login) {
        window.tb_framework.modal_login.forEach(function (modal_login) {
            if (modal_login.isOpen) {
                anyModalOpen = true;
            }
        });
    }

    if (!anyModalOpen && window.tb_framework.modal_add_to_cart) {
        window.tb_framework.modal_add_to_cart.forEach(function (modal_add_to_cart) {
            if (modal_add_to_cart.isOpen) {
                anyModalOpen = true;
            }
        });
    }

    if (!anyModalOpen && window.tb_framework.modal_confirmation) {
        window.tb_framework.modal_confirmation.forEach(function (modal_confirmation) {
            if (modal_confirmation.isOpen) {
                anyModalOpen = true;
            }
        });
    }

    return anyModalOpen;
}

function closeAllModals() {

    if (window.tb_framework.modal_default) {
        window.tb_framework.modal_default.forEach(function (modal_default) {
            if (modal_default.isOpen) {
                modal_default.close();
            }
        });
    }

    if (window.tb_framework.modal_login) {
        window.tb_framework.modal_login.forEach(function (modal_login) {
            if (modal_login.isOpen) {
                modal_login.close();
            }
        });
    }

    if (window.tb_framework.modal_add_to_cart) {
        window.tb_framework.modal_add_to_cart.forEach(function (modal_add_to_cart) {
            if (modal_add_to_cart.isOpen) {
                modal_add_to_cart.close();
            }
        });
    }

    if (window.tb_framework.modal_confirmation) {
        window.tb_framework.modal_confirmation.forEach(function (modal_confirmation) {
            if (modal_confirmation.isOpen) {
                modal_confirmation.close();
            }
        });
    }
}

window.addEventListener('popstate', function (event) {
    // This behaviour is very complex/buggy: https://stackoverflow.com/questions/11092736/window-onpopstate-event-state-null
    /* Note: In modal_default.tpl we use pushState() to add a new state to history, but we can't gather this state in popstate eventListener
             The reason is tricky: the history is already cleared by its first entry, that's why we kind of get the second history.
             This also explains why some user use pushState just two times. We don't do this, as it could bring up other drawbacks
     */
    if (checkIfAnyModalIsOpen()) {
        closeAllModals();
    }
    else {
        // Note: popstate is also triggered when clicking on anchor
        var currentURL = window.location.href;

        if (currentURL.includes('#')) {
            // Scroll a bit up as there is often fixed menu above it...
            setTimeout(function () {
                window.scrollTo({
                    top: window.scrollY - 70, // Aktuelle Y-Position minus 50 Pixel
                    left: 0, // Die horizontale Position bleibt unverändert
                    behavior: 'smooth' // Optional: Scrollen mit Animation
                });
            }, 150);
        }
        else {
            // Jump again back, if the user already closed the modals and if no anchor seems to be clicked
            history.back();
        }
    }
});


function closeAllToasts() {
    // Close all old toasts
    if (window.tb_framework.toast) {
        window.tb_framework.toast.forEach(function (toast) {
            toast.close();
        });
    }
}

// Buy_Block (note: for seo it's much better to have a moving buy_block than two -> duplicate content)
window.addEventListener('resize', function () {
   moveBuyBlock();
});

function moveBuyBlock() {

    var buyBlockContainerMobile = document.getElementById('buy_block_container_mobile');
    var buyBlockContainer = document.getElementById('buy_block_container');
    var buyBlock = document.getElementById('buy_block');

    if (buyBlockContainerMobile && buyBlockContainer && buyBlock) {
        if (window.innerWidth < 1024) {
            buyBlockContainerMobile.appendChild(buyBlock);
        }
        else {
            buyBlockContainer.appendChild(buyBlock);
        }
    }

}

function updateQtyInput(value) {

    var buy_block_visible = document.getElementById('buy_block');

    var qty_input = buy_block_visible.querySelector('#qty_input');
    var qty_select = buy_block_visible.querySelector('#qty_select');

    if (value==='show_qty_input') {
        qty_input.classList.remove('hidden');
        qty_select.classList.add('hidden');
        qty_input.focus();
        qty_input.value = ''; // Make sure that the field is empty
    }
    else {
        qty_input.value = parseInt(value);
        updateBuyBlock();
    }

}

function updateBuyBlock() {

    var buy_block_visible = document.getElementById('buy_block');

    var add_to_cart_button = buy_block_visible.querySelector('#add_to_cart');
    var qty_input = buy_block_visible.querySelector('#qty_input');

    // Make sure qty input is not bigger than max value
    if (qty_input.value > parseInt(qty_input.getAttribute('max'))) {
        qty_input.value = parseInt(qty_input.getAttribute('max'));
    }

    var qty = (parseInt(qty_input.value) > 0) ? parseInt(qty_input.value) : 1;

    var id_product_attribute = findCombination();

    if (id_product_attribute!==null) {
        // Update add_to_cart function
        var new_function = 'addProductToCart(' + id_product + ',' + id_product_attribute + ',' + qty + ');';
        add_to_cart_button.setAttribute('onclick', new_function);

        // Update product price
        updateProductPrice(id_product, true, id_product_attribute, qty, true, buy_block_visible.querySelector('#our_price_display'));
    }

    toggleAttributeRelatedData(id_product_attribute);

}

function toggleAttributeRelatedData(id_product_attribute) {

    // Display and hide all elements with 'data-id-product-attribute-related' depending on selected attribute
    id_product_attribute = parseInt(id_product_attribute);
    var data = document.querySelectorAll('[data-id-product-attribute-related]');

    data.forEach(function (row) {

        var row_id_product_attribute = parseInt(row.getAttribute('data-id-product-attribute-related'));

        if ((id_product_attribute) > 0 && (row_id_product_attribute > 0)) {
            row.style.display = id_product_attribute===row_id_product_attribute ? '' : 'none';
        }
        else {
            row.style.display = ''; // If none is selected we display all values
        }

    });

    // Handle all images that are attribute related
    if (typeof combinationImages!=="undefined") {

        var images = document.querySelectorAll('img[data-id-image]');

        images.forEach(function (image) {
            image.parentElement.classList.add('hidden');
        });

        combinationImages[id_product_attribute].forEach(function (imageToShow) {
            var images = document.querySelectorAll('img[data-id-image="' + imageToShow.id_image + '"]');
            images.forEach(function (image) {
                image.parentElement.classList.remove('hidden');
            });
        });
    }
}

function findCombination() {

    // Check if the product has even attributes
    if (typeof combinations == 'undefined' || !combinations) {
        return 0;
    }

    var id_product_attribute = null;

    var buy_block_visible = document.getElementById('buy_block');

    // Disable qty_select and add_to_cart button -> this should only be available, once the customer has select a valid combination
    var add_to_cart_button = buy_block_visible.querySelector('#add_to_cart');
    var qty_select = buy_block_visible.querySelector('#qty_select');
    var qty_input = buy_block_visible.querySelector('#qty_input');
    add_to_cart_button.disabled = true;
    qty_select.disabled = true;

    var selects = buy_block_visible.querySelectorAll('#attributes select');

    // create a temporary 'selected_values_by_customer' array containing the choices of the customer
    var selected_values_by_customer = [];

    selects.forEach(function (select) {
        selected_values_by_customer.push(parseInt(select.value));
    })

    // Testing every combination to find the combination's attributes' case of the user
    Object.entries(combinations).forEach(function ([id_product_attribute_test, combination]) {

        // Check if the two arrays are equal
        if (combination.attributes.join()===selected_values_by_customer.join()) {
            id_product_attribute = id_product_attribute_test;
        }

    });

    // Update attribute relevant information
    if (id_product_attribute) {

        // Remove disabled from qty_select and add_to_cart_button
        add_to_cart_button.disabled = false;
        qty_select.disabled = false;

        // Check which quantity options should be selectable
        var qty = combinations[id_product_attribute]['quantity'];

        Object.entries(qty_select.options).forEach(function ([key, option]) {

            // Theoretically it would be wishful to even check for the qty_input and reduce it to the max
            if (option.value==='show_qty_input') {
                (qty>=10) ? option.classList.remove('hidden') : option.classList.add('hidden');
            }
            else {
                (qty>=option.value) ? option.classList.remove('hidden') : option.classList.add('hidden');
            }

        });

        // Update max for input
        qty_input.setAttribute('max', qty);

        if (qty_input.value > qty) {
            qty_input.value = qty;
        }

        // Remove all please_select_option
        buy_block_visible.querySelectorAll('.please_select_option').forEach(function (please_select_option) {
            please_select_option.classList.add('hidden');
            please_select_option.previousElementSibling.classList.remove('hidden');
        })

        // Updating reference
        buy_block_visible.querySelector('#product_reference').textContent = combinations[id_product_attribute]['reference'];

        // Updating availability
        buy_block_visible.querySelector('#availability_statut').textContent = qty;

    }

    return id_product_attribute;
}

function updateProductPrice(id_product, use_tax = true, id_product_attribute = 0, qty = 1, format_price = false, element_replace = null) {

    var url_parmeter = 'id_product='+parseInt(id_product)+'&use_tax='+Boolean(use_tax)+'&id_product_attribute='+id_product_attribute+'&qty='+qty+'&format_price='+Boolean(format_price);

    var request = new XMLHttpRequest();
    request.open('GET', '/modules/tb_framework/tb_framework_ajax.php?getProductPrice&'+url_parmeter, true);
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    request.send();

    request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            var price = JSON.parse(this.response);
            if (element_replace) {
                element_replace.textContent = price;
            }
        }
    }
}

// Some copied stuff from tools.js
/*
 * This also uses global priceDisplayPrecision, so this should be right.
 * Formatting should match Tools::displayPrice().
 */
function displayPrice(price, currencyFormat, currencySign, currencyBlank) {
    var currency = 'EUR';

    if (typeof window.currency_iso_code !== 'undefined' && window.currency_iso_code.length === 3) {
        // Should be exposed in Back Office
        currency = window.currency_iso_code;
    } else if (typeof window.currency === 'object' && typeof window.currency.iso_code !== 'undefined' && window.currency.iso_code.length === 3) {
        // Front Office
        currency = window.currency.iso_code;
    }

    if (isNaN(price) || price == '') {
        price = 0;
    }

    if (typeof window['currencyFormatters'] !== 'undefined' && window.currencyFormatters[currency]) {
        var formatter = window.currencyFormatters[currency];
        var val = executeFunctionByName(formatter, [price, currencyFormat, currencySign, currencyBlank, priceDisplayPrecision]);
        if (typeof val === 'string' || val instanceof String) {
            return val;
        }
    }

    var blank = '';
    price = ps_round(price, priceDisplayPrecision);
    if (currencyBlank > 0) {
        blank = ' ';
    }

    // currencyFormat is available in front office, only.
    if (currencyFormat == 1) {
        return currencySign + blank + formatNumber(price, priceDisplayPrecision, ',', '.');
    }
    if (currencyFormat == 2) {
        return (formatNumber(price, priceDisplayPrecision, ' ', ',') + blank + currencySign);
    }
    if (currencyFormat == 3) {
        return (currencySign + blank + formatNumber(price, priceDisplayPrecision, '.', ','));
    }
    if (currencyFormat == 4) {
        return (formatNumber(price, priceDisplayPrecision, ',', '.') + blank + currencySign);
    }
    if (currencyFormat == 5) {
        return (currencySign + blank + formatNumber(price, priceDisplayPrecision, '\'', '.'));
    }
    if (currencyFormat == 6) {
        return (formatNumber(price, priceDisplayPrecision, '.', ',') + blank + currencySign);
    }

    return price.toFixed(priceDisplayPrecision);
}

function ps_round_helper(value, mode) {
    // From PHP Math.c
    if (value >= 0.0) {
        tmp_value = Math.floor(value + 0.5);
        if ((mode == 3 && value == (-0.5 + tmp_value)) ||
            (mode == 4 && value == (0.5 + 2 * Math.floor(tmp_value / 2.0))) ||
            (mode == 5 && value == (0.5 + 2 * Math.floor(tmp_value / 2.0) - 1.0))) {
            tmp_value -= 1.0;
        }
    }
    else {
        tmp_value = Math.ceil(value - 0.5);
        if ((mode == 3 && value == (0.5 + tmp_value)) ||
            (mode == 4 && value == (-0.5 + 2 * Math.ceil(tmp_value / 2.0))) ||
            (mode == 5 && value == (-0.5 + 2 * Math.ceil(tmp_value / 2.0) + 1.0))) {
            tmp_value += 1.0;
        }
    }

    return tmp_value;
}

function ps_log10(value) {
    return Math.log(value) / Math.LN10;
}

function ps_round_half_up(value, precision) {
    var mul = Math.pow(10, precision);
    var val = value * mul;

    var next_digit = Math.floor(val * 10) - 10 * Math.floor(val);
    if (next_digit >= 5) {
        val = Math.ceil(val);
    } else {
        val = Math.floor(val);
    }

    return val / mul;
}

function ps_round(value, places) {
    if (typeof(roundMode) === 'undefined') {
        roundMode = 2;
    }
    if (typeof(places) === 'undefined') {
        places = 2;
    }
    value = parseFloat(value);
    if (isNaN(value)) {
        return 0;
    }

    var method = roundMode;

    if (method === 0) {
        return ceilf(value, places);
    } else if (method === 1) {
        return floorf(value, places);
    } else if (method === 2) {
        return ps_round_half_up(value, places);
    } else if (method == 3 || method == 4 || method == 5) {
        // From PHP Math.c
        var precision_places = 14 - Math.floor(ps_log10(Math.abs(value)));
        var f1 = Math.pow(10, Math.abs(places));

        if (precision_places > places && precision_places - places < 15) {
            var f2 = Math.pow(10, Math.abs(precision_places));
            if (precision_places >= 0) {
                tmp_value = value * f2;
            } else {
                tmp_value = value / f2;
            }

            tmp_value = ps_round_helper(tmp_value, roundMode);

            /* now correctly move the decimal point */
            f2 = Math.pow(10, Math.abs(places - precision_places));
            /* because places < precision_places */
            tmp_value /= f2;
        }
        else {
            /* adjust the value */
            if (places >= 0) {
                tmp_value = value * f1;
            } else {
                tmp_value = value / f1;
            }

            if (Math.abs(tmp_value) >= 1e15) {
                return value;
            }
        }

        tmp_value = ps_round_helper(tmp_value, roundMode);
        if (places > 0) {
            tmp_value = tmp_value / f1;
        } else {
            tmp_value = tmp_value * f1;
        }

        return tmp_value;
    }
}

// return a formatted number
function formatNumber(value, numberOfDecimal, thousenSeparator, virgule) {
    value = value.toFixed(numberOfDecimal);
    var val_string = value + '';
    var tmp = val_string.split('.');
    var abs_val_string = (tmp.length === 2) ? tmp[0] : val_string;
    var deci_string = ('0.' + (tmp.length === 2 ? tmp[1] : 0)).substr(2);
    var nb = abs_val_string.length;

    for (var i = 1; i < 4; i++) {
        if (value >= Math.pow(10, (3 * i))) {
            abs_val_string = abs_val_string.substring(0, nb - (3 * i)) + thousenSeparator + abs_val_string.substring(nb - (3 * i));
        }
    }

    if (parseInt(numberOfDecimal) === 0) {
        return abs_val_string;
    }
    return abs_val_string + virgule + (deci_string > 0 ? deci_string : '00');
}
