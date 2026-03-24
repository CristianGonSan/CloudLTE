class LivewireSelect2Builder {

    constructor(wireInstance = null) {
        this.select2Config = {
            allowClear: true,
            theme: 'bootstrap4',
            language: 'es',
            width: '100%',
            placeholder: 'Seleccionar...',
            minimumInputLength: 1,
            dropdownAutoWidth: true,
        };

        this.internal = {
            $wire: wireInstance,
            live: false
        };

        this.valueOption = {
            id: null,
            text: null
        }
    }

    wire(wireInstance) {
        this.internal.$wire = wireInstance;
        return this;
    }

    selector(selector) {
        this.internal.selector = selector;
        return this;
    }

    wireModel(model) {
        this.internal.wireModel = model;
        return this;
    }

    live(live = true) {
        this.internal.live = live;
        return this;
    }

    value(id, text) {
        this.valueOption.id = id;
        this.valueOption.text = text;
        return this;
    }

    appendConfig(config) {
        Object.assign(this.select2Config, config);
        return this;
    }

    build() {
        const { $wire, selector, wireModel, live } = this.internal;
        const { id, text } = this.valueOption;

        if (!$wire || !selector) {
            throw new Error('LivewireSelect2Builder: configuración incompleta');
        }

        const select2 = $(selector).select2(this.select2Config);

        if (id && text) {
            select2.append(new Option(text, id, true, true)).trigger('change');
            this.valueOption.id = null;
            this.valueOption.text = null;
        }

        if (wireModel) {
            select2.on('change', function () {
                $wire.set(wireModel, $(this).val(), live);
            });
        }

        return select2;
    }

}
