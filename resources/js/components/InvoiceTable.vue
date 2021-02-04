<template>
    <div>
    <div class="p-4 bg-green-400 text-center  w-full" v-if="success">
        {{ success}}
    </div>
    <table class="table-auto w-full text-left overflow-hidden overflow-x-auto">
        <thead>
        <tr>
            <th class="w-auto ">#Bon de Commande</th>
            <th class="w-auto ">Montant HT</th>
            <th class="w-auto ">Montant TTC</th>
            <th class="w-auto ">Fournisseur</th>
            <th class="w-auto ">Date de la commande</th>
            <th class="w-auto ">Complete / Pending</th>
            <th class="w-auto "># Facture</th>
            <th class="w-auto ">Montant Facture</th>
            <th class="w-auto ">Date</th>
            <th class="w-auto "></th>
            <th class="w-auto "></th>
            <th class="w-auto "></th>
            <th class="w-auto "></th>
            <th class="w-auto "></th>


        </tr>
        </thead>
        <tbody>
        <tr v-for="(invoice, index) in invoices">
            <td>{{ invoice.coupon }}</td>
            <td>{{ invoice.total_price }}</td>
            <td>{{ invoice.gross_price }}</td>
            <td>{{ invoice.supplier_name }}</td>
            <td>{{ invoice.order_date }}</td>
            <td class="flex my-2" style="display: flex;  align-items: center;">{{ invoice.pending ? 'Pending' : 'Complete' }}<button v-if="invoice.pending_invoice.length > 0" class="bg-blue-500 p-2 m-2 text-white font-bold block">Ajouter</button></td>
            <td><input type="text" v-model="facture[invoice.id]"></td>
            <td class="flex"><input type="text" v-model="ammount[index]"> <button class="bg-blue-500 font-bold text-white p-2 ml-2" v-if="facture[invoice.id]" @click="addBill(index)">Complete</button></td>
            <td v-for="pendinginvoice in pending"><span v-if="pendinginvoice.invoice_id == invoice.id"  >
                {{pendinginvoice.bill_date}}
            </span></td>
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

                axios.post('/api/pendinginvoice/',
                    {
                        'facture': app.facture[app.invoices[index].id],
                        'ammount': app.ammount[index],
                        'invoice': app.invoices[index],
                    }).then(response => (confirm.info(response.data.success), this.getInvoices()))
            },
            getPending(){
                let app = this;
                axios.get('/api/getPending').then(response => (response.data.pending.forEach(e => e.pending_invoice.forEach(k => app.pending.push(k)))))
            }
        },
        mounted(){
            this.getInvoices();
            this.getPending();

        }
    }
</script>

<style scoped>

</style>