<template>
    <div>
        <div class="p-4 bg-green-400 text-center  w-full" v-if="success">
            {{ success}}
        </div>
        <div class="w-full">
            <div class="flex">
                <div class="w-1/4">
                    <p>Donneur d'ordre</p>
                    <p class="font-bold">{{user.fullname}}</p>
                </div>

                <div class="w-1/2">
                    <p>Sélectionner le fournisseur
                    </p>
                    <select name="supplier" id="supplier" v-model="supplier" @change="setProducts()">
                        <option v-for="(supplier, index) in suppliers" :key="supplier.supplier_id" :value="index">{{supplier.name}} </option>
                    </select>
                </div>
            </div>
            <div class="py-5">
                <button class="bg-blue-300 mr-5 px-4 p-2 " @click="addIndividual()" v-if="showSummary">Ajouter une commande individuelle </button>
                <button class="bg-blue-300 mx-5 px-4 p-2" @click="summary()"  v-if="Object.keys(order).length > 0"> <span v-if="showSummary">Change l'ordre</span> <span v-if="!showSummary">Afficher le résumé</span> </button>
                <button class="bg-blue-300 mx-5 px-4 p-2" @click="clear()">à vider  </button>
                <button class="bg-blue-300 mx-5 px-4 p-2" @click="allProducts()"><span v-if="!showAll">Afficher tous les produits</span><span v-if="showAll">Afficher les produits préférés</span> </button>
            </div>
        </div>

        <div class="p-6 bg-white border-b border-gray-200 product-table" v-if="products">
            <table class="table-auto w-full text-left overflow-hidden overflow-x-auto">
                <thead>
                <tr>
                    <th class="w-auto ">ID Fournisseur
                    </th>
                    <th class="w-auto "># Produit
                    </th>
                    <th class="w-auto ">Description Produit
                    </th>
                    <th class="w-auto ">Unité
                    </th>
                    <th class="w-auto ">Prix/Unité
                    </th>
                    <th class="w-auto ">Groupe de produits
                    </th>
                    <th class="w-auto ">Nom Fournisseur
                    </th>
                    <th class="w-auto ">Order Packages</th>
                    <th class="w-auto ">Nom Fournisseur
                    </th>
                    <th class="w-auto px-2 ">Prix total</th>
                    <th class="w-auto ">Département
                    </th>
                    <th class="w-auto ">Site</th>
                    <th class="w-auto ">Date d'entrée</th>
                </tr>
                </thead>
                <tbody>
                <tr v-if="noProducts">No Products found</tr>
                <tr v-for="(product, index) in individualorder" v-if="showSummary">
                    <td>{{product.supplier_id}}</td>
                    <td>{{product.product_id}}</td>
                    <td>{{product.desc}}</td>
                    <td>{{product.unit}}</td>
                    <td>{{((product.price * product.rabatt)/product.unit).toFixed(2) }}€</td>
                    <td>{{product.group}}</td>
                    <td>{{product.supplier_name}}</td>
                    <td><input type="number" min="1" :name="'input_name_id['  + product.id  + ']'"
                               v-model="order[product.product_id]" /></td>
                    <td>{{order[product.product_id]}}</td>
                    <td class="px-2" v-if="product.setQuantity">{{(order[product.product_id] * (product.price * product.rabatt)).toFixed(2) }}€</td>
                    <td v-if="!order[product.product_id]"> 0 €</td>
                    <td>{{currentUser.department}}</td>
                    <td>{{ currentUser.site}}</td>
                    <td>{{ today() }}</td>
                </tr>
                <tr v-for="(product, index) in user_products" v-if="!showSummary && !showAll">

                    <td>{{product.supplier_id}}</td>
                    <td>{{product.product_id}}</td>
                    <td>{{product.desc}}</td>
                    <td>{{product.unit}}</td>
                    <td>{{((product.price * product.rabatt)/product.unit).toFixed(2) }}€</td>
                    <td>{{product.group}}</td>
                    <td>{{product.supplier_name}}</td>
                    <td><input type="number" min="1" :name="'input_name_id['  + product.id  + ']'"
                               v-model="order[product.product_id]" /></td>
                    <td>{{order[product.product_id]}}</td>
                    <td class="px-2" v-if="order[product.product_id]">{{(order[product.product_id] * (product.price * product.rabatt)).toFixed(2) }}€</td>
                    <td v-if="!order[product.product_id]"> 0 €</td>
                    <td>{{currentUser.department}}</td>
                    <td>{{ currentUser.site}}</td>
                    <td>{{ today() }}</td>
                </tr>
                <tr v-for="(product, index) in products" v-if="!showSummary && showAll">

                    <td>{{product.supplier_id}}</td>
                    <td>{{product.product_id}}</td>
                    <td>{{product.desc}}</td>
                    <td>{{product.unit}}</td>
                    <td>{{((product.price * product.rabatt)/product.unit).toFixed(2) }}€</td>
                    <td>{{product.group}}</td>
                    <td>{{product.supplier_name}}</td>
                    <td><input type="number" min="1" :name="'input_name_id['  + product.id  + ']'"
                               v-model="order[product.product_id]" /></td>
                    <td>{{order[product.product_id]}}</td>
                    <td class="px-2" v-if="order[product.product_id]">{{(order[product.product_id] * (product.price * product.rabatt)).toFixed(2) }}€</td>
                    <td v-if="!order[product.product_id]"> 0 €</td>
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
        name: "OrderIndividualComponent",
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
                individualorder: [],
                showAll: false,
                user_products: [],
                loading: false,
                type: 'individual',
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
                app.individualorder.forEach(e => e.setQuantity = 0);
                app.individualorder = [];
                app.loading = false;
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
            summary() {
                let app = this;
                app.individualorder = [];
                app.showSummary = !app.showSummary;
                let fk = Object.keys(app.order);
                for(let i = 0; i < Object.keys(app.order).length; i++){
                    let x = app.products.find(product => {
                        return product.product_id == fk[i];
                    });
                    console.log(x);
                    x.setQuantity = app.order[fk[i]];
                    app.individualorder.push(x);

                }
            },
            async addIndividual(){
                let app = this;
                let individualorder = [];
                let fk = Object.keys(app.order);

                for(let i = 0; i < Object.keys(app.order).length; i++){
                    let selectedProduct = [];
                    for(let k = 0; k < Object.keys(app.order).length; k++){
                        let p = app.products.find(product => {
                            return product.product_id == fk[k];
                        });
                        selectedProduct.push(p);
//tz5
                    }
                    let x = {'product' : selectedProduct[i], 'quantity' : app.order[selectedProduct[i].product_id], 'user': app.currentUser, 'supplier' : app.supplier+1, 'type': app.type};
                    if(app.order[selectedProduct[i].product_id] > 0){
                        x.setQuantity = app.order[selectedProduct[i].product_id];
                        individualorder.push(x);
                    }



                }

                axios.post('/api/addIndividual', {
                    order: individualorder
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
                app.loading = true;
                axios.get(`/api/products/${app.supplier}/${app.currentUser.fullname}`).then(
                    response => (app.products = response.data.products, app.user_products = response.data.user_products, app.loading = false)
                );

                app.order  = {};
                app.noProducts = false;
                //app.products = app.suppliers[app.supplier].products;
                if(app.suppliers[app.supplier].products.length <= 0){
                    app.noProducts = true;
                }
            },
            setUser(){
                let app = this;
                console.log(app.ordering_person);
                app.currentUser = app.user;
            },
            allProducts(){
              this.showAll = !this.showAll;
            },
            today(){
                let today = new Date();
                let dd = String(today.getDate()).padStart(2, '0');
                let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                let yyyy = today.getFullYear();

                today = mm + '.' + dd + '.' + yyyy;
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
