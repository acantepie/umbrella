import TreeNode from './TreeNode';

export default class Tree {

    constructor() {
        this.root = null
        this.nodes = null
    }


    populate($items, reset = true) {
        const state = reset ? null : this.getState()

        this.root = new TreeNode()
        this.nodes = new Map()

        $items.forEach($item => {
            const id = $item.dataset.id
            const parentId = $item.dataset.parentId
            const collapsed = $item.dataset.collapsed !== 'false'

            const node = new TreeNode(id, $item)
            if (reset) {
                node.collapsed = collapsed
            } else {
                node.collapsed = state.has(node.id) ? state.get(node.id) : collapsed
            }

            if (!parentId || !this.nodes.has(parentId)) {
                this.root.addChild(node)
            } else {
                const parent = this.nodes.get(parentId)
                parent.addChild(node)
            }

            this.nodes.set(id, node)
        })
    }

    getState() {
        const state = new Map()

        if (this.root) {
            this.root.traverse(node => {
                state.set(node.id, node.collapsed)
                return true
            })
        }

        return state
    }
}
