<template>
    <div class="row">
      <div class="col-3">
        <h3>Draggable 1</h3>
        <draggable
          class="list-group"
          group="people"
          @change="log"
          itemKey="name"
        >
          <template v-for="(element, index) in list1">
            <div class="list-group-item">{{ element.name }} {{ index }}</div>
          </template>
        </draggable>
      </div>
  
      <div class="col-3">
        <h3>Draggable 2</h3>
        <draggable
          class="list-group"
          group="people"
          @change="log"
          itemKey="name"
        >
          <template v-for="(element, index) in list2">
            <div class="list-group-item">{{ element.name }} {{ index }}</div>
          </template>
        </draggable>
      </div>
  
    </div>
</template>

<script>
import axios from 'axios';
import moment from 'moment';
import FlatPickr from 'vue-flatpickr-component';
import 'flatpickr/dist/flatpickr.css';
import jquery from 'jquery';
import Swal from 'sweetalert2';
import draggable from 'vuedraggable';

export default {
    name : 'Kanban.kanban',
    components : {
        FlatPickr,
        Swal,
        draggable
    },
    data() {
        return {
            list1: [
                { name: "John", id: 1 },
                { name: "Joao", id: 2 },
                { name: "Jean", id: 3 },
                { name: "Gerard", id: 4 }
            ],
            list2: [
                { name: "Juan", id: 5 },
                { name: "Edgard", id: 6 },
                { name: "Johnson", id: 7 }
            ]
        }
    },
    methods : {
        isNull (item) {
            if (jquery.isEmptyObject(item)) {
                return true;
            } else {
                return false;
            }
        },
        add: function() {
            this.list.push({ name: "Juan" });
        },
            replace: function() {
            this.list = [{ name: "Edgard" }];
        },
        clone: function(el) {
            return {
                name: el.name + " cloned"
            };
        },
        log: function(evt) {
            window.console.log(evt);
        }
    },
    created() {
        
    },
    mounted() {
        
    }
}
</script>
