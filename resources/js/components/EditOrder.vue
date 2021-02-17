<template>
    <div>
        <div class="p-5 my-5 shadow bg-gray-50 w-auto rounded-xl">
            <p class="font-bold">Supplier Name: {{ suppliername ? suppliername : 'loading..'}}</p>
            <p class="pl-5"># Products: {{ summary.quantity }}  </p>
            <p class="pl-5" v-if="sum > transport">Total Price: {{ sum.toFixed(2) }} €</p>
            <p class="pl-5 " v-else>Total Price: <span class="text-red-500 font-bold">{{sum.toFixed(2)}}</span> €</p>
            <p class="pl-5" >Transport: {{ transport }} €</p>
            <p class="pl-5">Ordering Person: <span class="bg-blue-200 rounded-full py-1 px-3 m-2" v-for="user in user_ids">{{user.fullname}}  /  {{ user.department}}</span> </p>
        </div>
        <button :class="showClass  + ' text-white rounded-full py-1 px-3 my-2 font-bold'" @click="showOrder()"> <span v-if="!show">Edit Order</span> <span v-if="show">Abort</span> </button>
        <button v-if="show" class="rounded-full py-1 px-3 my-2 font-bold bg-green-300" @click="updateOrder(1)">Update all</button>
        <div class="zui-wrapper" v-if="show">

            <div class="p-6 bg-white border-b border-gray-200  zui-scroller" >
                <table class="table-auto w-full text-left overflow-hidden overflow-x-auto  zui-table">
                    <thead>
                    <tr>
                        <th class="w-100 zui-sticky-col">Delete</th>
                        <th class="w-auto ">Donneur d'ordre</th>
                        <th class="w-auto ">ID Fournisseur</th>
                        <th class="w-auto "># Produits</th>
                        <th class="w-auto ">Description Produit</th>
                        <th class="w-auto ">Unité</th>
                        <th class="w-auto ">Prix brut</th>
                        <th class="w-auto ">Rabais</th>
                        <th class="w-auto ">Prix net</th>
                        <th class="w-auto ">Prix/Unité</th>
                        <th class="w-auto ">Groupe de produits</th>
                        <th class="w-auto ">Nom Fournisseur</th>
                        <th class="w-auto ">Order Packages</th>
                        <th class="w-auto ">Total Units ordered</th>
                        <th class="w-auto px-2 ">Prix total</th>
                        <th class="w-auto ">Département</th>
                        <th class="w-auto ">Site</th>
                        <th class="w-auto ">Date d'entrée</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(product, index) in orders" >
                        <td class="zui-sticky-col  w-auto">

                            <button class="p-2 mb-3 bg-red-300 font-bold" @click="deleteOrder(index)">Delete</button>
                        </td>
                        <td>

                            <select  :id="'ordering_person-'+ product.user_id" v-model="ordering_person[index]" @change="setUser(index, $event)" >
                                <option v-for="(user) in users" :key="user.id"  :value="user.id" >{{user.fullname}}</option>
                            </select>
                        </td>
                        <td>{{product['supplier_id']}}</td>
                        <td>{{product['product_id']}}</td>
                        <td>{{product['desc']}}</td>
                        <td>{{product['unit']}}</td>
                        <td>{{product['price']}}</td>

                        <td>{{ Math.round((product['rabatt'] ) * 100)}}%
                        </td>

                        <td>{{product['net_price'] }}€</td>
                        <td>{{product['price_unit'] }}€</td>
                        <td>{{product['group']}}</td>
                        <td>{{product['supplier_name']}}</td>
                        <td><input type="number" min="1" :name="'input_name_id['  + product['id']  + ']'"
                                   :value="product.quantity" @change="updateQuantity(index, $event)"/></td>
                        <td>{{product.quantity}}</td>
                        <td class="px-2" >{{(product.quantity * (product.price * product.rabatt)).toFixed(2) }}€€</td>

                        <td>
                            <select  :id="'departments-'+ product.department" v-model="selectedDepartments[index]" @change="setDepartments(index, $event)" >
                                <option v-for="(department) in departments" :key="department.department"  :value="department.department" >{{department.department}}</option>
                            </select>
                            </td>
                        <td>
                            <select  :id="'sites-'+ product.site" v-model="selectedSites[index]" @change="setSites(index, $event)" >
                                <option v-for="(site) in sites" :key="site.site" :value="site.site" >{{site.site}}</option>
                            </select>
                        </td>
                        <td>{{ today(product['created_at']) }}</td>

                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
