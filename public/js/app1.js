
    let listGroupCounter = 1;
    let fieldCounter = 1;


$(document).ready(function () {

    
    initializeEventListeners();
    makeElementsDraggable();
    setupDroppableZone();
    setupSortableAreas();
});

function initializeEventListeners() {
    $(".close-builder-section, .add-form-elements, .close-preview-btn, .show-preview-btn").click(toggleSections);
    $('.form-builder-area').on('click', '.close-item-btn', removeFormElement);
    $('.form-builder-area').on('click', '.duplicate-item-btn', duplicateFormElement);
    $('.form-builder-area').on('click', '.add-option-btn', addOptionToListGroup);
    $('.list-group').on('click', '.delete-item-btn', deleteListItem);
}



function toggleSections() {
    const actionClasses = {
        "close-builder-section": { toggle: [".form-builder-items", ".form-builder-menu"] },
        "add-form-elements": { toggle: [".form-builder-menu", ".form-builder-items"] },
        "close-preview-btn": { toggle: [".form-preview-section", ".form-builder"], callback: null },
        "show-preview-btn": { toggle: [".form-builder", ".form-preview-section"], callback: extractFormData }
    };
    const classes = $(this).attr('class').split(' ');
    let found = false;

    for (let i = 0; i < classes.length; i++) {
        const action = actionClasses[classes[i]];
        if (action) {
            action.toggle.forEach(selector => $(selector).toggleClass('hide'));
            if (action.callback) action.callback();
            found = true;
            break;
        }
    }

    if (!found) {
        console.log("No matching action found for classes:", classes);
    }
}

function makeElementsDraggable() {
    $('.draggable').draggable({
        revert: false,
        helper: 'clone'
    });
}

function setupDroppableZone() {
    $('.element-drop-zone').droppable({
        accept: '.draggable',
        drop: function (event, ui) {
            const elementType = $(ui.draggable).data('type');
            elementType === 'text' ? addTextInput() : elementType === 'radio' ? addRadioInput() : null;
            $('.form-empty-state').addClass('hide');
        }
    });
}

function setupSortableAreas() {
    $('#sortable-area, .list-group').sortable({
        handle: '.move-item-btn, .move-list-item-btn',
        update: saveFormData()
    });
}

function removeFormElement() {
    $(this).closest('.form-element-section').remove();
    checkIfFormIsEmpty();
    saveFormData();
}

function duplicateFormElement() {
    const originalElement = $(this).closest('.form-element-section');
    const clonedElement = originalElement.clone();
    updateClonedElementIds(clonedElement);
    originalElement.after(clonedElement);
    saveFormData();
}

function addOptionToListGroup() {
    const listGroup = $(this).siblings('.list-group');
    const newOption = createListOption();
    listGroup.append(newOption);
    saveFormData();
}

function deleteListItem() {
    $(this).closest('li').remove();
    saveFormData();
}

function checkIfFormIsEmpty() {
    const isEmpty = $('.form-builder-area .form-element-section').length === 0;
    $('.form-empty-state').toggleClass('hide', !isEmpty);
}

function addTextInput() {
    const textInputSection = $('#text-field').clone().removeAttr('id');
    textInputSection.addClass("new-field-section").removeClass('hide');
    updateElementIdAndListeners(textInputSection);
    $('.form-builder-area').append(textInputSection);
    fieldCounter++;
}

function addRadioInput() {
    const radioInputSection = $('#radio-field').clone().removeAttr('id');
    radioInputSection.addClass("new-field-section").removeClass('hide');
    updateElementIdAndListeners(radioInputSection);
    $('.form-builder-area').append(radioInputSection);
    fieldCounter++;
}

function updateElementIdAndListeners(element) {
    const newId = element.data('type') + '-field-' + fieldCounter;
    element.attr('id', newId);

    if (element.data('type') === 'radio') {
        const listGroupId = 'list-group-' + listGroupCounter++;
        element.find('.list-group').attr('id', listGroupId);
    }

    saveFormData()
}

