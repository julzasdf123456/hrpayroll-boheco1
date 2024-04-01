<template>
    <TreeChart :json="treeData" />
</template>

<style>
.node .person {
    width: 100px !important;
}

.avat {
    background-color: transparent !important;
    border: none !important;
}

.avat img {
    border: none !important;
    background-color: transparent !important;
}

.node .person .name {
    height: auto !important;
    margin: 0px !important;
    padding: 0px !important;
    line-height: normal !important;
    font-size: .92em !important;
}
</style>

<script>
import axios from "axios";
import TreeChart from "vue-tree-chart-3";

export default {
    name : 'PositionsTreeView.tree-view',
    components : {
        TreeChart,
    },
    data() {
        return {
            treeData: {
				name: 'BoD',
                image_url: axios.defaults.imgsPath + '/prof-img.png',
                class: ["rootNode"],
                children: []
			},
        }
    },
    methods : {
        getPositions() {
            axios.get(`${ axios.defaults.baseURL }/positions/get-positions`)
            .then(response => {
                let tree = this.processTree(response.data)
                this.treeData.children = tree
            })
            .catch(error => {
                console.log(error)
                this.toast.fire({
                    icon : 'error',
                    text : 'Error getting positions!\n' + error.response.data
                })
            })
        },
        processTree(array, parentId = null, isRoot = false) {
            let tree = [];
            array.forEach(item => {
                if ((isRoot && item.ParentPositionId === parentId) || (!isRoot && item.ParentPositionId === parentId)) {
                    let children = this.processTree(array, item.id);
                    let node = {
                        // id: item.id,
                        // ParentPositionId: item.ParentPositionId,
                        name: item.Position,
                        image_url: axios.defaults.imgsPath + '/prof-img.png',
                    };
                    if (children.length) {
                        node.children = children;
                    }
                    if (isRoot) {
                        node.class = ['rootNode'];
                    }
                    tree.push(node);
                }
            });
            return tree;
        },
    },
    created() {
        
    },
    mounted() {
        this.getPositions()
    }
}

</script>