(function(global, doc) {
    const SELECTOR_FIELD = '.ez-data-source__field--meta-robots';
    const SELECTOR_SELECTED = '.ez-custom-dropdown__selection-info';
    const SELECTOR_SOURCE_INPUT = '.ez-data-source__input';
    const EVENT_VALUE_CHANGED = 'valueChanged';

    class EzSelectionValidator extends global.eZ.BaseFieldValidator {
        /**
         * Validates the textarea field value
         *
         * @method validateInput
         * @param {Event} event
         * @returns {Object}
         * @memberof EzSelectionValidator
         */
        validateInput(event) {console.log(event.currentTarget);
            const fieldContainer = event.currentTarget.closest(SELECTOR_FIELD);
            console.log(fieldContainer);
            const hasSelectedOptions = !!fieldContainer.querySelectorAll('.ez-custom-dropdown__selected-item').length;
            const isRequired = fieldContainer.classList.contains('ez-field-edit--required');
            const isError = isRequired && !hasSelectedOptions;

            const label = fieldContainer.querySelector('.ez-data-source__label').innerHTML;
            const errorMessage = global.eZ.errors.emptyField.replace('{fieldName}', label);

            return {
                isError,
                errorMessage,
            };
        }
    }

    const validator = new EzSelectionValidator({
        classInvalid: 'is-invalid',
        fieldSelector: SELECTOR_FIELD,
        eventsMap: [
            {
                selector: '.ez-data-source__input--selection',
                eventName: EVENT_VALUE_CHANGED,
                callback: 'validateInput',
                errorNodeSelectors: ['.ez-field-source__label-wrapper'],
                invalidStateSelectors: [SELECTOR_SELECTED],
            },
        ],
    });

    validator.init();

    global.eZ.fieldTypeValidators = global.eZ.fieldTypeValidators ? [...global.eZ.fieldTypeValidators, validator] : [validator];

    doc.querySelectorAll(SELECTOR_FIELD).forEach((container) => {
        const dropdown = new global.eZ.core.CustomDropdown({
            container,
            itemsContainer: container.querySelector('.ez-custom-dropdown__items'),
            sourceInput: container.querySelector(SELECTOR_SOURCE_INPUT),
        });

        dropdown.init();
    });
})(window, document);