function createListOption() {
    return $('<li class="list-group-item">' +
        '<div class="item-content radio-options">' +
        '<input type="radio" name="options" class="option-radio">' +
        '<span name="radio_option" contenteditable="true">Type your option</span>' +
        '</div>' +
        '<div class="item-buttons">' +
        '<span class="move-list-item-btn">&nbsp;&nbsp;&nbsp;</span>' +
        '<button class="delete-item-btn"></button>' +
        '</div>' +
        '</li>');
}

function saveFormData() {
    let formData = [];

    $('.form-builder-area .form-element-section').each(function () {
        let elementData = extractElementData($(this));
        formData.push(elementData);
    });

    console.log("Final FormData: ", formData);

    $.ajax({
        url: storeFormUrl,
        type: 'POST',
        data: JSON.stringify(formData, null, 2),
        contentType: 'application/json',
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        success: function(response) {
            console.log("Data saved successfully:", response);
        },
        error: function(error) {
            console.error("Error saving data:", error);
        }
    });
}

function extractElementData(element) {
    console.log("Extracting data for element: ", element);

    let data = {
        type: element.data('type'),
        id: element.attr('id'),
        title: element.find('.field-heading').text(),
        description: element.find('.field-description').text(),
        settings: extractSettings(element)
    };

    if (data.type === 'radio') {
        data.options = extractRadioOptions(element);
    }
    console.log("Extracted Data: ", data);

    return data;
}

function extractSettings(element) {
    return {
        required: element.find('.required-setting').is(':checked')
    };
}

function extractRadioOptions(element) {
    let options = [];
    element.find('.list-group-item').each(function () {
        options.push({
            option: $(this).find('[name="radio_option"]').text()
        });
    });
    return options;
}

function extractFormData() {
    let formData = [];

    $('.form-builder-area .form-element-section').each(function () {
        let elementData = extractElementData($(this));
        formData.push(elementData);
    });

    $('.form-preview').empty();

    formData.forEach(function (item) {
        let formElement = createFormElement(item);
        $('.form-preview').append(formElement);
    });
}

function createFormElement(item) {
    if (item.type === 'text') {
        return createTextInput(item);
    } else if (item.type === 'radio') {
        return createRadioGroup(item);
    }

    saveFormData();
}

function createTextInput(item) {
    let textInputHtml = '<div class="form-group">';
    textInputHtml += `<label for="${item.id}">${item.title}</label>`;
    textInputHtml += `<input type="text" class="form-control" id="${item.id}" `;

    if (item.settings.required) {
        textInputHtml += 'required ';
    }

    if (item.settings.maxCharactersEnabled) {
        textInputHtml += `maxlength="${item.settings.maxCharacters}" `;
    }

    textInputHtml += '>';

    if (item.description) {
        textInputHtml += `<small class="form-text text-muted">${item.description}</small>`;
    }

    textInputHtml += '</div>';
    return textInputHtml;

    
}


function createRadioGroup(item) {
    let radioGroupHtml = '<div class="form-group">';
    radioGroupHtml += `<label>${item.title}</label>`;

    item.options.forEach(function (option, index) {
        radioGroupHtml += '<div class="form-check">';
        radioGroupHtml += `<input class="form-check-input" type="radio" name="${item.id}" id="${item.id}_${index}" `;
        
        if (item.settings.required) {
            radioGroupHtml += 'required ';
        }

        radioGroupHtml += `>`;
        radioGroupHtml += `<label class="form-check-label" for="${item.id}_${index}">${option.option}</label>`;
        radioGroupHtml += '</div>';
    });

    if (item.description) {
        radioGroupHtml += `<small class="form-text text-muted">${item.description}</small>`;
    }

    radioGroupHtml += '</div>';
    return radioGroupHtml;

}
