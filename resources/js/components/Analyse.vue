<template>
    <div class="w-1/4" >
        <select name="supplier" id="supplier" v-model="selectedSupplier" @change="setUrl()" class="w-full flex">
            <option value="" selected >Choose a Supplier</option>
            <option v-for="supplier in suppliers" :value="'&in=supplier_id,'+supplier.id">{{supplier.name}}</option>
        </select>
        <select name="group" id="group" v-model="selectedGroup" @change="setUrl()" class="w-full flex">
            <option value="" selected >Choose a Product Group</option>
            <option v-for="group in groups" :value="'&in=group,'+group.group">{{group.group}}</option>
        </select>
        <select name="department" id="department" v-model="selectedDepartment" @change="setUrl()" class="w-full flex">
            <option value="" selected >Choose a Department</option>
            <option v-for="department in departments" :value="'&in=department,'+department.department">{{department.department}}</option>
        </select>
        <select name="site" id="site" v-model="selectedSite" @change="setUrl()" class="w-full flex">
            <option value="" selected >Choose an Site</option>
            <option v-for="site in sites" :value="'&in=site,'+site.site">{{site.site}}</option>
        </select>
        <select name="user" id="user" v-model="ordering_person" @change="setUrl()" class="w-full flex">
            <option value="" selected >Choose an Ordering Person</option>
            <option v-for="user in users" :value="'&in=user_id,'+user.id">{{user.fullname}}</option>
        </select>
        <!--<select name="type" id="status" v-model="status" @change="setUrl()" class="w-full flex">
            <option value="" disabled>Choose Order Status</option>
            <option :value="'&in=complete,1&in=partial,1'" >Both</option>
            <option :value="'&in=complete,1'">Complete</option>
            <option :value="'&in=partial,1'">Partial</option>
            <option :value="'&in=partial,1,0&in=complete,1,0'">All</option>
        </select>-->
        <div class="flex w-full">
        <input class="w-1/2" type="date" id="start" name="trip-start" @change="setDate()"
               v-model="startDate">
        <input class="w-1/2" type="date" id="end" name="trip-end" @change="setDate()"
               v-model="endDate">
        </div>
        <select name="supply" id="supply" v-model="sellectedSupply" @change="setUrl()" class="w-full flex">
            <option value="" disabled selected >Choose Supply Status</option>
            <option v-for="group in groups" :value="'&in=group,'+group.group">{{group.group}}</option>
        </select>
        <select name="type" id="type" v-model="type" @change="setUrl()" class="w-full flex">
            <option value="" disabled>Choose Order Type</option>
            <option value="individual,bulk" selected >All</option>
            <option value="individual">Individual</option>
            <option value="bulk">Bulk</option>
        </select>







        <div class="my-12 block">
            <a :href="'/summary/analyse'+url" class="bg-blue-500 py-4 px-6 font-bold text-white">Refresh</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Analyse",
        data: function () {
            return {
                ordering_person: '',
                users: [],
                url: '',
                type: 'individual,bulk',
                selectedSite: '',
                selectedDepartment: '',
                selectedSupplier: '',
                selectedGroup: '',
                sellectedSupply: '',
                startDate: '',
                endDate: '',
                groups: [],
                suppliers: [],
                sites: [],
                departments: [],
                status: '',
                selectedStart: '&',
                selectedEnd: '&',
            }
        },


        methods: {
            getUsers(){
                let app = this;
                axios.get('/api/users/')
                    .then(response => (app.users = response.data.users))
            },
            setUrl(){
                this.url = '?in=type,'+this.type+this.ordering_person+this.selectedSite+this.selectedDepartment+this.selectedSupplier+this.selectedGroup+this.status+this.selectedStart+this.selectedEnd;
            },
            getSites(){
                let app = this;
                axios.get('/api/getSites')
                    .then(response => (app.sites = response.data.sites))
            },
            getDepartments(){
                let app = this;
                axios.get('/api/getDepartments')
                    .then(response => (app.departments = response.data.departments))
            },

            getSuppliers(){
                let app = this;
                axios.get('/api/suppliers')
                    .then(response => (app.suppliers = response.data.suppliers))
            },
            getGroups(){
                let app = this;
                axios.get('/api/getGroups')
                    .then(response => (app.groups = response.data.groups))
            },
            today(){
                let today = new Date();
                let dd = String(today.getDate()).padStart(2, '0');
                let mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                let yyyy = today.getFullYear();

                today = yyyy + '-' + mm + '-' + dd;
                return today
            },
            setDates(){
                this.startDate = this.today();
                this.endDate = this.today();
            },
            setDate(){
                this.selectedStart ='&greater_or_equal=created_at,' + this.startDate ;
                this.selectedEnd ='&less_or_equal=created_at,' + this.endDate;
                this.setUrl();
            }
        },

        mounted(){
            this.getUsers();
            this.getSites();
            this.getDepartments();
            this.getSuppliers();
            this.getGroups();
            this.setUrl();
            this.setDates();
        }
    }
</script>

<style scoped>

</style>