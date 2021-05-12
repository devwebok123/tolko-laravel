(function ($) {
    'use strict';

    const aGridDefaults = {
        events: {},
        opacity: '0.6',
        notResultsEmpty: true,
        filterForm: false,
        url: [],
        actions: [],
        bulkForms: [],
        panelActions: [],
        columns: [],
        additionRow: false,
        selectorRow: false,
        items: [],
        sort: {
            dir: 'desc',
            attr: 'id',
        },
        count: 0,
        perPage: 20,
        page: 1,
        queryParams: [],
        perPageSelector: true,
        perPageData: [20, 50, 100],
        theadPanelCols: {
            pager: 'col-sm-3',
            actions: 'col-sm-6',
            filter: 'col-sm-3'
        },
        emptyText: 'Список пустой ...',
        filterPanel: false,
        columnFilterPanel: [],
    };

    const aGridFilters = [];

    function aGrid(el, options) {

        const opts = $.extend({}, aGridDefaults, options);
        const $aGrid = $(el);

        if (opts.filterForm) {
            applyFilterFormParams($(opts.filterForm));
        }

        sendRequest();

        function buildUrl(useDefault = true) {

            var urlDel = (opts.url.split("?").length - 1) ? '&' : '?';

            var url = opts.url + urlDel + 'sort_dir=' + opts.sort.dir + '&sort_attr=' + opts.sort.attr;

            $.each(opts.queryParams, function (i, param) {
                url += '&' + param;
            })

            if (opts.page > 1) {
                url += '&page=' + opts.page;
            }

            if (opts.perPage) {
                url += '&per_page=' + opts.perPage;
            }

            $.each(opts.columnFilterPanel, (index, filter) => {
                // Filter Value
                if (filter.input == 'range') {
                    var filterValueFrom = $('#' + filter.id + '_from').val();
                    var filterValueTo = $('#' + filter.id + '_to').val();
                } else {
                    var filterValue = $('#' + filter.id).val();
                    if (useDefault && filter.value) {
                        var filterValue = filter.value;
                    }
                }

                if (filter.input == 'range') {

                    if (filterValueFrom === undefined | filterValueFrom == '') {
                        delete aGridFilters [filter.name + '_from'];
                    } else {
                        url += '&' + filter.name + '_from' + '=' + filterValueFrom;
                        aGridFilters [filter.name + '_from'] = filterValueFrom;
                    }

                    if (filterValueTo === undefined | filterValueTo == '') {
                        delete aGridFilters [filter.name + '_to'];
                    } else {
                        url += '&' + filter.name + '_to' + '=' + filterValueTo;
                        aGridFilters [filter.name + '_to'] = filterValueTo;
                    }

                } else {
                    if (filterValue === undefined | filterValue == '') {
                        delete aGridFilters [filter.name];
                    } else {
                        url += '&' + filter.name + '=' + filterValue;
                        aGridFilters [filter.name] = filterValue;
                    }
                }

            });

            return url;
        }

        function hasFilterPanel() {
            return (typeof opts.filterPanel !== 'undefined') && (typeof opts.filterPanel.inputs !== 'undefined');
        }

        function sendRequest(useDefault = true) {
            if (typeof opts.events.beforeSendRequest === 'function') {
                opts.events.beforeSendRequest(opts);
            }

            $aGrid.css({opacity: opts.opacity});
            $.ajax({
                method: 'GET',
                url: buildUrl(useDefault),
            })
                .done(function (response) {
                    renderTable(response, useDefault);
                    toggleTheadPanel();
                    $aGrid.css({opacity: '1'});
                })
        }

        function getSortingClass(name) {
            var cls = 'sorting';
            if (name === opts.sort.attr) {
                cls += '_' + opts.sort.dir;
            }

            return cls;
        }

        function toogleDisabledBulkForms(value) {
            $.each(opts.bulkForms, function (i, formSelector) {
                var form = $(formSelector);
                $('[type=submit]', form).prop('disabled', value);
            })
        }

        function attachIdsToBulkForms() {
            var ids = getSelectedRowsIds();
            $.each(opts.bulkForms, function (i, formSelector) {
                var form = $(formSelector);
                $('.js-bulk-id', form).remove();
                $.each(ids, function (j, id) {
                    form.append('<input type="hidden" name="ids[]" class="js-bulk-id" value="' + id + '">');
                })
            })
        }

        function renderTable(data, useDefault = true) {
            renderThead(data, useDefault);
            renderTbody(data);
            renderTfoot(data);
            if (typeof opts.events.afterRender === 'function') {
                opts.events.afterRender(opts, data);
            }
        }

        function renderTbody(data) {
            $('tbody', $aGrid).remove();

            if (data.meta.total) {
                var tbody = $aGrid.append('<tbody></tbody>');
                $.each(data.items, function (i, item) {
                    var trClass = "ag-main-row";
                    if (opts.additionRow !== false && item.id !== null) {
                        trClass += ' ag-has-addition-row';
                    }
                    if (item.id === null) {
                        trClass += ' font-red';
                    }
                    if (typeof opts.addRowClass === 'function') {
                        trClass += ' ' + opts.addRowClass(item);
                    }

                    var tr = $('<tr class="' + trClass + '" ' + attr(item, 'id', 'data-id') + ' ></tr>');
                    if (opts.selectorRow) {
                        tr.append('<td>' + (item.id !== null ? `<input type="checkbox" class="ag-checkbox-row" value="${item.id}">` : '') + '</td>');
                    }
                    $.each(opts.columns, function (k, column) {
                        if (typeof item[column.name] !== undefined) {
                            var tdValue = (typeof column.render === 'function') ? column.render(item) : item[column.name];
                            if (tdValue === null) {
                                tdValue = '';
                            }
                            tr.append('<td data-name="' + column.name + '">' + tdValue + '</td>');
                        }
                    })
                    tbody.append(tr);

                    // render row actions
                    if (typeof opts.rowActions === 'object') {
                        var rowActionsTD = $('<td style="color: black !important;"></td>');
                        $.each(opts.rowActions, function (i, rowAction) {
                            var href = '#';
                            var onClickClass = 'ag-action';
                            if (typeof rowAction.url !== 'undefined') {
                                href = (typeof rowAction.url === 'function') ? rowAction.url(item) : rowAction.url;
                            }
                            if (typeof rowAction.onClickClass !== 'undefined') {
                                onClickClass = rowAction.onClickClass;
                            }

                            var rowActionBtn = $(`<a class="ml-1 btn btn-xs btn-${rowAction.btn} ${onClickClass}"
                                                    href="${href}"
                                                    ${attr(rowAction, 'title')}
                                                    ${attr(rowAction, 'target')}
                                                    ${attr(rowAction, 'refresh', 'data-refresh')}
                                                    ${attr(rowAction, 'method', 'data-method')}
                                                    ${attr(rowAction, 'success', 'data-success')}
                                                    ${attr(rowAction, 'confirm', 'data-confirm')}>
                                                <i class="fas ${rowAction.icon}"></i>
                                            </a>`);

                            if (typeof rowAction.action !== 'undefined') {
                                rowActionBtn.addClass('js-action-' + rowAction.action);
                            }

                            rowActionsTD.append(rowActionBtn);
                        })
                        tr.append(rowActionsTD);
                    }

                    // render collspace row
                    if (typeof opts.additionRow.type !== 'undefined' && item.id !== null) {
                        if (opts.additionRow.type === 'render' && typeof opts.additionRow.render === 'function') {
                            /*
                            tbody.append(
                                `<tr class="ag-addition-row ag-hidden">
                                    <td><span class="addition-row-toggle" title="Скрить"><i class="icon icon-st"></i></span></td>
                                    <td id="addition-row-content-${item.id}" colspan="${getCountColumns()-1}">${opts.additionRow.render(item)}</td>
                                </tr>`
                            );
                            */

                            tbody.append(
                                `<tr class="ag-addition-row ag-hidden">
                                <td id="addition-row-content-${item.id}" colspan="${getCountColumns()}">${opts.additionRow.render(item)}</td>
                            </tr>`
                            );
                        } else if (opts.additionRow.type === 'ajax' && typeof opts.additionRow.url === 'function') {
                            var url = opts.additionRow.url(item);
                            tbody.append(
                                `<tr class="ag-addition-row ag-hidden">
                                <td class="js-content" colspan="${getCountColumns()}" data-url="${url}"></td>
                            </tr>`
                            );
                        }
                    }
                })
            }

            $('select.select2', tbody).select2();
        }

        function renderThead(data, useDefault = true) {

            if (!$('thead', $aGrid).length) {
                var theadHtml = '<thead>';
                theadHtml += renderTheadPanel(data);
                theadHtml += '</thead>';
                $aGrid.html(theadHtml)
                $('select.select2', $aGrid).select2();
            } else {
                $('.js-pager-summary', $aGrid).html(getPagerSummary(data));
                $('.js-tr-attributes', $aGrid).remove();
                $('.js-tr-filters', $aGrid).remove();
            }

            $('.js-has-data', $aGrid).show();
            $('thead', $aGrid).append(renderTheadAttributes());
            $('thead', $aGrid).append(renderTheadFilters(useDefault));
            $('thead', $aGrid).append(renderTheadFiltersAutocomplete());
        }

        function renderTheadAttributes() {
            var html = '<tr class="js-tr-attributes">';
            if (opts.selectorRow) {
                html += '<th><input type="checkbox" class="ag-check-all"></th>';
            }

            $.each(opts.columns, function (i, column) {
                var thClass = '';

                if (column.sort !== false) {
                    thClass = 'ag-sort ' + getSortingClass(column.name);
                }

                html += '<th class="' + thClass + '" data-name="' + column.name + '"' + (column.width ? 'width="' + column.width + '"' : '') + '>' + column.label + '</th>';
            })

            if (typeof opts.rowActions === 'object') {
                html += '<th>&nbsp;</th>';
            }
            html += '</tr>';

            return html;
        }

        function renderTheadFilters(useDefault = true) {
            // Create TR
            if (!opts.columnFilterPanel.length) {
                return;
            }
            var html = '<tr class="js-tr-filters">';

            // Wrap delete checkboxes
            if (opts.selectorRow) {
                html += '<th>&nbsp;</th>';
            }

            $.each(opts.columns, function (i, column) {

                var hasFilter = false;

                $.each(opts.columnFilterPanel, (index, filter) => {

                    // Add input - if filter is set
                    if (column.name == filter.name) {
                        // Create input
                        var input = 'input';
                        if (filter.input) {
                            input = filter.input;
                        }

                        // Set type
                        var type = 'text';
                        if (filter.type) {
                            type = filter.type;
                        }

                        // Set step if exists
                        var step = '';
                        if (filter.step) {
                            step = ' step="' + filter.step + '" ';
                        }

                        //
                        var cssClass = 'filter-input-event';
                        if (filter.autocomplete) {
                            cssClass = '';
                        }
                        if (input == 'select') {
                            cssClass = 'filter-select-event';
                        }
                        if (input == 'range') {
                            cssClass = 'filter-range-event';
                        }
                        if (filter.addClass) {
                            cssClass += ' ' + filter.addClass;
                        }
                        var minLength = 0;
                        if (filter.length) {
                            minLength = filter.length;
                        }

                        //
                        var value = '';
                        var valueFrom = '';
                        var valueTo = '';
                        if (useDefault && filter.value) {
                            value = filter.value;
                        }
                        if (aGridFilters [filter.name]) {
                            value = aGridFilters [filter.name];
                        }

                        if (filter.input == 'range') {
                            if (aGridFilters [filter.name + '_from']) {
                                valueFrom = aGridFilters [filter.name + '_from'];
                            }
                            if (aGridFilters [filter.name + '_to']) {
                                valueTo = aGridFilters [filter.name + '_to'];
                            }
                        }

                        var name = '';
                        if (filter.name)

                            if (input == 'input') {
                                var inputHtml = `
                                <input
                                    class="form-control ` + cssClass + `"
                                    type="` + type + `"
                                    ` + step + `
                                    value="` + value + `"
                                    id="` + filter.id + `"
                                    name="` + filter.name + `"
                                    data-length="` + minLength + `"
                                    placeholder="` + filter.placeholder + `">
                            `;
                            }

                        if (input == 'select') {
                            var inputHtml = `<select class="form-control ` + cssClass + `" id="` + filter.id + `">`;
                            inputHtml += `<option value=""></option>`;
                            $.each(filter.source, function (i, param) {
                                inputHtml += `<option value="` + i + `"`;
                                if (value == i) {
                                    inputHtml += ` selected`;
                                }
                                inputHtml += `>` + param + `</option>`;
                            });
                            inputHtml += `</select>`;
                        }

                        if (input == 'range') {
                            var inputHtml = `
                                <div class="row">
                                    <div class="col-6">
                                        <input
                                            class="form-control ` + cssClass + `"
                                            type="` + type + `"
                                            ` + step + `
                                            value="` + valueFrom + `"
                                            id="` + filter.id + `_from"
                                            placeholder="От">
                                    </div>
                                    <div class="col-6">
                                        <input
                                            class="form-control ` + cssClass + `"
                                            type="` + type + `"
                                            ` + step + `
                                            value="` + valueTo + `"
                                            id="` + filter.id + `_to"
                                            placeholder="До">
                                    </div>
                                </div>
                            `;
                        }

                        if (filter.name in aGridFilters) {
                            html += `
                                <td><div class="input-group mb-3">
                                    ` + inputHtml + `
                                </div></td>`;
                        } else {
                            html += '<td>' + inputHtml + '</td>';
                        }

                        hasFilter = true;
                        return false;
                    }

                });

                if (!hasFilter) {
                    html += '<td>&nbsp;</td>';
                }

            });

            // End of TR
            if (typeof opts.rowActions === 'object') {
                html += '<th>&nbsp;</th>';
            }
            html += '</tr>';

            return html;
        }

        function renderTheadFiltersAutocomplete() {
            $.each(opts.columns, function (i, column) {
                $.each(opts.columnFilterPanel, (index, filter) => {
                    // Init autocomplete
                    if (filter.autocomplete) {
                        if ($('#' + filter.id).length > 0) {
                            setAutocomplete(filter);
                        }
                    }
                });
            });
        }


        function getPagerSummary(data) {
            return data.meta.total ?
                `<label class="ml-5">&nbsp;${data.meta.from}-${data.meta.to} из ${data.meta.total}` :
                opts.emptyText;
        }

        function renderTfoot(data) {
            $('tfoot', $aGrid).remove();

            if (data.meta.total) {
                $aGrid.append('<tfoot><tr><td colspan="' + getCountColumns() + '">' + renderPaginate(data) + '</td></tr></tfoot>');
            }
        }

        function renderTheadPanel(data) {
            // render header panel
            let theadPanel = '<tr><td colspan="' + getCountColumns() + '"><div class="row">';

            theadPanel += '<div class="' + opts.theadPanelCols.pager + '">';

            if (opts.perPageSelector) {
                theadPanel += '<label class="ml-2 js-pager-select js-has-data">';
                theadPanel += 'Показывать: <select name="per_page" class="form-control-sm">';

                $.each(opts.perPageData, function (i, val) {
                    let selected = val == opts.perPage ? 'selected="selected"' : '';
                    theadPanel += '<option value="' + val + '" ' + selected + '>' + val + '</option>';
                })
                theadPanel += '</select></label>';
            }

            theadPanel += '<label class="ml-5 js-pager-summary">' + getPagerSummary(data) + '<label>';
            theadPanel += '</div>';

            if (typeof opts.panelActions === 'object') {
                theadPanel += panelActions();
            }

            if (hasFilterPanel()) {
                theadPanel += filterPanel();
            }

            theadPanel += '</div></td></tr>';

            return theadPanel;
        }

        function getCountColumns() {
            let count = opts.columns.length;

            if (opts.selectorRow) {
                count++;
            }
            if (typeof opts.rowActions === 'object') {
                count++;
            }
            return count;
        }

        function renderPaginate(data) {
            let html = '', suffix = ''
            const lastPage = data.meta.last_page

            if (lastPage > 1) {
                const curPage = data.meta.current_page
                html += '<ul class="float-sm-right pagination">' + createPagerLink(1, curPage)

                if (lastPage > 2) {
                    let startInner = 2, endInner = lastPage - 1

                    if (curPage > 4) {
                        startInner = curPage - 2
                        html += '<li class="page-item ml-1 mr-1">...</li>';
                    }
                    if (curPage < lastPage - 3) {
                        endInner = curPage + 2
                        suffix = '<li class="page-item ml-1 mr-1">...</li>';
                    }

                    for (let i = startInner; i <= endInner; i++) {
                        html += createPagerLink(i, curPage);
                    }
                }

                html += suffix + createPagerLink(lastPage, curPage) + '</ul>';
            }
            return html;
        }

        function createPagerLink(pageNum, curPage) {
            return '<li class="page-item' + (curPage == pageNum ? ' active' : '') + '"><a class="page-link" href="#" data-page="' + pageNum + '">' + pageNum + '</a></li>';
        }

        function panelActions() {
            let html = '<div class="' + opts.theadPanelCols.actions + '">';

            $.each(opts.panelActions, function (i, panelItem) {

                if (panelItem.type === 'button') {
                    html += '<button class="js-has-data js-panel-action ag-panel-button btn ' + panelItem.class + ' ml-5" ' +
                        ' data-url="' + panelItem.url + '" data-label="' + panelItem.label +
                        '" data-action="' + panelItem.action + '" ' + attr(panelItem, 'confirm', 'data-confirm') + '>' + panelItem.label + '</button>';
                } else if (panelItem.type === 'select') {
                    let selectClass = 'ag-panel-select form-control';
                    const btnTitle = (typeof panelItem.button !== 'undefined' && typeof panelItem.button.title !== 'undefined') ?
                        panelItem.button.title : 'Применить';
                    const btnClass = (typeof panelItem.button !== 'undefined' && typeof panelItem.button.class !== 'undefined') ?
                        panelItem.button.class : 'btn-success';

                    if (typeof panelItem.class !== 'undefined') {
                        selectClass += ' ' + panelItem.class;
                    }
                    html += '<label class="ml-5 js-has-data">' + panelItem.label + ' <select class="js-panel-action ' + selectClass + '" data-url="' + panelItem.url + '" data-action="' + panelItem.action + '">';
                    html += '<option value="">-</option>';
                    $.each(panelItem.options, function (o_id, o_val) {
                        html += '<option value="' + o_id + '">' + o_val + '</option>';
                    })
                    html += '</select>';
                    html += '<button class="js-panel-select-btn ml-2 btn ' + btnClass + ' hidden">' + btnTitle + '</button>';
                    html += '</label>';
                }
            })
            html += '</div>';

            return html;
        }

        function filterPanel() {
            const conf = opts.filterPanel;

            let html = '<div class="' + opts.theadPanelCols.filter + '"><form id="' + conf.formId + '">';

            $.each(conf.inputs, function (i, input) {
                if (input.type === 'text') {
                    html += '<input class="form-control"' +
                        attr(input, 'type') + ' ' +
                        attr(input, 'id') + ' ' +
                        attr(input, 'id', 'name') + ' ' +
                        attr(input, 'placeholder') + ' ' +
                        ' >';

                } else if (input.type === 'select') {

                } else if (input.type === 'submit') {

                }
            })

            html += '</div></form>';

            return html;
        }

        function attr(obj, k, attrName = false) {
            var val = '';
            if (typeof obj === 'object' && typeof obj[k] !== 'undefined') {
                attrName = attrName ? attrName : k;

                return attrName + '="' + obj[k] + '"';
            }
            return val;
        }

        function formSerialize(form) {
            var items = form.serializeArray();
            var data = [];
            $.each(items, function (i, item) {
                data.push(item.name + '=' + item.value);
            })
            return data;
        }

        function toggleTheadPanel() {
            var disabled = (getSelectedRowsIds().length === 0) ? true : false;
            $('.ag-panel-button, .ag-panel-select, .js-panel-select-btn', $aGrid).each(function () {
                $(this).prop('disabled', disabled);
            })
            toogleDisabledBulkForms(disabled);
            attachIdsToBulkForms();
        }

        function getSelectedRowsIds() {
            var ids = [];
            $.each($(".ag-checkbox-row:checked", $aGrid), function () {
                ids.push(parseInt($(this).val()));
            });
            return ids;
        }

        function setAutocomplete(filterObject) {

            var filterInput = $('#' + filterObject.id);

            filterInput.autocomplete({
                minLength: filterObject.autocomplete.length,
                source: function (request, response) {
                    $.ajax({
                        data: {
                            [filterObject.name]: filterInput.val(),
                        },
                        dataType: "json",
                        type: 'POST',
                        url: filterObject.autocomplete.source,
                        headers: {
                            'X-CSRF-TOKEN': window._token
                        },
                        success: function (data) {
                            response(data.items);
                        }
                    });
                },
                select: function (event, ui) {
                    filterInput.val(ui.item [filterObject.autocomplete.key]);
                    sendRequest();
                    return false;
                },
            }).data("ui-autocomplete")._renderItem = function (ul, item) {
                return $("<li></li>")
                    .append('<div class="text-nowrap">' + item [filterObject.autocomplete.key] + '</div>')
                    .appendTo(ul);
            };
        }

        function applyFilterFormParams(selector) {
            opts.page = 1;
            opts.queryParams = formSerialize(selector);
        }

        // ACTION LISTENERS
        /** mass action select */
        $aGrid.on('change', '.ag-panel-select', function () {
            const self = $(this);
            const btn = self.closest('label').find('.js-panel-select-btn');
            const val = self.val();
            if (val !== '') {
                btn.removeClass('hidden');
                /*
                if (typeof $(this).data('action') !== 'undefined') {
                    window[$(this).data('action')](getSelectedRowsIds(), val, sendRequest);
                }
                */
            } else {
                btn.addClass('hidden');
            }
        })

        /** submit for select */
        $aGrid.on('click', '.js-panel-select-btn', function () {
            const self = $(this);
            const select = self.closest('label').find('select');
            const val = select.val();
            if (val !== '') {
                if (typeof select.data('action') !== 'undefined') {
                    window[select.data('action')](getSelectedRowsIds(), val, sendRequest);
                }
            }
        })

        /** panel buttons */
        $aGrid.on('click', '.ag-panel-button', function () {
            if (typeof $(this).data('confirm') !== 'undefined') {
                if (!confirm($(this).data('confirm'))) {
                    return;
                }
            }

            if (typeof $(this).data('action') !== 'undefined') {
                window[$(this).data('action')](getSelectedRowsIds(), sendRequest);
            }
        })

        /** addition row toggle */
        $aGrid.on('click', '.ag-has-addition-row', function () {

            var tdContent = $('.js-content', $(this).next());

            if ($(this).next().hasClass('ag-hidden') && tdContent.html() === '') {
                $.ajax({
                    async: false,
                    url: tdContent.data('url'),
                    headers: {
                        'X-CSRF-TOKEN': window._token
                    }
                })
                    .done(function (response) {
                        tdContent.html(response);
                    });
            }

            $(this).next().toggleClass('ag-hidden');
        })

        $aGrid.on('click', '.addition-row-toggle', function () {
            $(this).closest('tr').addClass('ag-hidden');
        })


        /** Events for column filters */
        $aGrid.on('keyup change', '.filter-input-event', function () {
            var length = $(this).data('length');
            var value = $(this).val();
            if (value.toString().length >= length) {
                sendRequest();
            }
        });
        $aGrid.on('change', '.filter-select-event', function () {
            sendRequest(false);
        });
        $aGrid.on('keyup', '.filter-range-event', function (event) {
            if (event.keyCode === 13) {
                event.preventDefault();
                sendRequest();
            }
        });


        /** addition row toggle */
        $aGrid.on('click', '.addition-row-toggle', function () {
            $(this).closest('tr').addClass('ag-hidden');
        })

        /** check all */
        $aGrid.on('change', '.ag-check-all', function () {
            $('.ag-checkbox-row', $aGrid).prop('checked', $(this).is(':checked'));
            toggleTheadPanel();
        })
        /** check row */
        $aGrid.on('click', '.ag-checkbox-row', function (e) {
            e.stopPropagation();
            $('.ag-check-all').prop('checked', !$('.ag-checkbox-row:checkbox:not(":checked")', $(this).closest('table')).length);
            toggleTheadPanel();
        })

        /** page change */
        $aGrid.on('click', '.page-link', function (e) {
            e.preventDefault();
            opts.page = $(this).data('page');
            sendRequest();
        })

        /** page size change */
        $aGrid.on('change', 'select[name="per_page"]', function () {
            opts.page = 1;
            opts.perPage = $(this).val();
            sendRequest();
        })

        // $aGrid.on('click', '.ag-action', function(e){
        //     e.stopPropagation();
        // })

        // $aGrid.on('click', '.js-action-ajax-delete', function(e){
        //     e.preventDefault();
        //     e.stopPropagation();
        //
        //     var self = $(this);
        //     if (!confirm('Удалить?')) {
        //         return;
        //     }
        //
        //     $.ajax({
        //         url: self.attr('href'),
        //         method: 'DELETE',
        //         headers: {
        //             'X-CSRF-TOKEN': window._token
        //         }
        //     })
        //         .done(function (response) {
        //             if (self.attr('data-refresh') === 'table') {
        //                 sendRequest();
        //             } else {
        //                 var tr = self.closest('tr');
        //
        //                 if (opts.additionRow === true) {
        //                     tr.next().remove();
        //                 }
        //                 tr.remove();
        //             }
        //         })
        // })

        // $aGrid.on('click', '.js-action-ajax-action', function(e){
        //     e.preventDefault();
        //     e.stopPropagation();
        //
        //     var self = $(this);
        //     var conf = self.data('confirm');
        //     var success = self.data('success');
        //     if (typeof conf !== 'undefined' && !confirm(conf)) {
        //         return;
        //     }
        //
        //     $aGrid.css({opacity: opts.opacity});
        //
        //     $.ajax({
        //         url: self.attr('href'),
        //         method: self.data('method'),
        //         headers: {
        //             'X-CSRF-TOKEN': window._token
        //         },
        //         error: function (jqXHR, textStatus, errorThrown) {
        //             if (jqXHR.status === 422) {
        //                 alertNotify(jqXHR.responseText, 'danger');
        //             }
        //             $aGrid.css({opacity: 1});
        //         }
        //     })
        //         .done(function (response) {
        //             if (typeof success !== 'undefined') {
        //                 alertNotify(success, 'success');
        //             }
        //             sendRequest();
        //         })
        // })

        /** sort */
        $aGrid.on('click', '.ag-sort', function () {
            var dir;
            var cls = $.trim($(this).attr('class').replace('ag-sort', ''));
            $('.ag-sort').removeClass(cls).addClass('sorting');

            if (opts.sort.attr == $(this).data('name')) {
                dir = (opts.sort.dir === 'desc') ? 'asc' : 'desc';
            } else {
                dir = 'asc';
            }
            $(this).addClass('sorting_' + dir);

            opts.page = 1;
            opts.sort.attr = $(this).data('name');
            opts.sort.dir = dir;

            sendRequest();
        })

        $aGrid.on('click', '.clear-filter', function () {
            var filter = $(this).data('name');
            var id = $(this).data('id');
            $("#" + id).val('');
            delete aGridFilters [filter];

            // Delete value (spike for address global search)
            $.each(opts.columnFilterPanel, (index) => {
                if (opts.columnFilterPanel [index].name == filter) {
                    delete opts.columnFilterPanel [index].value;
                }
            });

            sendRequest();
        });

        /** filter form */
        if (opts.filterForm !== false) {
            $('body').on('submit', opts.filterForm, function (e) {
                e.preventDefault();
                if (typeof opts.events.beforeSubmitFilterForm !== 'undefined') {
                    opts.events.beforeSubmitFilterForm($(this));
                }
                applyFilterFormParams($(this))
                sendRequest(false);
            })
        }
        // !ACTION LISTENERS
    }

    /** The actual plugin */
    $.fn.aGrid = function (options) {
        if (this.length) {
            this.each(function () {
                aGrid(this, options);
            });
        }
        return this;
    };

})(jQuery);
