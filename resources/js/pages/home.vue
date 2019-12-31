<template>
    <card :title="$t('reports')">
        <div class="text-right">
            <button class="btn btn-info" type="button" data-toggle="modal" data-target="#myModal">
                <fa :icon="['fa', 'plus']" size="1x" />
            </button>
        </div>
        <hr>
        <div id="editReport" v-if="editReport" class="modal" :style="editModalStyle()">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    <div class="modal-header">
                        <h4 class="modal-title w-100">
                            #{{ parseInt(editReport) }}. <input type="text" v-model="report.title" class="w-75">
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" @click="editReport=false">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row border border-left-0 border-right-0 border-top-0 mb-3 pb-3">
                            <div class="col-md-4 text-center">
                                <label>
                                    <fa :icon="['fa', 'stream']" />
                                    <span class="btn btn-danger">{{ $t('new') }}</span>
                                </label>
                            </div>
                            <div class="col-md-4 text-center">
                                <label>
                                    <fa :icon="['fa', 'mobile-alt']" />
                                    <input type="text" v-model="report.phone" class="w-75">
                                </label>
                            </div>
                            <div class="col-md-4 text-center">
                                <label>
                                    <fa :icon="['fa', 'at']" />
                                    <input type="email" v-model="report.email" class="w-75">
                                </label>
                            </div>
                        </div>
                        <div>
                            <textarea v-model="report.details" class="w-100 h-100"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="colContainerPhotos">
                            <div class="colPhotos" :key="key" v-for="(photo, key) in report.photos" v-if="report.photos.length > 0">
                                <img :src="photo.url" alt="photo" class="m-1 rounded-lg">
                                <input type="hidden" v-model="photo.url" :key="key">
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <div id="showReport" v-if="showSelectedReport" class="modal" :style="showModalStyle()">
            <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">

                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">
                            #{{ selectedReport.id }}. {{ selectedReport.title }}
                        </h4>
                        <button type="button" class="close" data-dismiss="modal" @click="selectReport({})">&times;</button>
                    </div>

                    <div class="modal-body">
                        <div class="row border border-left-0 border-right-0 border-top-0 mb-3 pb-3">
                            <div class="col-md-2 text-center">
                                <span :class="'btn btn-'+statusToButton[selectedReport.status]">
                                    {{ $t(selectedReport.status) }}
                                </span>
                            </div>
                            <div class="col-md-5 text-center">
                                <a v-if="selectedReport.phone" :href="'tel:'+selectedReport.phone">
                                    <fa :icon="['fa', 'mobile-alt']" />
                                    {{selectedReport.phone}}
                                </a>
                            </div>
                            <div class="col-md-5 text-center">
                                <a v-if="selectedReport.email" :href="'mailto:'+selectedReport.email">
                                    <fa :icon="['fa', 'at']" />
                                    {{selectedReport.email}}
                                </a>
                            </div>
                        </div>
                        <div v-html="selectedReport.details"></div>
                    </div>

                    <div class="modal-footer">
                        <div class="colContainerPhotos">
                            <viewer :images="selectedReportPhotos" v-if="selectedReportPhotos.length > 0">
                                <div class="colPhotos" :key="key" v-for="(photo, key) in selectedReportPhotos">
                                    <img :src="photo" alt="photo" class="m-1 rounded-lg">
                                </div>
                            </viewer>
                        </div>
                    </div>

                </div>
            </div>

        </div>
        <ul class="list-group" v-if="loaded">
            <li class="list-group-item" v-if="allReports.meta.last_page>1">
                <ul class="pagination text-center"><li v-for="n in allReports.meta.last_page" :class="paginationLinkClass(n)"><a class="page-link" @click.prevent="fetchReports(n, selectedStatus)" href="">{{ n }}</a></li></ul>
            </li>
            <li :class="reportsLinkClass(report)" v-for="report in allReports.data" :title="$t(report.status)">
                <div class="row">
                    <div class="col-md-1">
                        <h3 :class="'btn btn-'+statusToButton[report.status]">
                            #{{ report.id }}
                        </h3>
                    </div>
                    <div class="col-md-9">
                        <h5>{{ report.title }}</h5>
                        <p>
                            <i>{{ report.created }}</i>
                        </p>
                    </div>
                    <div class="col-md-2">
                        <a class="btn btn-info float-right ml-1" type="button" :href="getReportUrl(report)" @click.prevent="selectReport(report)">
                            <fa :icon="['fa', 'info']" />
                        </a>
                        <a class="btn btn-danger float-right" type="button" href="" @click.prevent="selectReportToEdit(report)">
                            <fa :icon="['fa', 'edit']" />
                        </a>
                    </div>
                </div>
            </li>
            <li class="list-group-item" v-if="allReports.meta.last_page>1">
                <ul class="pagination text-center"><li v-for="n in allReports.meta.last_page" :class="paginationLinkClass(n)"><a class="page-link" @click.prevent="fetchReports(n, selectedStatus)" href="">{{ n }}</a></li></ul>
            </li>
        </ul>
    </card>
