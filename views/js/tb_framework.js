window.tb_framework = {};
window.tb_framework.modals_open = 0;

function getComponent(component_name, id) {

    if (window.tb_framework[component_name][id]) {
        return window.tb_framework[component_name][id];
    }

    return false;
}

function addComponent(component_name, object) {

    if (!window.tb_framework[component_name]) {
        window.tb_framework[component_name] = {};
    }

    window.tb_framework[component_name][object.id] = object;
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
                initAjaxComponent(response.content);
                if (typeof callback_function == "function") {
                    var component = getComponent(component_name, response.id);
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

    var component = component_helper.firstElementChild;

    // Note: insertAdjacentHTML has the drawback, that the callback element is not usable
    if (relative_position === 'prepend') {
        relative_element.prepend(component);
    } else if (relative_position === 'append') {
        relative_element.appendChild(component);
    }

    // Todo: it's not clear, but maybe we need to check if an external file was already loaded. Probably a browser does anyways use cache.

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

// Used for outside click handling
function hasSomeParentTheId(element, id) {
    if (element.id && typeof element.id==='string' && element.id.split(' ').indexOf(id)>=0) return element;
    return element.parentNode && hasSomeParentTheId(element.parentNode, id);
}

function hasSomeParentTheClass(element, classname) {
    if (element.className && typeof element.className==='string' && element.className.split(' ').indexOf(classname)>=0) return element;
    return element.parentNode && hasSomeParentTheClass(element.parentNode, classname);
}