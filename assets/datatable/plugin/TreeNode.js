export default class TreeNode {

    constructor(id = null, $elt = null, level = 0) {
        this.id = id
        this.idx = 0
        this.level = level
        this.$elt = $elt
        this.parent = null
        this.collapsed = true
        this.children = new Map()

        this._childIndex = 0
    }

    /**
     * @param node {TreeNode}
     */
    addChild(node) {
        node.parent = this
        node.level = this.level + 1
        node.idx = this._childIndex++
        this.children.set(node.id, node)
    }

    isFirst() {
        return this.idx === 0
    }

    isLast() {
        return this.parent ? this.parent._childIndex - 1 === this.idx : true
    }

    traverse(cb) {
        this.children.forEach(childNode => {
            // stop traverse
            if (false === cb(childNode)) {
                return false
            }

            childNode.traverse(cb)
        })
    }

    collapse() {
        this.collapsed = true
        this.traverse(nestNode => nestNode.$elt.hidden = true)
    }


    expand() {
        this.collapsed = false
        this.traverse(nestNode => {
            nestNode.$elt.hidden = false
            if (nestNode.collapsed === true) {
                return false;
            }
        })
    }

    hasChildren() {
        return this.children.size > 0
    }
}
