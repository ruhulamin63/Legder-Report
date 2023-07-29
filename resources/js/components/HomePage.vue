<template>
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <!-- Page Content -->
        <div class="content container-fluid">
            <!-- Page Header -->
            <div class="page-header">
                <div class="row">
                    <div class="col-sm-12">
                        <!-- <v-btn color="primary" class="my-2">Button</v-btn> -->
                        <v-alert dismissible type="success">Vueitfy installed successfully!</v-alert>
                        <!-- <v-icon icon="fas fa-home" /> -->

                        <h3 class="page-title">Ledger Report</h3>
                        <ul class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#l">Dashboard</a></li>
                            <li class="breadcrumb-item active">Product Ledger Report</li>
                        </ul>
                    </div>
                </div>
            </div>
            <!-- /Page Header -->
            
            <!-- Search Filter -->
            <form @submit.prevent="search">
                <div class="row filter-row">
                    <div class="col-sm-6 col-md-3"> 
                        <div class="form-group form-focus select-focus">
                            <label class="focus-label">Purchased By</label>
                            <select class="form-control" @change="selectItem(2)">
                                <option>-Select-</option>
                                <option v-for="(i, index) in itemArr" :key="index"
                                    v-bind:value="i.id">
                                    {{ i.product.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">  
                        <div class=" form-focus">
                            <label class="focus-label">From</label>
                            <input 
                                class="form-control" 
                                type="date" 
                                v-model="item.form_date">
                        </div>
                    </div>

                    <div class="col-sm-6 col-md-3">  
                        <div class="form-group form-focus">
                            <input 
                                class="form-control" 
                                type="date" 
                                v-model="item.to_date">
                            <label class="focus-label">To</label>
                        </div>
                    </div>
                    <div class="col-sm-6 col-md-3">  
                        <!-- search button -->
                        <button type="submit" class="btn btn-success btn-block"> Search </button>
                    </div>
                </div>
            </form>
            <!-- /Search Filter -->
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-striped custom-table mb-0 datatable">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Date</th>
                                    <th>Rate</th>
                                    <th>Stock In</th>
                                    <th>Stock Out</th>
                                    <th>Stock</th>
                                    <th>Balance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, index) in ledgerReport">
                                    <td>
                                        <strong>{{ item.name }}</strong>
                                    </td>
                                    <td>{{ item.date }}</td>
                                    <td>{{ item.purchase_rate }}</td>
                                    <td>{{ item.stockIn }}</td>
                                    <td>{{ item.stockOut }}</td>
                                    <td>{{ item.stock }}</td>
                                    <td>{{ item.totalAmount }}</td>
                                </tr>
                            </tbody>
                        </table>

                        <!-- pagination -->
                        <pagination v-if="pagination.last_page > 1"
                            :pagination="pagination"
                            :offset="5"
                            @paginate="productDetails()">
                        </pagination>
                    </div>
                </div>
            </div>
        </div>
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
</template>

<script>
    import axios from 'axios';
    import pagination from './partials/Pagination.vue';
    
    export default {
        name: "HomePage",

        components: {
            pagination,
        },

        data() {
            return {
                items: {},
                itemArr: {},
                ledgerReport: {},
                salesList: {},
                totalAmount: 0,
                stockIn: 0,
                stockOut: 0,
                stock: 0,

                item: {
                    product_id: '',
                    form_date: '',
                    to_date: '',
                },

                pagination: {
                    current_page:1,
                },
            };
        },

        methods: {
            //get product list
            async saleDetails(){
                await axios.get(`/api/ledger`).then(({data})=>{
                    this.itemArr = data.SaleDetails;

                    // console.log('data=>', this.itemArr);

                }).catch((error)=>{
                    console.log('error', error);
                });
            },

            // insert search data
            //how to filter search or how to get data from database
            search(){
                axios.post(`/api/reports/sales`, this.item)
                    .then(response => {
                        this.totalAmount = response.data.totalAmount;
                        this.stockIn = response.data.stockIn;
                        this.stockOut = response.data.stockOut;
                        this.stock = response.data.stock;

                        this.ledgerReport = response.data.saleDetails;

                        this.ledgerReport.forEach((item, index) => {
                            this.ledgerReport[index].totalAmount = this.totalAmount;
                            this.ledgerReport[index].stockIn = this.stockIn;
                            this.ledgerReport[index].stockOut = this.stockOut;
                            this.ledgerReport[index].stock = this.stock;
                        });

                        // console.log('data=>', this.ledgerReport);

                    }).catch(error => {
                    console.log(error);
                })
            },

            //get product details
            async productDetails(){
                await axios.get('/api/products/sales?page=' + this.pagination.current_page).then(({data})=>{
                    this.ledgerReport = data.saleDetails.data;
                    this.pagination = data.saleDetails;
                    // console.log('data=>', this.pagination);

                    this.totalAmount = data.totalAmount.data;
                    this.stockIn = data.stockIn.data;
                    this.stockOut = data.stockOut.data;
                    this.stock = data.stock.data;

                    // console.log('data=>', this.ledgerReport);
                    
                    this.ledgerReport.forEach((item, index) => {
                        this.ledgerReport[index].totalAmount = this.totalAmount[index];
                        this.ledgerReport[index].stockIn = this.stockIn[index];
                        this.ledgerReport[index].stockOut = this.stockOut[index];
                        this.ledgerReport[index].stock = this.stock[index];
                    });

                    // console.log('data=>', this.ledgerReport);

                }).catch((error)=>{
                    console.log('error', error);
                });
            },

            selectItem: function(id){
                this.item.product_id = id;
            },
        },

        mounted() {
            this.saleDetails();
            this.productDetails();
        },
    }
</script>