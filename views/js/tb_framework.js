window.tb_framework = {
    'modal_default' : {
        'components' : {},
        'open' : 0
    }
};

addEventListener('DOMContentLoaded', (event) => {

    Object.entries(window.tb_framework).forEach(component_names => {

        const [component_name, componentsObject] = component_names;

        Object.entries(componentsObject.components).forEach(components => {
            const [id_component, component] = components;
            initComponent(component_name, component);
        });

    });
});

function getComponent(component_name, id) {

    if (window.tb_framework[component_name]['components'][id]) {
        return window.tb_framework[component_name]['components'][id];
    }

    return false;
}

function addComponent(component_name, component) {

    if (!window.tb_framework[component_name]) {
        window.tb_framework[component_name] = {
            'components' : {}
        };
    }

    window.tb_framework[component_name]['components'][component.id] = component;
}

function initComponent(component_name, component) {

    // Check if the component contains the init function -> if yes trigger it
    if (typeof component.init === 'function') {
        component.init();
    }

    console.log(tb_framework);

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
    request.send(
        'component_name=' + component_name +
        '&data=' + encodeURIComponent(JSON.stringify(data)) +
        '&style=' + style
    );

    request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            var response = JSON.parse(this.response);
            if (response.content) {
                initAjaxComponent(response.content, relative_element, relative_position);

                var component = getComponent(component_name, response.id);
                initComponent(component_name, component);

                if (typeof callback_function == "function") {
                    if (component) {
                        callback_function(component);
                    }
                }
            }
        }
    }

}

// Note: when you load html by ajax, the script tags and external files aren't executed/loaded by just adding the html div
// This function does handle this "problem".
function initAjaxComponent(content, relative_element = document.body, relative_position = 'append') {

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

    // Todo: before we had component_helper.firstElementChild -> this made problems for modal_default, as there are two elements
    // Todo:  Check If it works now correctly for all elements
    var component = component_helper;

    // Note: insertAdjacentHTML has the drawback, that the callback element is not usable
    if (relative_position === 'prepend') {
        relative_element.prepend(component);
    } else if (relative_position === 'append') {
        relative_element.appendChild(component);
    }
    else if (relative_position === 'replace') {
        relative_element.outerHTML = component.outerHTML;
    }

    css_files.forEach(function (href) {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = href;

        document.head.appendChild(link);
    });

    // Add css blocks
    css_blocks.forEach(function (css_code) {
        var style_inner = document.createElement('style');
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
                    loadJsBlocks(js_blocks);
                }
            });

        });
    }
    else {
        loadJsBlocks(js_blocks);
    }

    document.dispatchEvent(new Event('TbFrameworkInitComponents'));

    // Return clean javascript element
    return component;
}

function loadJsBlocks(js_blocks) {
    if (js_blocks.length) {
        js_blocks.forEach(function (js_code) {
            var script_inner = document.createElement('script');
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

// Buy_Block
function updateQtyInput(value) {

    // Todo: make this work with max quantities

    var buy_block_visible = getVisibleBuyBlock();

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

    var buy_block_visible = getVisibleBuyBlock();

    var add_to_cart_button = buy_block_visible.querySelector('#add_to_cart');
    var qty_input = buy_block_visible.querySelector('#qty_input');

    var qty = (parseInt(qty_input.value) > 0) ? parseInt(qty_input.value) : 1;

    var id_product_attribute = findCombination();

    if (id_product_attribute!==null) {
        // Update add_to_cart function
        var new_function = 'addProductToCart(' + id_product + ',' + id_product_attribute + ',' + qty + ');';
        add_to_cart_button.setAttribute('onclick', new_function);

        // Update product price
        updateProductPrice(id_product, true, id_product_attribute, qty, true, buy_block_visible.querySelector('#our_price_display'));
    }

}

// Note: we prefer to add to buy_blocks into the html (desktop vs mobile) instead of moving it with js
// That way we have no content shift after loading
function getVisibleBuyBlock() {

    var buy_block_visible = false;
    var buy_blocks = document.querySelectorAll('#buy_block');

    buy_blocks.forEach(function (buy_block) {
        if (buy_block.offsetParent!==null) {
            buy_block_visible = buy_block;
        }
    });

    return buy_block_visible;
}

function findCombination() {

    // Check if the product has even attributes
    if (typeof combinations == 'undefined' || !combinations) {
        return 0;
    }


    var id_product_attribute = null;

    var buy_block_visible = getVisibleBuyBlock();

    // Disable qty_select and add_to_cart button -> this should only be available, once the customer has select a valid combination
    var add_to_cart_button = buy_block_visible.querySelector('#add_to_cart');
    var qty_select = buy_block_visible.querySelector('#qty_select');
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
        console.log(combinations[id_product_attribute]);
        console.log(combinationsFromController[id_product_attribute]);

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


    if (id_product_attribute) {



        return;

        if (combinations[combination]['minimal_quantity'] > 1)
        {
            $('#minimal_quantity_label').html(combinations[combination]['minimal_quantity']);
            $('#minimal_quantity_wanted_p').fadeIn();
            $('#quantity_wanted').val(combinations[combination]['minimal_quantity']);
            $('#quantity_wanted').bind('keyup', function() {checkMinimalQuantity(combinations[combination]['minimal_quantity']);});
        }

        //combination of the user has been found in our specifications of combinations (created in back office)
        selectedCombination['unavailable'] = false;

        //get the data of product with these attributes
        quantityAvailable = combinations[combination]['quantity'];
        selectedCombination['price'] = combinations[combination]['price'];
        selectedCombination['unit_price'] = combinations[combination]['unit_price'];
        selectedCombination['specific_price'] = combinations[combination]['specific_price'];

        /*if (combinations[combination]['ecotax'])
            selectedCombination['ecotax'] = combinations[combination]['ecotax'];
        else
            selectedCombination['ecotax'] = default_eco_tax;*/

        //show the large image in relation to the selected combination
        if (combinations[combination]['image'] && combinations[combination]['image'] != -1) {
            displayImage($('#thumb_' + combinations[combination]['image']).parent());
        }

        //show discounts values according to the selected combination
        if (combinations[combination]['idCombination'] && combinations[combination]['idCombination'] > 0) {
            displayDiscounts(combinations[combination]['idCombination']);
        }


        //get available_date for combination product
        selectedCombination['available_date'] = combinations[combination]['available_date'];

        // update the display
        // updateDisplay();

        if (typeof(firstTime) != 'undefined') {
            // refreshProductImages(0);
        }
        else {
            // refreshProductImages(combinations[combination]['idCombination']);
        }
        //leave the function because combination has been found
        return;
    }


    //this combination doesn't exist (not created in back office)
    selectedCombination['unavailable'] = true;
    if (typeof(selectedCombination['available_date']) != 'undefined')
        delete selectedCombination['available_date'];

    // updateDisplay();
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

