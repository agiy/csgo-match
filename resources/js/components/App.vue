<template>
    <v-app>

        <v-container>
            <v-card>
                <v-card-title>
                    <div class="d-fx jc-fe w100p">
                        <div style="width: 400px;">
                            <v-text-field
                                    v-model="search"
                                    append-icon="search"
                                    label="検索"
                                    color="accent"
                                    single-line
                                    hide-details
                                    outline
                            ></v-text-field>
                        </div>
                    </div>
                </v-card-title>

            <v-data-table
                    :headers="headers"
                    :items="demoFiles"
                    :pagination.sync="pagination"
                    :rows-per-page-items="rowsPerPageItems"
                    class="elevation-1"
            >
                <template slot="items" slot-scope="props">
                    <td>{{ props.item.name }}</td>
                    <td class="text-xs-center">{{ props.item.datetime }}</td>
                    <td class="justify-center layout px-0">
                        <v-btn icon class="mx-0" @click="download(props.item.name)">
                            <v-icon color="teal">cloud_download</v-icon>
                        </v-btn>
                    </td>
                </template>
                <template slot="no-data">
                    <v-alert :value="true" outline color="grey" icon="info">
                        demoはありません
                    </v-alert>
                </template>
            </v-data-table>

            </v-card>

        </v-container>
    </v-app>

</template>

<script>

    export default {
        name: "App",
        created() {
            this.fetchGOTVDemos();
        },
        data: () => ({
            headers: [
                {
                    text: 'ファイル名',
                    align: 'center',
                    value: 'name'
                },
                { text: '日時',  align: 'center', value: 'datetime'},
            ],
            pagination: {
                sortBy: 'datetime',
                descending: true
            },
            rowsPerPageItems: [10, 50, 100, {"text": "すべて", "value": -1}],
            search: ``,
            demoFiles: []
        }),
        methods: {
            fetchGOTVDemos() {
                console.log("fetchGOTVDemos");
                axios.get('/api/gotv-demos')
                    .then(response => {
                        this.demoFiles = response.data;
                    })
                    .catch(error => {
                        console.log(error);
                    })
            },
            download(fileName){
                console.log("download:");
                axios.get('/api/gotv-demos/download', {
                    params: {
                        filename: fileName
                    }
                })
                    .then(response => {
                        const url = window.URL.createObjectURL(new Blob([response.data]));
                        const link = document.createElement('a');
                        link.href = url;
                        link.setAttribute('download', fileName);
                        document.body.appendChild(link);
                        link.click();
                    })
                    .catch(error => {
                    })
            }
        }
    }
</script>

<style scoped>

</style>
