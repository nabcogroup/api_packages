<template>
    <div class="row">
        <v-page-header-bar title="Property List"></v-page-header-bar>
        <v-live-view :grid="gridView" @action="action"></v-live-view>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                gridView: {
                    columns: [
                        {name: 'name', column: 'Name'},
                        {name: 'address', column: 'Address'},
                        {name: 'total_units', column: 'Total Units'},
                        {name: '$action', column: 'Actions', style: 'width:5%'}
                    ],
                    actions: [
                        {key: 'edit', name: 'Edit'},
                        {key: 'view', name: 'View'}
                    ],
                    source: {
                        url: '/api/property',
                        pointer: 'properties'
                    }
                }
            }
        },
        methods: {
            action(a,v,k) {
                if(a === 'edit') {

                }
            }
        }

    }
</script>

