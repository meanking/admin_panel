<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">Cities <Button @click="addModal=true"><Icon type="md-add" /> Add city</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>State</th>
								<th>Name</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->                                
								<!-- FILTER -->
							<tr>
								<td>&nbsp;</td>
								<td>
                                    <Select prefix="ios-search" v-model="searchData.state_id" placeholder="Search by state" style="width:200px">
                                        <Option value="">Select state</Option>
                                        <Option v-for="(state, i) in states" :value="state.id" :key="i">{{ state.name }}</Option>
                                    </Select>
                                </td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.name" placeholder="Search by name" style="width: auto" />
                                </td>
								<td>&nbsp;</td>
							</tr>
								<!-- FILTER -->
								<!-- ITEMS -->
							<tr v-for="(city, i) in cities" :key="i">
								<td>{{city.id}}</td>
								<td>{{city.state.name}}</td>
								<td>{{city.name}}</td>
								<td>
									<Button type="info" @click="showEditModal(city)">Edit</Button>
                                    <Button type="error" @click="showDeleteModal(city, i)">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
                <Page :total="total" :current="page" @on-change="handlePage" />

                <!-- City adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add city"
                    :mask-closable="false"
                    :closable="false"
                    >
                    <Select prefix="ios-home" v-model="data.state_id" placeholder="Select state" style="width:200px">
                        <Option v-for="(state, i) in states" :value="state.id" :key="i">{{ state.name }}</Option>
                    </Select>                    
                    <Input v-model="data.name" placeholder="City" maxlength="60" show-word-limit style="margin-top: 15px;" />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Cancel</Button>
                        <Button type="primary" @click="addCity" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Adding ...': 'Add city'}}</Button>  
                    </div>
                </Modal>

                <!-- City edit modal -->
                <Modal
                    v-model="editModal"
                    title="Edit city"
                    :mask-closable="false"
                    :closable="false"
                    >
                    <Select prefix="ios-home" v-model="selectedObj.state_id" placeholder="Select state" style="width:200px">
                        <Option v-for="(state, i) in states" :value="state.id" :key="i">{{ state.name }}</Option>
                    </Select>                    
                    <Input v-model="selectedObj.name" maxlength="60" show-word-limit style="margin-top: 15px;" />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Cancel</Button>
                        <Button type="primary" @click="editCity" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Editing ...': 'Edit city'}}</Button>  
                    </div>
                </Modal>

                <!-- City delete modal -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure to delete this city?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :disabled="isDeleting" :loading="isDeleting" @click="deleteCity">{{ isDeleting? 'Deleting ...': 'Delete city'}}</Button>
                    </div>
                </Modal>

			</div>
		</div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            data: {
                state_id: '',
                name: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            cities: [],
            states: [],
            selectedObj: {
                id: -1,
                state_id: '',
                name: ''
            },
            index: -1,
            isDeleting: false,
            deleteModal: false,
            searchData: {
                state_id: '',
                name: ''
            },
            total: 0,
            page: 1,
            pageSize: 10,
        }
    },
    beforeCreate() {

    },
    async created() {
        const res2 = await this.callApi('get', '/api/state/get')
        if (res2.status == 200) {
            this.states = res2.data.states
        } else {
            this.swr('')
        }

        axios({
            method: 'GET',
            url: '/api/city/get/'+this.page+'/'+this.pageSize
        })
        .then(res => {
            if (res.status == 200) {
                this.cities = res.data.cities
                this.total  = res.data.total
            } else {
                this.swr('')
            }
        })
        .catch(error => {
            console.log('error::', error);
        })
    },
    mounted() {

    },
    methods: {
        async addCity() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/city/create', this.data)
            if (res.status == 200) {
                this.cities.unshift(res.data)
                this.s('City has been registered successfully')
                this.addModal = false
                this.isAdding = false
                this.data = {
                    state_id: '',
                    name: ''
                }
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.state_id) return this.i(errors.state_id)
                    if (errors.name)     return this.i(errors.name)
                }
            }
        },
        async editCity() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/city/edit', this.selectedObj)
            if (res.status == 200) {
                this.s('The city has been updated successfully')
                this.editModal = false
                this.isAdding = false
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.state_id) return this.i(errors.state_id)
                    if (errors.name)     return this.i(errors.name)
                }
            }
        },
        showEditModal(city) {
            this.selectedObj = city
            this.editModal = true
        },
        async deleteCity() {
            this.isDeleting = true
            const res = await this.callApi('post', '/api/city/delete', this.selectedObj)
            if (res.status == 200) {
                this.cities.splice(this.index, 1);
                this.deleteModal = false
                this.isDeleting = false
                this.s('The city has been deleted successfully')
            } else {
                this.swr('')
            }
        },
        showDeleteModal(city, index) {
            this.index = index
            this.deleteModal = true
            this.selectedObj = city
        },
        handlePage(p) {
            this.page = p
            axios({
                method: 'GET',
                url: '/api/city/get/'+this.page+'/'+this.pageSize
            })
            .then(res => {
                if (res.status == 200) {
                    this.cities = res.data.cities
                } else {
                    this.swr('')
                }
            })
            .catch(error => {
                console.log('error::', error);
            })
        }
    },
    watch: {
        searchData: {
            async handler(val) {
                const res = await this.callApi('post', '/api/city/search', val)
                if (res.status == 200) {
                    this.cities = res.data
                }
            },
            deep: true
        },
        'selectedObj.state_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/state/getById', {state_id: val})
                if (res.status == 200) {
                    this.selectedObj.state.name = res.data.name
                }
            },
            deep: true
        }
    }
}
</script>