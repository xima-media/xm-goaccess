define([], function () {
    const BackendModuleDkfz = {
        table: null,
        typeButtons: null,
        searchInput: null,
    };

    BackendModuleDkfz.init = function () {

        this.cacheDom()
        this.bindTypeSwitchButtons()
        this.bindSearchInput()
        this.updateEntryCount()
    }

    BackendModuleDkfz.cacheDom = function () {
        this.table = document.querySelector('#dkfz-table')
        this.typeButtons = document.querySelectorAll('.type-switch')
        this.searchInput = document.querySelector('#dkfz-search input.form-control')
        this.entryCount = document.querySelectorAll('span[data-entry-count]')
    }

    BackendModuleDkfz.bindTypeSwitchButtons = function () {
        this.typeButtons.forEach(button => {
            button.addEventListener('click', this.onTypeSwitchButtonClick.bind(this))
        })
    }

    BackendModuleDkfz.onTypeSwitchButtonClick = function (e) {
        e.preventDefault()

        // button state
        this.typeButtons.forEach(button => {
            button.classList.remove('active')
        })
        e.currentTarget.classList.add('active')

        // filter
        this.table.querySelectorAll('tbody tr').forEach(tr => {
            tr.classList.add('hidden')
        })
        const newType = e.currentTarget.getAttribute('href').substring(1)
        this.table.querySelectorAll('tbody tr[data-type="' + newType + '"]').forEach(tr => {
            tr.classList.remove('hidden')
        })

        this.updateEntryCount()
    }

    BackendModuleDkfz.updateEntryCount = function() {
        const count = this.table.querySelectorAll('tbody tr:not([class^="hidden"])').length
        this.entryCount.forEach(span => span.innerHTML = count.toString());
    }

    BackendModuleDkfz.bindSearchInput = function () {
        this.searchInput.addEventListener('keyup', this.onSearchInput.bind(this))
    }

    BackendModuleDkfz.onSearchInput = function (e) {
        const val = e.currentTarget.value.replace(/ +/g, ' ').toLowerCase()

        this.table.querySelectorAll('tbody tr').forEach(tr => {
            const trContent = tr.textContent.replace(/\s+/g, ' ').toLowerCase()
            if (!~trContent.indexOf(val)) {
                tr.classList.add('hidden-filter')
            } else {
                tr.classList.remove('hidden-filter')
            }
        })

        this.updateEntryCount()
    }

    // To let the module be a dependency of another module, we return our object
    return BackendModuleDkfz.init();
});