</template>

<script>
    import Form from 'vform'
    import { mapGetters, mapActions } from 'vuex'
    import 'viewerjs/dist/viewer.css'
    import Vue from 'vue'
    import Viewer from 'v-viewer'
    Vue.use(Viewer);
    export default {
        scrollToTop: false,

        metaInfo () {
            return { title: this.$t('reports') }
        },

        data: () => ({
            loaded: false,
            selectedReport: {},
            selectedReportId: null,
            editReport: null,
            report: new Form({
                title: '',
                details: '',
                email: '',
                phone: '',
                photos: [],
            }),
            selectedStatus: 'new'
        }),
        watch: {
            oneReport: function(newValue) {
                this.selectedReport = newValue
            },
            allReports: function(newValue) {
                this.loaded = true;
                if( typeof this.$route.params.id !== 'undefined' ) {
                    this.selectedReportId = this.$route.params.id
                    this.fetchOneReport(this.selectedReportId)
                }
            },
            selectedReport: function(newReport){
            },
            selectedStatus(newStatus) {
                this.fetchReports(1, newStatus);
            },
            user: (newUser) => {
            }
        },
        computed: {
            ...mapGetters({
                allReports: 'reports/allReports',
                oneReport: 'reports/oneReport',
                allStatuses: 'reports/allStatuses',
                statusToButton: 'reports/getStatusToButton',
                user: 'auth/user'
            }),
            showSelectedReport: function() {
                return typeof this.selectedReport.id !== 'undefined'
            },

            selectedReportPhotos: function() {
                return this.selectedReport.photos.map(item=>item.url)
            }
        },

        created () {
            this.fetchReports(1);
        },

        mounted(){
        },

        methods: {
            ...mapActions({
                fetchReports: 'reports/fetchReports',
                fetchOneReport: 'reports/fetchOneReport'
            }),


            paginationLinkClass(page) {
                let classes = ['page-item'];
                if( this.allReports.meta.current_page === page ) {
                    classes.push('active')
                }
                return classes;
            },

            reportsLinkClass(report) {
                let classes = ['list-group-item', 'list-group-item-action']
                if(report.id === this.selectedReport.id ) {
                    classes.push('active')
                }
                return classes;
            },

            showModalStyle(){
                let styles = {display: 'none'}
                if( this.showSelectedReport ) {
                    styles.display = 'block'
                }
                return styles
            },

            editModalStyle(){
                let styles = {display: 'none'}
                if( this.editReport ) {
                    styles.display = 'block'
                }
                return styles
            },

            selectReport(report) {
                this.selectedReport = report
                if( !this.showSelectedReport && typeof this.$route.params.id !== 'undefined' ) {
                    this.$router.push({name: 'home'});
                }
            },
            selectReportToEdit(report){
                Object.keys(report).map((key) => {
                    if (typeof this.report[key] !== 'undefined') {
                        this.report[key] = report[key]
                    }
                });
                this.editReport = report.id
            },
            getReportUrl(report){
                let props = this.$router.resolve({
                    name: 'one-report',
                    params: { id: report.id },
                });
                return props.href;
            }

        },
    }
</script>

<style>
    .colContainerPhotos {
        display: table;
        width: 100%;
    }

    #showReport .colPhotos img {
        cursor: pointer;
    }
    .colPhotos img {
        max-height: 150px;
        width: auto;
        min-height: 150px;
        height: 150px;
        box-shadow: 1px 1px 5px lightgrey;
    }
    .colPhotos img:hover {
        box-shadow: 1px 1px 10px lightgrey;
    }
    .colPhotos {
        display: inline-block;
    }
    .modal .modal-content {
        box-shadow: 0px 0px 20px lightgray;
    }
    textarea {
        min-height: 300px;
    }
</style>