<script>

    import {ref, watchEffect} from 'vue';
    export default {
        name: "EditOrder",
        props: ['supplier'],
        data: function () {
            return {
                'showClass': 'bg-blue-500',
                'users': [],
                'input_name_id': [],
                'show': false,
                'orders': [],
                'currentUser': [],
                'order': [],
                'ordering_person': [],
                'departments': [],
                'sites': [],
                'selectedDepartments': [],
                'selectedSites': [],
                'ordering_quantity': [],
                'user_list': [],
                'summary': [],
                'user_ids': [],
                'department': '',
                'site': '',
                'suppliername': '',
                'transport': 0,
                'site_list': [],
                'sum': null,
                'updateIndexes': [],
            }
        },
        methods: {
            showOrder(){
                if (!this.show){
                    this.showClass = 'bg-red-500';
                } else {
                    this.showClass = 'bg-blue-500';
                }
                this.show = !this.show;

                this.setOrderingPerson();
            },
            updateQuantity(index, $event){
                this.ordering_quantity[index] = $event.target.value;

                if(this.updateIndexes.indexOf(index) === -1) {this.updateIndexes.push(index)}

            },
            getOrders(){
                let app = this;
                    axios.get(`/api/orders/${app.supplier}/bulk`)
                    .then(response => (
                        console.log(response.data.orders),
                        app.orders = response.data.orders,
                            app.summary = response.data.summary,
                            app.user_ids = response.data.user_ids,
                            app.suppliername = response.data.orders[0]['supplier_name'],
                            app.sites = response.data.sites,
                            app.departments = response.data.departments,
                            app.sum = response.data.sum,
                            app.transport = response.data.transport.transport))

            },
            clear(){
                let app = this;
                app.currentUser = [];
                app.ordering_person = null;
                app.input_name_id = [];
                app.order  = {};
                app.setOrderingPerson();

            },
            getUser(){
                let app = this;
                axios.get('/api/user/'+this.userID)
                    .then(response => (app.user = response.data.user[0]))
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
            async addBulk(){
                let app = this;
                let bulkorder = [];

                Object.keys(app.order).forEach(key => {
                    let x = {'product' : app.products[key], 'quantity' : app.order[key], 'user': app.currentUser, 'supplier' : app.supplier+1};
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
            updateOrder(idx){
                let app = this;
                const index = parseInt(idx);
                let confirm = this.$awn;
                app.updateIndexes.forEach(e => (
                    axios.patch('/api/update/order',
                        {
                            'order': app.orders[e],
                            'user_id': parseInt(app.ordering_person[e]),
                            'quantity': (app.ordering_quantity[e] ? app.ordering_quantity[e] : app.orders[e].quantity),
                            'site': app.selectedSites[e],
                            'department': app.selectedDepartments[e],
                        }
                    )
                ))

                this.updateIndexes = [];
                this.getOrders();
                confirm.info('Order updated!');
            },

            deleteOrder(idx){
                const index = parseInt(idx);
                let app = this;
                let confirm = this.$awn;
                let onOk = () => { confirm.info('Order deleted');
                    axios.delete(`/api/delete/order/${app.orders[index].id}`)
                        .then(response => (this.getOrders()));

                };
                let onCancel = () => { confirm.info('Order won\'t be deleted')};
                confirm.confirm(
                    'Are you sure you want to delete this Order?',
                    onOk,
                    onCancel,
                    {
                        labels: {
                            confirm: 'Dangerous action'
                        }
                    }
                );


            },
            sleep(ms){
                return new Promise((resolve => {
                    setTimeout(resolve, ms);
                }))
            },
            setUser(index, event){
                let app = this;
                const search = app.users;
                app.ordering_person[index] = event.target.value;
                const found = search.find(element => element.id === parseInt(event.target.value));
                app.user_list[index] = found;

                if(this.updateIndexes.indexOf(index) === -1) {this.updateIndexes.push(index)}
            },
            setSites(index, event){
                let app = this;
                const search = app.sites;
                app.selectedSites[index] = event.target.value;
                const found = search.find(element => element.id === parseInt(event.target.value));
                app.site_list[index] = found;

                if(this.updateIndexes.indexOf(index) === -1) {this.updateIndexes.push(index)}
            },
            setDepartments(index, event){

                if(this.updateIndexes.indexOf(index) === -1) {this.updateIndexes.push(index)}
            },
            today(date){
                let today = new Date(date);
                let dd = String(today.getDate()).padStart(2, '0');
                let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                let yyyy = today.getFullYear();

                today = dd + '.' + mm + '.' + yyyy;
                return today
            },
            setOrderingPerson(){
                let app = this;
                console.log(app.orders.length);
                for (let i = 0; i < app.orders.length; i++){
                    app.ordering_person[i] = app.orders[i].user_id;
                    console.log(i)
                }
                for (let i = 0; i < app.orders.length; i++){
                    app.selectedSites[i] = app.orders[i].site;
                }
                for (let i = 0; i < app.orders.length; i++){
                    app.selectedDepartments[i] = app.orders[i].department;
                }

            },

        },
        mounted(){
            this.getOrders();
            this.getUsers();


        }
    }
</script>

<style scoped>
    .zui-table {
        border: none;
        border-collapse: separate;
        border-spacing: 0;

    }
    .zui-table thead th {
        border: none;
        padding-bottom: 5px;
        text-align: left;
        padding-right: 20px;
        white-space: nowrap;
    }
    .zui-table tbody td {
        color: #333;



    }
    .zui-table    tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, .09);
    }
    .zui-wrapper {
        position: relative;
    }
    .zui-scroller {
        margin-left: 100px;
        overflow-x: scroll;
        overflow-y: visible;
        padding-bottom: 5px;

    }
    .zui-table .zui-sticky-col {
        left: 0;
        position: absolute;
        top: auto;
        width: 100px;
    }
</style>
