import Tree from './Tree'

export default class TreePlugin {

    constructor(options) {
        this.spaceWidth = options.space ?? 40
        this.columnIdx = options.columnIdx

        this.umbrellaDatatable = null
        this.tree = new Tree()
    }

    /**
     * @param {UmbrellaDataTable} umbrellaDatatable
     */
    configure(umbrellaDatatable) {
        this.umbrellaDatatable = umbrellaDatatable
        this.umbrellaDatatable.datatable.on('draw', () => this._drawTree())
    }

    _drawTree() {
        const $items = this.umbrellaDatatable.tbody.querySelectorAll('tr')

        this.tree.populate($items, false)
        this.tree.nodes.forEach(node => this._drawNode(node))

        // initialize collapse state - all node are shown by default => just hide collapsed
        this.tree.root.traverse(node => {

            if (node.collapsed) {
                this._collapseNode(node)
                return false
            }

        })
    }

    _drawNode(node) {
        // node.$elt is row
        // $spaceContainer is cell
        const $spaceContainer = node.$elt.children.length > this.columnIdx ? node.$elt.children[this.columnIdx] : null

        if (null === $spaceContainer) {
            console.error(`No column found at idx ${this.columnIdx}`)
            return
        }

        let $spacer = null
        if (node.hasChildren()) {
            $spacer = document.createElement('a')
            $spacer.href = '#'

            if (node.collapsed) {
                $spacer.classList.add('collapsed')
            }

            $spacer.addEventListener('click', evt => {
                evt.preventDefault()
                if (node.collapsed) {
                    this._expandNode(node)
                } else {
                    this._collapseNode(node)
                }
            })
        } else {
            $spacer = document.createElement('div')
        }

        $spacer.classList.add('tree-spacer')

        // padding spacer depending on level
        $spacer.style.paddingLeft = (node.level * this.spaceWidth) + 'px'

        $spaceContainer.prepend($spacer)

        node.$elt.classList.add('tree-node')
        if (node.isFirst()) {
            node.$elt.classList.add('tree-node-first')
        }

        if (node.isLast()) {
            node.$elt.classList.add('tree-node-last')
        }
    }

    _collapseNode(node) {
        const $target = node.$elt.firstElementChild

        if ($target && $target.firstElementChild && $target.firstElementChild.classList.contains('tree-spacer')) {
            $target.firstElementChild.classList.add('collapsed')
        }

        node.collapse()
    }

    _expandNode(node) {
        const $target = node.$elt.firstElementChild

        if ($target && $target.firstElementChild && $target.firstElementChild.classList.contains('tree-spacer')) {
            $target.firstElementChild.classList.remove('collapsed')
        }

        node.expand()
    }

}
