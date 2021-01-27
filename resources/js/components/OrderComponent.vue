<template>
    <div>
        <div class="p-4 bg-green-400 text-center  w-full" v-if="success">
            {{ success}}
        </div>
        <div class="w-full">
            <div class="flex">
            <div class="w-1/4">
                <p>Ordering Person</p>
                <p class="font-bold">{{user.fullname}}</p>
            </div>

            <div class="w-1/2">
                <p>Select Supplier</p>
                <select name="supplier" id="supplier" v-model="supplier" @change="setProducts()">
                    <option v-for="(supplier, index) in suppliers" :key="supplier.supplier_id" :value="index">{{supplier.name}} </option>
                </select>
            </div>
            </div>
            <div class="py-5">
                <button class="bg-blue-300 mr-5 px-4 p-2 " @click="addBulk()" v-if="showSummary">Add to Bulk Order </button>
                <button class="bg-blue-300 mx-5 px-4 p-2" @click="summary()"><span v-if="showSummary">Change Order</span> <span v-if="!showSummary">Show Summary </span> </button>
                <button class="bg-blue-300 mx-5 px-4 p-2" @click="clear()">Clear </button>
            </div>
        </div>

        <div class="p-6 bg-white border-b border-gray-200 product-table" v-if="products">
            <table class="table-auto w-full text-left overflow-hidden overflow-x-auto">
                <thead>
                <tr>
                    <th class="w-auto ">Supplier ID</th>
                    <th class="w-auto ">Product #</th>
                    <th class="w-auto ">Product Description</th>
                    <th class="w-auto ">Unit/Packiging</th>
                    <th class="w-auto ">Price Unit</th>
                    <th class="w-auto ">Product Group</th>
                    <th class="w-auto ">Supplier Name</th>
                    <th class="w-auto ">Order Packages</th>
                    <th class="w-auto ">Total Units ordered</th>
                    <th class="w-auto px-2 ">Total Price</th>
                    <th class="w-auto ">Department</th>
                    <th class="w-auto ">Site</th>
                    <th class="w-auto ">Entry Date</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="noProducts">No Products found</tr>
                <tr v-for="(product, index) in bulkorder" v-if="showSummary">
                    <td>{{product.supplier_id}}</td>
                    <td>{{product.product_id}}</td>
                    <td>{{product.desc}}</td>
                    <td>{{product.unit}}</td>
                    <td>{{((product.price * product.rabatt)/product.unit).toFixed(2) }}€</td>
                    <td>{{product.group}}</td>
                    <td>{{product.supplier_name}}</td>
                    <td><input type="number" min="1" :name="'input_name_id['  + product.id  + ']'"
                               v-model="product.setQuantity" @change="test()"/></td>
                    <td>{{product.setQuantity}}</td>
                    <td class="px-2" v-if="product.setQuantity">{{(product.setQuantity * (product.price * product.rabatt)).toFixed(2) }}€</td>
                    <td v-if="!product.setQuantity"> 0 €</td>
                    <td>{{currentUser.department}}</td>
                    <td>{{ currentUser.site}}</td>
                    <td>{{ today() }}</td>
                </tr>
                <tr v-for="(product, index) in products" v-if="!showSummary">

                    <td>{{product.supplier_id}}</td>
                    <td>{{product.product_id}}</td>
                    <td>{{product.desc}}</td>
                    <td>{{product.unit}}</td>
                    <td>{{((product.price * product.rabatt)/product.unit).toFixed(2) }}€</td>
                    <td>{{product.group}}</td>
                    <td>{{product.supplier_name}}</td>
                    <td><input type="number" min="1" :name="'input_name_id['  + product.id  + ']'"
                               v-model="order[index]" @change="test()"/></td>
                    <td>{{order[index]}}</td>
                    <td class="px-2" v-if="order[index]">{{(order[index] * (product.price * product.rabatt)).toFixed(2) }}€</td>
                    <td v-if="!order[index]"> 0 €</td>
                    <td>{{currentUser.department}}</td>
                    <td>{{ currentUser.site}}</td>
                    <td>{{ today() }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['userID'],
        name: "OrderComponent",
        data: function () {
            return {
                success: '',
                input_name_id: [],
                order: {

                },
                showSummary: false,
                user: [],
                users: [],
                currentUser: [],
                ordering_person: null,
                suppliers: [],
                supplier: '',
                products: [],
                noProducts: false,
                bulkorder: [],
            }
        },
        methods: {
            clear(){
                let app = this;
                app.ordering_person = null;
                app.input_name_id = [];
                app.supplier = null;
                app.products = [];
                app.order  = {};
                app.showSummary = false;
                app.bulkorder.forEach(e => e.setQuantity = 0);
                app.bulkorder = [];
            },
            test(){
              console.log(this.order)
            },
            getUser(){
                let app = this;
                axios.get('/api/user/'+this.userID)
                    .then(response => (app.user = response.data.user[0], app.currentUser = response.data.user[0]))
            },
            getUsers(){
                let app = this;
                axios.get('/api/users/')
                    .then(response => (app.users = response.data.users))
            },
            getSuppliers(){
                let app = this;
                axios.get('/api/suppliers')
                    .then(response => (app.suppliers = response.data.suppliers))
            },
            summary(){
                let app = this;
                app.showSummary = !app.showSummary;

                Object.keys(app.order).forEach(key => {
                    //let x = {'product' : app.products[key], 'quantity' : app.order[key], 'user': app.currentUser, 'supplier' : app.supplier+1};
                    let x = app.products[key];
                    x.setQuantity = app.order[key];
                    app.bulkorder.push(x);

                });
                /*
                axios.post('/api/addbulk', {
                    bulkorder: bulkorder
                })
                    .then(response => (app.success = response.data.success))
                    .then(this.clear());

                    await this.sleep(4000);
                    app.success = '';
                */

            },
            async addBulk(){
                let app = this;
                let bulkorder = [];
                Object.keys(app.order).forEach(key => {
                    let x = {'product' : app.products[key], 'quantity' : app.order[key], 'user': app.currentUser, 'supplier' : app.supplier+1};
                    x.setQuantity = app.order[key];
                    bulkorder.push(x);

                });
                axios.post('/api/addbulk', {
                    bulkorder: bulkorder
                })
                    .then(response => (app.success = response.data.success))
                    .then(this.clear());

                    await this.sleep(4000);
                    app.success = '';

            },
            sleep(ms){
                return new Promise((resolve => {
                    setTimeout(resolve, ms);
                }))
            },
            setProducts(){
                let app = this;
                app.order  = {};
                app.noProducts = false;
                app.products = app.suppliers[app.supplier].products;
                if(app.suppliers[app.supplier].products.length <= 0){
                    app.noProducts = true;
                }
            },
            today(){
                let today = new Date();
                let dd = String(today.getDate()).padStart(2, '0');
                let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                let yyyy = today.getFullYear();

                today = dd + '.' + mm + '.' + yyyy;
                return today
            }
        },
        mounted(){
            this.getUser();
            this.getUsers();
            this.getSuppliers();
        }
    }
</script>

<style scoped>
    .product-table { overflow-x: scroll; }
    th, td { min-width: auto; }
    tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .09);
    }
</style>