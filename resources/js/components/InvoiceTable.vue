<template>
    <div>
    <div class="p-4 bg-green-400 text-center  w-full" v-if="success">
        {{ success}}
    </div>
    <table class="table-auto w-full text-left overflow-hidden overflow-x-auto ">
        <thead>
        <tr>
            <th class="w-auto ">#Bon de Commande</th>
            <th class="w-auto ">Montant HT</th>
            <th class="w-auto ">Montant TTC</th>
            <th class="w-auto ">Fournisseur</th>
            <th class=" " style="width: 200px;">Date de la commande</th>
            <th class="w-auto ">Complete / Pending</th>
            <th class="w-auto "># Facture</th>
            <th class="w-auto ">Montant Facture</th>
            <th class="w-auto ">Date</th>
            <th class="w-auto " ># Facture - Montant - Date</th>



        </tr>
        </thead>
        <tbody >
        <tr  v-for="(invoice, index) in invoices">
            <td>{{ invoice.coupon }}</td>
            <td>{{ invoice.total_price }}</td>
            <td>{{ invoice.gross_price }}</td>
            <td>{{ invoice.supplier_name }}</td>
            <td >{{ invoice.order_date }}</td>
            <td class="flex my-2" style="display: flex;  align-items: center;">{{ invoice.pending ? 'Pending' : 'Complete' }}<button v-if="invoice.pending_invoice.length > 0 && invoice.pending" class="bg-blue-500 p-2 m-2 text-white font-bold block" @click="setComplete(invoice.id)">Ajouter</button></td>
            <td><input type="text" v-model="facture[invoice.id]"></td>
            <td class="flex"><input type="text" v-model="ammount[index]"> <button class="bg-blue-500 font-bold text-white p-2 ml-2" v-if="facture[invoice.id]" @click="addBill(index)">Ajouté</button></td>
            <td v-if="invoice.pending_invoice.length > 0">
                {{invoice.pending_invoice[0]['bill_date']}}
            </td>
            <td  style="white-space:nowrap;" v-for="pendinginvoice in invoice.pending_invoice">
                <p class="ml-5 py-4"><span class="font-bold"> #Facture: </span>{{pendinginvoice.bill_id}}<span class="font-bold"> Montant: </span>{{pendinginvoice.bill_ammount}}<span class="font-bold"> Date: </span>{{pendinginvoice.bill_date}}</p>
            </td>
        </tr>
        </tbody>


    </table>
    </div>
</template>

<script>
    import {ref, watchEffect} from 'vue';
    export default {
        name: "InvoiceTable",
        data: () => {
            return {
                'invoices': [],
                'facture': [],
                'ammount': [],
                'success': '',
                'pending': []
            }
        },
        methods: {
            async getInvoices(){
                let app = this;
                await axios.get('/api/invoices').then(response => (app.invoices = response.data.invoices, response.data.invoices.forEach(e => app.ammount.push(e.gross_price)), response.data.invoices.forEach(e => e.pending_invoice.forEach(k => app.facture[k.invoice_id] = k.bill_id))))
            },
            addBill(index){
                let app = this;
                let confirm = this.$awn;

                axios.post('/api/pendinginvoice',
                    {
                        'facture': app.facture[app.invoices[index].id],
                        'ammount': app.ammount[index],
                        'invoice': app.invoices[index],
                    }).then(response => (confirm.info(response.data.success), this.getInvoices())), this.getPending()
            },
            getPending(){
                let app = this;
                axios.get('/api/getPending').then(response => (response.data.pending.forEach(e => e.pending_invoice.forEach(k => app.pending.push(k)))))
            },
            setComplete(id){
                let app = this;
                axios.post(`/api/complete/invoice/${id}`).then(this.getInvoices(), this.getPending())

            }
        },
        mounted(){
            this.getInvoices();
            this.getPending();

        }
    }
</script>

<style scoped>
    th {
        padding-left: 7px;
    }
    td {
    }
</style>
