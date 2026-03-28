export class GaricTable {
  /**
   * NOTE: JSDoc para malupet
   *       Assumes icons use <i></i> from fontawesome
   * @param {Object} opts
   * @param {string} opts.tableId - <table id="...">
   * @param {string} opts.tableBodyId - <tbody id="...">
   * @param {string} [opts.searchInputId]
   * @param {string} [opts.emptyMessage]
   * @param {string} [opts.debounceMs]
   * @param {function(string): Promise<Array>} opts.fetchIndex - fetch all rows
   * @param {function(string): Promise<Array>} opts.fetchSearch - fetch search results (q)
   * @param {Array<{ key: string, header?: string, render: (row:any)=>string }>} opts.columns
   * @param {function(any): string} [opts.rowClass] - returns classes for <tr>
   * @param {Array<{ key:string, label:string, className?:string, iconHtml?:string, onClick:(row:any)=>void }>} [opts.actions]
   */

  constructor(opts) {
    this.opts = {
      // DEFAULT: debounce = 250ms -- onChange tick
      debounceMs: 250,
      emptyMessage: "No records found.",
      ...opts,
    };

    console.log(this.opts);

    this.table = document.getElementById(this.opts.tableId);
    this.tbody = document.getElementById(this.opts.tbodyId);
    this.searchInput = this.opts.searchInputId
      ? document.getElementById(this.opts.searchInputId)
      : null;

    if (!this.table || !this.tbody) {
      throw new Error("GaricTable: tableId/tbodyId not found in DOM.");
    }

    this.rows = [];
    this.debounceTimer = null;

    this._bind();
  }

  async load() {
    this._setLoading(true);
    try {
      const data = await this.opts.fetchIndex("");
      this.rows = Array.isArray(data) ? data : [];
      this.render();
    } finally {
      this._setLoading(false);
    }
  }

  async search(q) {
    const query = (q ?? "").trim();

    // TODO: Show row with "No such {item}" message with full colspan. For now, do:
    // IF: no search params provided / empty query, render all rows
    if (!this.opts.fetchSearch || query === "") {
      return this.load();
    }

    this._setLoading(true);
    try {
      const data = await this.opts.fetchSearch(query);
      this.rows = Array.isArray(data) ? data : [];
      this.render();
    } finally {
      this._setLoading(false);
    }
  }

  render() {
    if (!this.rows.length) {
      this.tbody.innerHTML = `
        <tr class="">
          <td class="text-center px-4 py-6" colspan="${this._colspan()}">
            ${this._escapeHtml(this.opts.emptyMessage)}
          </td>
        </tr>
      `;
      return;
    }

    this.tbody.innerHTML = this.rows
      .map((row) => this._rowTemplate(row))
      .join("");
  }

  _bind() {
    // BIND: Search input
    if (this.searchInput) {
      this.searchInput.addEventListener("input", () => {
        clearTimeout(this.debounceTimer);
        this.debounceTimer = setTimeout(() => {
          this.search(this.searchInput.value);
        }, this.opts.debounceMs);
      });
    }

    // BIND: Action Rows
    if (this.opts.actions?.length) {
      this.tbody.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-dt-action]");
        if (!btn) return;

        const actionKey = btn.dataset.dtAction;
        const rowIndex = Number(btn.dataset.dtRowIndex);
        const row = this.rows[rowIndex];

        const action = this.opts.actions.find((a) => a.key === actionKey);
        if (action && row) action.onClick(row);
      });
    }
  }

  _rowTemplate(row) {
    const trClass = this.opts.rowClass
      ? this.opts.rowClass(row)
      : "bg-indigo-50 border-t-4 border-white";

    const cells = this.opts.columns
      .map((col) => {
        const html = col.render(row);
        return `<td class="text-start px-4 py-2">${html}</td>`;
      })
      .join("");

    const actionsCell = this.opts.actions?.length
      ? `
        <td class="px-4 py-2">
          <div class="flex flex-row justify-center items-center gap-2">
            ${this._actionsHtml(row)}
          </div>
        </td>
      `
      : "";

    return `<tr class="${this._escapeHtml(trClass)}">${cells}${actionsCell}</tr>`;
  }

  _actionsHtml(row) {
    // INDEX: action click handler
    const idx = this.rows.indexOf(row);

    return this.opts.actions
      .map((a) => {
        const icon = a.iconHtml ?? "";
        const label = a.label ?? a.key;

        return `
        <button
          type="button"
          data-dt-action="${this._escapeHtml(a.key)}"
          data-dt-row-index="${idx}"
          class="${this._escapeHtml(a.className ?? "")}"
          aria-label="${this._escapeHtml(label)}"
          title="${this._escapeHtml(label)}"
        >
          ${icon || this._escapeHtml(label)}
        </button>
      `;
      })
      .join("");
  }

  _colspan() {
    return this.opts.columns.length + (this.opts.actions?.length ? 1 : 0);
  }

  _setLoading(isLoading) {
    if (isLoading) this.table.classList.add("opacity-70");
    else this.table.classList.remove("opacity-70");
  }

  _escapeHtml(str) {
    return String(str ?? "")
      .replaceAll("&", "&amp")
      .replaceAll("<", "&lt")
      .replaceAll(">", "&gt")
      .replaceAll('"', "&quot")
      .replaceAll("'", "&#039");
  }
}
