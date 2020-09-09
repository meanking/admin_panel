<template>
    <div>
        <div class="content">
			<div class="container-fluid">
				<!--~~~~~~~ TABLE ONE ~~~~~~~~~-->
				<div class="_1adminOverveiw_table_recent _box_shadow _border_radious _mar_b30 _p20">
					<p class="_title0">States <Button @click="addModal=true"><Icon type="md-add" /> Add state</Button></p>

					<div class="_overflow _table_div">
						<table class="_table">
								<!-- TABLE TITLE -->
							<tr>
								<th>ID</th>
								<th>Country</th>
								<th>Name</th>
								<th>Action</th>
							</tr>
								<!-- TABLE TITLE -->                                
								<!-- FILTER -->
							<tr>
								<td>&nbsp;</td>
								<td>
                                    <Select prefix="ios-search" v-model="searchData.country_id" placeholder="Search by country" style="width:200px">
                                        <Option value="">Select country</Option>
                                        <Option v-for="(country, i) in countries" :value="country.id" :key="i">{{ country.name }}</Option>
                                    </Select>
                                </td>
								<td>
                                    <Input suffix="ios-search" v-model="searchData.name" placeholder="Search by name" style="width: auto" />
                                </td>
								<td>&nbsp;</td>
							</tr>
								<!-- FILTER -->
								<!-- ITEMS -->
							<tr v-for="(state, i) in states" :key="i">
								<td>{{state.id}}</td>
								<td>{{state.country.name}}</td>
								<td>{{state.name}}</td>
								<td>
									<Button type="info" @click="showEditModal(state)">Edit</Button>
                                    <Button type="error" @click="showDeleteModal(state, i)">Delete</Button>
								</td>
							</tr>
								<!-- ITEMS -->


						</table>
					</div>
				</div>
                <Page :total="total" :current="page" @on-change="handlePage" />

                <!-- State adding modal -->
                <Modal
                    v-model="addModal"
                    title="Add state"
                    :mask-closable="false"
                    :closable="false"
                    >
                    <Select prefix="ios-home" v-model="data.country_id" placeholder="Select country" style="width:200px">
                        <Option v-for="(country, i) in countries" :value="country.id" :key="i">{{ country.name }}</Option>
                    </Select>                    
                    <Input v-model="data.name" placeholder="State" maxlength="60" show-word-limit style="margin-top: 15px;" />
                    <div slot="footer">
                        <Button type="default" @click="addModal=false">Cancel</Button>
                        <Button type="primary" @click="addState" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Adding ...': 'Add state'}}</Button>  
                    </div>
                </Modal>

                <!-- State edit modal -->
                <Modal
                    v-model="editModal"
                    title="Edit state"
                    :mask-closable="false"
                    :closable="false"
                    >
                    <Select prefix="ios-home" v-model="selectedObj.country_id" placeholder="Select country" style="width:200px">
                        <Option v-for="(country, i) in countries" :value="country.id" :key="i">{{ country.name }}</Option>
                    </Select>                    
                    <Input v-model="selectedObj.name" maxlength="60" show-word-limit style="margin-top: 15px;" />
                    <div slot="footer">
                        <Button type="default" @click="editModal=false">Cancel</Button>
                        <Button type="primary" @click="editState" :disabled="isAdding" :loading="isAdding">{{ isAdding? 'Editing ...': 'Edit state'}}</Button>  
                    </div>
                </Modal>

                <!-- State delete modal -->
                <Modal v-model="deleteModal" width="360">
                    <p slot="header" style="color:#f60;text-align:center">
                        <Icon type="ios-information-circle"></Icon>
                        <span>Delete confirmation</span>
                    </p>
                    <div style="text-align:center">
                        <p>Are you sure to delete this state?</p>
                    </div>
                    <div slot="footer">
                        <Button type="error" size="large" long :disabled="isDeleting" :loading="isDeleting" @click="deleteState">{{ isDeleting? 'Deleting ...': 'Delete state'}}</Button>
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
                country_id: '',
                name: ''
            },
            addModal: false,
            editModal: false,
            isAdding: false,
            states: [],
            countries: [],
            selectedObj: {
                id: -1,
                country_id: '',
                name: ''
            },
            index: -1,
            isDeleting: false,
            deleteModal: false,
            searchData: {
                country_id: '',
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
        const res2 = await this.callApi('get', '/api/country/get')
        if (res2.status == 200) {
            this.countries = res2.data.countries
        } else {
            this.swr('')
        }
        axios({
            method: 'GET',
            url: '/api/state/get/'+this.page+'/'+this.pageSize
        })
        .then(res => {
            if (res.status == 200) {
                this.states = res.data.states
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
        async addState() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/state/create', this.data)
            if (res.status == 200) {
                this.states.unshift(res.data)
                this.s('State has been registered successfully')
                this.addModal = false
                this.isAdding = false
                this.data = {
                    country_id: '',
                    name: ''
                }
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.country_id) return this.i(errors.country_id)
                    if (errors.name)       return this.i(errors.name)
                }
            }
        },
        async editState() {
            this.isAdding = true
            const res = await this.callApi('post', '/api/state/edit', this.selectedObj)
            if (res.status == 200) {
                this.s('The state has been updated successfully')
                this.editModal = false
                this.isAdding = false
            } else {
                if (res.status == 401) {
                    this.isAdding = false
                    let errors = res.data.errors
                    if (errors.country_id) return this.i(errors.country_id)
                    if (errors.name)       return this.i(errors.name)
                }
            }
        },
        showEditModal(state) {
            this.selectedObj = state
            this.editModal = true
        },
        async deleteState() {
            this.isDeleting = true
            const res = await this.callApi('post', '/api/state/delete', this.selectedObj)
            if (res.status == 200) {
                this.states.splice(this.index, 1);
                this.deleteModal = false
                this.isDeleting = false
                this.s('The state has been deleted successfully')
            } else {
                this.swr('')
            }
        },
        showDeleteModal(state, index) {
            this.index = index
            this.deleteModal = true
            this.selectedObj = state
        },
        handlePage(p) {
            this.page = p
            axios({
                method: 'GET',
                url: '/api/state/get/'+this.page+'/'+this.pageSize
            })
            .then(res => {
                if (res.status == 200) {
                    this.states = res.data.states
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
                const res = await this.callApi('post', '/api/state/search', val)
                if (res.status == 200) {
                    this.states = res.data
                }
            },
            deep: true
        },
        'selectedObj.country_id': {
            async handler(val) {
                const res = await this.callApi('post', '/api/country/getById', {country_id: val})
                if (res.status == 200) {
                    this.selectedObj.country.name = res.data.name
                }
            },
            deep: true
        }
    }
}
</script>